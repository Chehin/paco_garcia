<?php

namespace App\Http\Controllers\fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\NoteEtiquetas;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fe\FeUtilController;
use Carbon\Carbon;

class BlogController extends Controller
{
	public function __construct(Request $request)
    {
		parent::__construct($request);
		
        $this->resource = $request->input('edicion');
		$this->filterNote = \config('appCustom.'.$request->input('id_edicion'));
		$this->orden = $request->input('orden');
		$this->id_relacion = $request->input('id_relacion');
		$this->iDisplayLength = $request->input('iDisplayLength');
		$this->iDisplayStart = $request->input('iDisplayStart');		
        $this->fotos = $request->input('fotos');
        $this->id_idioma = $request->input('idioma');
		$this->subdominio = $request->input('subdominio');
		$this->search = $request->input('search');
		$this->filtro_archivo = $request->input('filtro_archivo');
		$this->tag = $request->input('tag');
    }
	public function index(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$aResult['data']['nota'] = array();
			$aResult['data']['total'] = array();
			$pageSize = $this->iDisplayLength;
            $offset = $this->iDisplayStart;
            $currentPage = ($offset / $pageSize) + 1;
			$sort = $this->orden;
			$rand = false;
			$filtro_archivo = $this->filtro_archivo;
			if($sort=='rand'){
				$rand = true;
			}else{
				$sortDir = $sort['dir'];
				$sortCol = $sort['col'];
            }
			
			Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
			});
		
			if($this->tag){
				$aItems = NoteEtiquetas::
				select('editorial_notas.id_nota as id', 'editorial_notas.sumario','editorial_notas.titulo', 'fecha')
				->leftJoin('editorial_notas', 'editorial_notas.id_nota','=', 'editorial_notas_etiquetas.id_nota')
				->where('id_etiqueta', $this->tag)
				;
			
			}else{
				$aItems = Note::select('id_nota as id', 'sumario','titulo', 'fecha');
			}
            $aItems
            ->where('editorial_notas.habilitado', 1)
			->where('editorial_notas.id_edicion',$this->filterNote)
			->groupBy('editorial_notas.id_nota')
			;
			if($this->search){
				$search = $this->search;
				$aItems->where(function($query) use ($search){
                    $query
                        ->where('editorial_notas.titulo','like',"%{$search}%")
                        ->orWhere('editorial_notas.sumario','like',"%{$search}%")
                    ;
                });
			}
			if($filtro_archivo){
				$aItems
				->whereMonth('editorial_notas.fecha', '=', $filtro_archivo['m'])
				->whereYear('editorial_notas.fecha', '=',$filtro_archivo['a'])
				;
			}
			if($rand){
				$aItems = $aItems->inRandomOrder();
			}else{
				$aItems = $aItems->orderBy($sortCol, $sortDir);
			}
			$etiquetas_all = Util::getEtiquetasBlog();
			$aResult['data']['etiquetas_all'] = $etiquetas_all?$etiquetas_all->getData():array();

			$archivos = FeUtilController::postMonth($this->filterNote);
			$aResult['data']['archivos'] = $archivos?$archivos->getData():array();
			
			$aItems = $aItems->paginate($pageSize);
			foreach ($aItems as $item) {
				if($this->fotos){
					$aOItems = FeUtilController::getImages($item->id,$this->fotos,'blog');
				}else{
					$aOItems = '';
				}
				//idioma texto
				if($this->id_idioma>0){
					$lItem = FeUtilController::getLenguage($item->id, $this->id_idioma);
                }
                setlocale(LC_TIME, 'es_ES');
                $fecha = Carbon::parse($item->fecha);
				Carbon::setUtf8(true);
				

				$data = array(
					'id' => $item->id,
					'titulo' => ($this->id_idioma>0 && $lItem?$lItem->titulo:$item->titulo),
					'sumario' => ($this->id_idioma>0 && $lItem?$lItem->sumario:$item->sumario),
					'fecha' => array(
                        'dia' => $fecha->format('d'),
                        'mes' => $fecha->formatLocalized('%b'),
                        'anio' => $fecha->format('Y')
                    ),
					'fotos' => $aOItems
				);
				array_push($aResult['data']['nota'],$data);
			}
			$aResult['data']['total'] = $aItems->total();
			return response()->json($aResult);
		}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
	}
	public function nota(Request $request, $id){

		$aResult = Util::getDefaultArrayResult(); 
		$evento = Note::
		select('editorial_notas.id_nota as id','editorial_notas.id_seccion as id_categoria','editorial_notas.titulo','editorial_notas.sumario','editorial_notas.texto','editorial_notas.orden','editorial_notas.icono','editorial_notas.id_video','editorial_notas.updated_at','editorial_secciones.seccion', 'editorial_notas.fecha', 'editorial_notas.id_edicion')
		->leftJoin('editorial_secciones','editorial_secciones.id_seccion','=','editorial_notas.id_seccion')
		->where('editorial_notas.habilitado',1)
		->where('editorial_notas.id_nota',$id)
		->get();

		foreach ($evento as $item) {
				$aOItems = FeUtilController::getImages($item->id,99,'blog');
				$archivos = array();

				$fecha = array();
				if($item->fecha){
					setlocale(LC_TIME, 'es_ES');
					$fecha = Carbon::parse($item->fecha);
					Carbon::setUtf8(true);
					$fecha = array(
                        'dia' => $fecha->format('d'),
                        'mes' => $fecha->formatLocalized('%b'),
                        'anio' => $fecha->format('Y')
					);
					$archivos = FeUtilController::postMonth($item->id_edicion);
					$archivos = $archivos?$archivos->getData():array();
				}
				$etiquetas = Note::find($id)->etiquetasBlog()->get();
				
				$etiquetas_all = Util::getEtiquetasBlog();

				$relacionado = Note::join("items_related","items_related.parent_id","=","editorial_notas.id_nota")
							->where('items_related.related_id', $id)
							->where('editorial_notas.habilitado', 1)
							->get();
				
				foreach ($relacionado as $item) {
					$fotosRel = FeUtilController::getImages($item->id_nota,1, 'blog');
				}
				
				$data = array(
					'id' => $item->id,
					'id_categoria' => $item->id_categoria,
					'categoria' => $item->seccion,
					'titulo' => $item->titulo,
					'sumario' => $item->sumario,
					'texto' => $item->texto,
					'orden' => $item->orden,
					'icono' => $item->icono,
					'id_video' => $item->id_video,
					'fecha' => $fecha,
					'etiquetas' => $etiquetas,
					'etiquetas_all' => $etiquetas_all?$etiquetas_all->getData():array(),
					'archivos' => $archivos,
					'fotos' => $aOItems,
					'fotosRel' => $fotosRel,
					'relacion' => $relacionado
				);
				array_push($aResult['data'],$data);
			}
		
        return response()->json($aResult);
	}
}

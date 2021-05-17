<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 860);
use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\PedidosClientes;
use App\AppCustom\Models\MktListasPersonas;
use App\AppCustom\Models\MktListas;
use Excel;

class ImportarClientesController extends Controller
{
	//use ResourceTraitController;

	public $resource;
    public $resourceLabel;
	public $filterNote;
	public $viewPrefix = 'importador';
	public $modelName = '';
	/**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
		
		parent::__construct($request);
		
        $this->resource = 'importarClientes';
		$this->resourceLabel = 'Importar Clientes';
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    public function import(Request $request){
        $aResult = Util::getDefaultArrayResult();
            if($request->hasFile('file')){
                $path = $request->file('file')->getRealPath();
				$data = Excel::load($path, function($reader){})->get();
                    if(!empty($data) && $data->count()){
                        foreach($data as $key => $value){
							$cliente = new PedidosClientes();
								if($value->nombre!='' && $value->apellido!='' && $value->email!=''){
								
									$cliente->nombre = $value->nombre;
									$cliente->apellido = $value->apellido;
									$cliente->mail = $value->email;
									$cliente->telefono = $value->telefono;
									$cliente->dni = $value->dni;
									$cliente->created_at = $value->registracion;
									$cliente->registrado = 1;
									$cliente->confirm_mail = 1;
								
									if( $value->lista==''){
										$value->lista='Lista contactos registrados en la web';
									}

									$find=PedidosClientes::where('mail', $cliente->mail)->first();
									$listas=explode(',', $value->lista);
									foreach ($listas as $key => $lista) {

										if(!$find){
											$cliente->save();
											$idP= PedidosClientes::all();
											$id=$idP->last();
											$idL=Util::getIdByName($lista);

											if($idL==null){//no existe la lista la creo
												$listas = new MktListas();
												$listas->nombre=$lista;
												$listas->habilitado=1;
												$listas->save();
												$idP= MktListas::all();
												$idL=$idP->last()->id;

												$personasListas = new MktListasPersonas();
												$personasListas->id_persona = $id->id;
												$personasListas->id_lista = $idL;
												$personasListas->save();
											}else{
												$idL=$idL->id;
												$personasListas = new MktListasPersonas();
												$personasListas->id_persona = $id->id;
												$personasListas->id_lista = $idL;
												$personasListas->save();										}
										}else{
											$update=PedidosClientes::where('mail', $value->email)
																	->update(['nombre' => $value->nombre,
																			'apellido' => $value->apellido,  
																			'mail' => $value->email,
																			'telefono' => $value->telefono,
																			'dni' => $value->dni]);
											$id=$find->id;
											$idL=Util::getIdByName($lista);
											if($idL==null){//no existe la lista la creo
												$listas = new MktListas();
												$listas->nombre= $lista;
												$listas->habilitado=1;
												$listas->save();
												$idP= MktListas::all();
												$idL=$idP->last()->id;
												
												$updateL=MktListasPersonas::where('id_persona',$id)
																		->update(['id_lista' => $idL]);
											}else{
												$idL=$idL->id;
												$updateL=MktListasPersonas::where('id_persona',$id)
																		->update(['id_lista' => $idL]);
											}
										}
									}
								}else{
									$k=$key+2;
									$aWarns[] = "El nombre o apellido o email estan vacios en fila {$k}";
								}
						}
                    }
			}
			
			if (isset($aWarns)) {
				$aResult['status'] = 2;
				$aResult['msg'] = \config('appCustom.messages.someWarnings');
				$aResult['data'] = $aWarns;
			}

            $aViewData = [
                'aResult' => $aResult
            ];
            
            return response()
                ->view('importador.importarClientes', ['aViewData' => $aViewData])
                ;
        
    }

	
	    
    
    
    
    
}

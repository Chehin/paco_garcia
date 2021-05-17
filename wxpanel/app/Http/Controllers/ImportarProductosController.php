<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 860);

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\AppCustom\Models\ProductosImportar;
use App\AppCustom\Models\Sync;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\ProductosCodigoStock;
use App\AppCustom\Models\Rubros;
use App\AppCustom\Models\SubRubros;
use App\AppCustom\Models\SubSubRubros;
use App\AppCustom\Models\Marcas;
use App\AppCustom\Models\Etiquetas;
use App\AppCustom\Models\Colores;
use App\AppCustom\Models\Talles;
use App\AppCustom\Models\Genero;
use App\AppCustom\Models\Deportes;
use App\AppCustom\Models\Pais;
use App\AppCustom\Models\SucursalesStock;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\PreciosProductos;

class ImportarProductosController extends Controller
{
    //use ResourceTraitController;
	const minsToDetectSyncNok = 90;
	const syncModel = 'App\AppCustom\Models\Sync';

	public $resource;
    public $resourceLabel;
	public $filterNote;
	public $viewPrefix = '';
	public $modelName = '';
	/**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
		
		parent::__construct($request);
		
        $this->resource = 'importarProductos';
		$this->resourceLabel = 'Importar/Sincronizar Productos';
		$this->viewPrefix = 'productos';

		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '512M');
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function procesar(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
		if ($this->user->hasAccess($this->resource . '.create')) {
			$aWarns = [];
			if (!$request->file('file')) {
				$aResult['status'] = 1;
				$aResult['msg'] = 'Debe seleccionar un archivo';
			} else {				
				try {
					$productosActualizados = 0;
					$data = Excel::load($request->file('file'), function ($reader) {})->toArray();
					if (!empty($data) && count($data) > 0) {
						//pongo update_import en 0
						$update_import = Productos::where('update_import', 1)
						->update(array('update_import' => 0));
						$rowNum = 0;
						foreach ($data as $row) {
							$rowNum++;
							unset($talle);
							unset($cod_producto);
							unset($marca);
							unset($genero);

							$codigo = Util::separarCodigo($row['nro_rep']); //requerido
							$talle = $row['talle'];
							$descripcion = $row['des_rep'];
							$stock = $row['stock_ini']; // stock real
							$precio_de_venta = $row['contado_w']; //requerido
							$precio_de_lista = $row['lista_w'];
							$precio_de_meli = $row['ml_w'];
							$genero = $row['genero'];
							$tipo_medida = $row["tipo_med"];
										

							if ($codigo && $descripcion) {

								//formateo el codigo
										
									//busco si el producto existe
									$item = ProductosCodigoStock::select('id_producto')
									->where('codigo', 'like', $codigo)->first();

									//empiezo a crear o actualizar los productos
									//extraigo marca de la descripcion
									$aux = Util::clear($descripcion);								
									$marca =  Util::matchMarcas($aux);
									

									if (isset($marca)) {
										$marca = ucwords(strtolower(($marca)));
										// Verifico si la marca existe
										$marca = Marcas::where('nombre','=',$marca)->first();
										if (!$marca) {
											// Si no existe se debe crear la marca 
											$array_marca = array(
												'nombre' => $marca
											);
											$request->request->add($array_marca);
											$aResult = app('App\Http\Controllers\MarcasController')->store($request);
											$aResult = json_decode($aResult->getContent(),true);
											$marca = Marcas::where('nombre','=',$marca)->first();
										}
									}

									
									// Verifico si el genero existe
									if (isset($genero)) {
										$generoAux = Genero::where('genero','=',utf8_encode($genero))->first();

										if(!$generoAux) {
											// Si no existe se debe crear la genero 
											$nombreGenero = utf8_encode($genero);
											$genero = new Genero;
											$genero->genero = $nombreGenero;
											$genero->save();
										}else{
											$genero = $generoAux;
										}
									}

									
									if (isset($talle)) {
										//talle
										$talleAux = Talles::select('id')->where('nombre', $talle)->where('habilitado', 1)->first();
									

										if(!$talleAux){										
											$nombreTalle = $talle;
											$talle = new Talles;
											$talle->nombre = $nombreTalle;
											$talle->habilitado = 1;
											$talle->save();
										}else{
											$talle = $talleAux;
										}
									}

									
									$alto = 15;
									$ancho = 15;
									$largo = 30;
									$peso = 800;

								
									
									if(!$item){
										$descripcion = ucwords(strtolower(($descripcion)));
										$array_send = array(
											'nombre' => $descripcion,
											'orden' => 0,
											'habilitado' => 0
										);
										$array_send['alto'] = $alto;
										$array_send['ancho'] = $ancho;
										$array_send['largo'] = $largo;
										$array_send['peso'] = $peso;
										
										
										if (isset($marca)){
											$array_send['id_marca'] = $marca->id;
										} else {
											$array_send['id_marca'] = '';
										}

										if (isset($genero)){
											$array_send['id_genero'] = $genero->id;
										} else {
											$array_send['id_genero'] = '';
										}
									
										//para este caso no hay rubro ni subrubro usare una funcion diferente	
										$request->request->add($array_send);
										$aResult = app('App\Http\Controllers\ProductosController')->storeImport($request);
										$aResult = json_decode($aResult->getContent(),true);

										if ($aResult['status'] == 1) {
											$aWarns[] = "El producto no se pudo crear fila {$rowNum}. ".$aResult['msg'][0].". No Importado";
										}else{
											$id_producto = $aResult['id_producto'];
											$aWarns[] = "El producto de la fila {$rowNum} Fue Creado.";

											//CARGAR talle y stock											
											$codStock = new ProductosCodigoStock;
											$codStock->id_producto = $id_producto;
											$codStock->id_talle = isset($talle)?$talle->id:0;
											$codStock->codigo = $codigo;
											$codStock->stock = $stock;
											$codStock->save();											
											
											//CARGAR PRECIO
											// obtengo la moneda por default

											if($precio_de_venta > 0 && $precio_de_lista > 0){
												$moneda_default = Util::getMonedaDefault();
												$id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
						
												// Array para guardar el precio del producto
												$array_precio = array(
													'resource_id' => $id_producto,
													'id_moneda' => $id_moneda,
													'precio_venta' => $precio_de_venta,
													'precio_lista' => isset($precio_de_lista)?$precio_de_lista:null,
													'precio_meli' => $precio_de_meli
												);											

												$request->request->add($array_precio);
												$aResult = app('App\Http\Controllers\PreciosRelatedController')->store($request);					
												$aResult = json_decode($aResult->getContent(),true);
												if ($aResult['status'] == 1) {
													$aWarns[] = "El precio no se pudo crear para la fila {$rowNum}. No Importado";
												}
											}else{
												$aWarns[] = "El precio no se pudo crear para la fila {$rowNum}. No Importado";
											}
											

											//sucursal
											$stock_sucursal = new SucursalesStock;
											$stock_sucursal->id_codigo_stock = $codStock->id;
											$stock_sucursal->id_sucursal = 417;
											$stock_sucursal->stock = $stock;
											$stock_sucursal->save();

											//update_import
											$update_import = Productos::find($id_producto);
											$update_import->habilitado = 0;
											$update_import->update_import = 1;
											$update_import->save();

										}
									}else{
										
										$id_producto = $item->id_producto;																				
										$descripcion = ucwords(strtolower(($descripcion)));

										//actualizo precio - stock -
										$id_talle = isset($talle)?$talle->id:0;										

										$codStock = ProductosCodigoStock::select('id')
														->where([
															'codigo' => $codigo,
															'id_talle' => $id_talle,
															'id_producto' => $id_producto
														])->first();

															
														if($codStock){
															//actualizo el stock y color si no inserto el nuevo que ingrese
															$update=ProductosCodigoStock::where('id',$codStock->id)
																	->update(['stock' => $stock]);
														}else{
															$codStock = new ProductosCodigoStock;
															$codStock->codigo = $codigo;
															$codStock->id_talle = $id_talle;
															$codStock->id_producto = $id_producto;
															$codStock->save();
														}

											//stock por sucursal
																						
										
								
												$stock_sucursal = SucursalesStock::
												select('id')
												->where('id_codigo_stock', $codStock->id)
												->where('id_sucursal', 417)
												->first();
												
												if($stock_sucursal){
													$update=SucursalesStock::where('id',$stock_sucursal->id)
														->update(['stock' => $stock]);
												}else{
													$stock_sucursal = new SucursalesStock;
													$stock_sucursal->id_codigo_stock = $codStock->id;
													$stock_sucursal->id_sucursal = 417;
													$stock_sucursal->stock = $stock;
													$stock_sucursal->save();
												}												
												
											
											
																						
											//Actualizar PRECIO
											// obtengo la moneda por default
											$moneda_default = Util::getMonedaDefault();
											$id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
					
											// Array para guardar el precio del producto
											$array_precio = array(
												'resource_id' => $id_producto,
												'id_moneda' => $id_moneda,
												'precio_venta' => $precio_de_venta,
												'precio_lista' => isset($precio_de_lista)?$precio_de_lista:null
											);

											// Obtengo el id del registro en la tabla inv_precios
											$id_precio = PreciosProductos::
											select('id')
											->where('id_moneda','=',$id_moneda)
											->where('id_producto','=',$id_producto)
											->first();											

											$request->request->add($array_precio);
											if ($id_precio) {
												$id_precio = $id_precio->id;
												// Si tiene un precio cargado actualizo el valor
												$aResult = app('App\Http\Controllers\PreciosRelatedController')->update($request,$id_precio);
											} else {
												// Si no tiene un precio cargado lo creo
												if($precio_de_venta > 0 && $precio_de_lista > 0){
													$aResult = app('App\Http\Controllers\PreciosRelatedController')->store($request);
												}else{
													$aResult['status'] = 1;
													$aResult=response()->json($aResult);
													$aWarns[] = "El precio no se pudo crear para la fila {$rowNum}. No Importado";
												}
											}
											
											$aResult = json_decode($aResult->getContent(),true);
											if ($aResult['status'] == 1) {
												$aWarns[] = "El precio no se pudo actualizar para la fila {$rowNum}. No Importado";
											}
									
										//update_import
										$update_import = Productos::find($id_producto);
										//actualizo marca (se cargan de descripcipn)
										if (isset($marca)){
											$update_import->id_marca = $marca->id;
										} else {
											$update_import->id_marca = 0;
										}
										$update_import->update_import = 1;
										$update_import->save();
									}
									$productosActualizados++;
								

							} elseif(!$codigo){
								$aWarns[] = "El codigo está vacía en la fila {$rowNum}. No Importado";
							} elseif(!$descripcion) {
								$aWarns[] = "La descripción está vacío en la fila {$rowNum}. No Importado";
							}
						}

						if ($productosActualizados > 0) {
							$usuarioProducto = new ProductosImportar;
							$usuarioProducto->id_usuario = \Sentinel::getUser()->id;
							$usuarioProducto->save();
							
						} else {
							$aResult['status'] = 1;
							$aResult['msg'] = 'No se ha podido actualizar. Verifique los datos de la planilla o el tipo de archivo';
						}
					}			
				} catch (\Exception $e) {
					$aResult['status'] = 1;
					$aResult['msg'] = $e->getMessage();
				}
			}
		} else {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		
		if ($aWarns) {
			$aResult['status'] = 2;
            $aResult['msg'] = \config('appCustom.messages.someWarnings');
			$aResult['data'] = $aWarns;
		}
		
		$aViewData = [
			'lastUpdate' => ImportarProductosUtilController::getLastUpdate(), 
			'aResult' => $aResult
		];
		
		return response()
            ->view('productos.importarProductos.importarProductos', ['aViewData' => $aViewData])
			;
	}
	
	protected function getLastSync() {
		
		$syncModel = static::syncModel;
		
		$this->update = $syncModel::orderBy('id', 'desc')->first();
		
		return $this->update;
	}
	
	public function getLastSyncStatus() {
		
		$lastSync = $this->getLastSync();
		
		if ($lastSync) {
			if (!$lastSync->done) {
				if ($lastSync->last_start->diffInMinutes(Carbon::now()) >= static::minsToDetectSyncNok) {
					$lastSync->lastSyncOk = 0;
				} else {
					$lastSync->lastSyncOk = 1;
				}
			}
		}
		
		
		return response()->json($lastSync);
	}


	public function syncModal(Request $request){
		
		$aViewData['data'] = $request['data'];
		$aViewData['msg'] = $request['msg'];
		$aViewData['status'] = $request['status'];

		$aResult['status'] = 0;
		$aResult['html'] = \View::make("productos.productos.productosSync")
            ->with('aViewData', $aViewData)
            ->render()
        ;

        return response()->json($aResult);
	}
}

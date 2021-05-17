<?php

namespace App\Console;
ini_set('max_execution_time', 860);
ini_set('memory_limit', '-1');
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\AppCustom\Util;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\AppCustom\Models\Sync;
use App\AppCustom\Models\ProductosImportar;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\CampaignTesting;
use Symfony\Component\Process\Process;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
		\App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

		//cron para mailling
			//se cambia el charset para los emojis
			\DB::statement("SET NAMES 'utf8mb4'");
			//mails por lista
		
			$campanias=Util::campanias();
				if (! $campanias->isEmpty()) {
					foreach ($campanias as $c) {
						$lista=Util::contactos($c->id);
						$hora=date_create($c->hora);
						$fecha=$c->fecha;
						$schedule->call(function () use ($c,$lista) {
							Util::campania($c,$lista);
						})->everyMinute() 
						->when(function() use ($fecha, $hora){
						return ( date('Y-m-d')==$fecha && date('H:i')==date_format($hora,'H:i') );
						})
						;
					}
				} /* else {
					\Log::info('Notificación. No se encontraron campañas estandares para enviar');
				} */
			
		//########################################### campañas AB  ######################################################################
					//mails contenidos en la lista 								
					$campaniasA=Util::campaniasA();					
					$campaniasB=Util::campaniasB();
				
					if (! $campaniasA->isEmpty()) {
						foreach ($campaniasA as $ca) {
								$lista=	Util::contactosAB($ca->id_campania,'a');
								$hora=date_create($ca->hora);
								$fecha=$ca->fecha;								
										$schedule->call(function () use($ca,$lista) {
											Util::campaniaA($ca,$lista);									
										})->everyMinute() 
										->when(function() use ($fecha, $hora, $ca){
		
													if(date('Y-m-d')==$fecha && date('H:i')==date_format($hora,'H:i')){
															//guardo la ultima fecha de envio
															$item=[
																'fechaenvio' => \Carbon\Carbon::now()
															];
															CampaignTesting::where('id','=', $ca->id_campania)->update($item);
													}
													
													return ( date('Y-m-d')==$fecha && date('H:i')==date_format($hora,'H:i') );
										})
										;
						}
					}/*  else {
						\Log::info('Notificación. No se encontraron campañas A para enviar');
					} */
		
		
					if (! $campaniasB->isEmpty()) {
				
						foreach ($campaniasB as $cb) {
								$lista=	Util::contactosAB($cb->id_campania,'b');				
								$hora=date_create($cb->hora);
								$fecha=$cb->fecha;
										$schedule->call(function () use($cb,$lista) {
											Util::campaniaB($cb,$lista);									
										})->everyMinute() 
										->when(function() use ($fecha, $hora){												
													return ( date('Y-m-d')==$fecha && date('H:i')==date_format($hora,'H:i') );
										})
										;
						}
					} /* else {
						\Log::info('Notificación. No se encontraron campañasB para enviar');
					} */
			
				
				//se vuelve a utf8
				\DB::statement("SET NAMES 'utf8'");


		/********************************************************************************************************/
            
        $aResult = Util::getDefaultArrayResult();	
		/**
		 * Funcion que muestra las imagenes que hay en la ruta pasada como parametro
		 */
      	$ruta = '../fe/public/sync';    
		$productos = Productos::all();
		// Se comprueba que realmente sea la ruta de un directorio
			if (is_dir($ruta)){
				// Abre un gestor de directorios para la ruta indicada
				$gestor = opendir($ruta);

				// Recorre todos los archivos del directorio
				while (($archivo = readdir($gestor)) !== false)  {
					// Solo buscamos archivos sin entrar en subdirectorios
					if (is_file($ruta."/".$archivo)) {
							$aWarns = [];
											
								try {
									$productosActualizados = 0;
									$data = Excel::load($ruta."/".$archivo, function ($reader) {})->toArray();
											
									if (!empty($data) && count($data) > 0) {
										//pongo update_import en 0
										$update_import = Productos::where('update_import', 1)
										->update(array('update_import' => 0));
										$rowNum = 0;
										foreach ($data as $rowNum => $row) {
											$schedule->call(function () use($row,$rowNum,$aResult) {
												Util::importar($row,$rowNum,$aResult);												
											})->everyMinute();
										}

										if ($productosActualizados > 0) {
											$usuarioProducto = new ProductosImportar;
											$usuarioProducto->id_usuario = 58;
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
									
						if ($aWarns) {
							$aResult['status'] = 2;
							$aResult['msg'] = \config('appCustom.messages.someWarnings');
							$aResult['data'] = $aWarns;
						}
						
						$aViewData = [
							'lastUpdate' => Util::getLastUpdate(), 
							'aResult' => $aResult
						];
		
	
					}            
				}

				//guardo fecha de sync
				$sync = new Sync();
				$sync->date_up = Carbon::now()->format('Y-m-d H:i:s');
				$sync->last_start = Carbon::now()->format('Y-m-d H:i:s');
				$sync->save();

				// Cierra el gestor de directorios
				closedir($gestor);
				return response()->json($aResult);
			} else {
				echo "No es una ruta de directorio valida<br/>";
				return response()->json($aResult);
			}

    }
	

}

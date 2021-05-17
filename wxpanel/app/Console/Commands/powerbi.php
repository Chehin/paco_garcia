<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Exception;


class powerbi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'power:bi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta una vista, exporta a excel y sube el excel a drive.';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info("[+] realizando consulta a la vista power_bi");
        $all = \DB::table('power_bi')->get();
       
        //convierto todo el resultado del query en un array
        if($all){
            $all =  array_map(function($value){
                return (array)$value;
            },$all);
    
            $filepath =  public_path().'/power_bi';
            //exporto a excel
            \Log::info("[+] guardando en excel.");
            Excel::create('power_bi', function($excel) use ($all){
                $excel->sheet('pedidos_productos', function($sheet) use ($all){
                    $sheet->fromArray($all);
                });
            })->store('xlsx',$filepath);
        }


        if(file_exists($filepath.'/power_bi.xlsx')){
            //subir excel a drive
            putenv('GOOGLE_APPLICATION_CREDENTIALS='.public_path().'/credentials/credentials.json');
            // $folderId= '1S9JtZxGdYNIOdNPv38JP_oyeTOVeE0um';
            $folderId= '1AqCY1ycwYzVWWMZI5K3FlbVrLewj42rP';
            $archivoId ='';
            $nameFile = 'power_bi.xlsx';
            $files = $filepath.'/'.$nameFile;
            $client = new Google_Client();
            $client->useApplicationDefaultCredentials();
            $client->setScopes(['https://www.googleapis.com/auth/drive.file']);
            try{
                $service = new Google_Service_Drive($client);
                // listar archivos drive
                $optParams = array(
                    'pageSize' => 10,
                    // 'fields' => "nextPageToken, files(contentHints/thumbnail,fileExtension,iconLink,id,name,size,thumbnailLink,webContentLink,webViewLink,mimeType,parents)",
                    'fields' => "nextPageToken, files(fileExtension,id,name,size,webContentLink,webViewLink,mimeType,parents)",
                    'q' => "'".$folderId."' in parents"
                );
                $results = $service->files->listFiles($optParams);
                
                foreach($results as $fil){
                    $archivoId  = $fil->id;
                }

                // si existe algun id de archivo excel lo actualizo
                if($archivoId){
                    \Log::info("[+] actualizo archivo excel en drive.");
                    $file = new Google_Service_Drive_DriveFile();
                    $file->setName($nameFile);
                    $updatedFile = $service->files->update(
                        $archivoId,
                        $file,
                        array(
                            'data' => file_get_contents($files),
                            'mimeType'=>'application/vnd.ms-excel',
                            'uploadType' => 'media'
                        )
                    );
                }else{
                    \Log::info("[+] creo un archivo excel en drive.");
                    $file = new Google_Service_Drive_DriveFile();
                    $file->setName($nameFile);
                    $file->setParents(array($folderId));
                    $file->setMimeType('application/vnd.ms-excel');
                    $archivo = $service->files->create(
                        $file,
                        array(
                            'data'=>file_get_contents($files),
                            'mimeType'=>'application/vnd.ms-excel',
                            'uploadType'=>'media'
                        )
                    );
                }

            }catch(Google_Service_Exception $g){
                $m=json_decode($g->getMessage());
                \Log::error($m->error->message);

            }catch(Exception $e){
                \Log::error('cron power_bi: ',$e->getMessage());
            }
            //end exportar excel a drive
        }
    }
}

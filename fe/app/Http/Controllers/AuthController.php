<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Api;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Socialite;

class AuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request,Api $api,$provider)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        //TRAIGO LOS DATOS DEL USUARIO SI ES QUE EXISTE
        $array_send = array(
            'mail' => $user->email
        );
        $data = Util::aResult();
		try {			
             $post = http_build_query($array_send);
             $data = $api->client->resJson('GET', 'user?'.$post)['data'];
             if($data['status']==0){
                $existingUser=$data['data'];
             }else{
                $existingUser='';
             }

		} catch (RequestException $e) {
			\Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				\Log::error($e->getMessage());
			}
        }
        
        //SI EXISTE INICIA SESSION, SI NO SE registra E INICIA SESSION
        if($existingUser){
            auth($existingUser);

            if(isset($_SESSION['carrito']['carrito'][0])){
                return redirect('cart');
            }else{
                return redirect('cuenta');
            }

        }else{
            //separo (guarda el nombre y apellido en name)
            $nombre=explode(" ", $user->name);
                      
            if($provider=='facebook'){
                $array_registro = array(
                    'nombre' => $nombre[0],
                    'apellido' => $nombre[count($nombre)-1],
                    'email' => $user->email,
                    'fb_id' => $user->id,
                    'g_id' => ''
                );
            }else{
                $array_registro = array(
                    'nombre' => $nombre[0],
                    'apellido' => $nombre[count($nombre)-1],
                    'email' => $user->email,
                    'fb_id' => '',
                    'g_id' => $user->id
                );
            }
            
            //registro el usuario y redirecciono
            try {			
                $post = http_build_query($array_registro);
                $data = $api->client->resJson('GET', 'userRegister?'.$post)['data'];
            
                if($data['status']==0){

                    auth($data['data']);
                    
                    if(isset($_SESSION['carrito']['carrito'][0])){
                        return redirect('cart');
                    }else{
                        return redirect('cuenta');
                    }

                 }else{
                    return redirect('/login')->with('status', 'Algo salio mal en el registro!');
                 }
                
            } catch (RequestException $e) {
               \Log::error(Psr7\str($e->getRequest()));
               if ($e->hasResponse()) {
                   \Log::error($e->getMessage());
               }
           }
            
        }
    }
}
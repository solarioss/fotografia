<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evento;
use App\Foto;

class RecognitionController extends Controller
{
    public function retornarEvento(Request $request){
        

        $evento= Evento::select('id','nombre','fecha')->where('id',$request->evento)->get();
        if(is_null($evento)){
            return response()->json('Evento no existe',500);
        }
        /*$album= Foto::where('id_evento',$request->evento)->get();
        $respuesta=[
            'evento'=>$evento,
            'album'=>$album
        ];*/

        return response()->json($evento,200);
    }

    public function retornarAlbum(Request $request){

        $album= Foto::select('id','url','id_evento')->where('id_evento',$request->evento)->get();
     

        
        return response()->json($album,200);//<--------cambiar a 200
    }

    public function buscarFotos(Request $request, $id_user, $id_evento)
    {
        $result=[];
        $fotos = Foto::where('id_evento', $id_evento)->get();
        foreach ($fotos as $foto) {
            //detecta las caras de la foto
            $faces = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => 'af06c0ccb7184986a9ac2fead707a999',
                'Content-Type' => 'application/json'
            ])->post(
                'https://brazilsouth.api.cognitive.microsoft.com/face/v1.0/detect',
                [
                    'url' => 'http://191.232.172.106/' . asset($foto->url) 
                ]
            );

            if (!$faces->ok()) {
                return response()->json('Error al detectar una imagen', 500);
            }

            //variables 
            $match=false;
            foreach ($faces->json() as $face) {
                //pregunta si el usuario esta en esa cara
                if (!$match){
                    $response = Http::withHeaders([
                        'Ocp-Apim-Subscription-Key' => 'af06c0ccb7184986a9ac2fead707a999',
                        'Content-Type' => 'application/json'
                    ])->post(
                        'https://brazilsouth.api.cognitive.microsoft.com/face/v1.0/findsimilars',
                        [
                            'faceId' => $face['faceId'],
                            'largeFaceListId' => $id,
                            "maxNumOfCandidatesReturned"=> 1000,
                        ]
                    );
                    if (!$response->ok()) {
                        return response()->json('Error al detectar una cara', 500);
                    }
                    foreach ($response->json() as $detection) {
                        if ($detection['confidence']>=0.8 && !$match){
                            $match=true;
                            array_push($result, $foto);
                        }
                    }
                    
                }//endif 
            }//endforeach
        }//endforeach
        return response()->json($result, 200);
    }
}

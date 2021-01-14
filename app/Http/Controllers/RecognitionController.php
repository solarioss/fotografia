<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evento;
use App\Foto;

class RecognitionController extends Controller
{
    public function retornarAlbum(Request $request){
        

        $evento= Evento::where('id',$request->evento)->get();
        if(is_null($evento)){
            return response()->json('Evento no existe',500);
        }
        $album= Foto::where('id_evento',$request->evento)->get();
        $respuesta=[
            'evento'=>$evento,
            'album'=>$album
        ];

        return response()->json($respuesta,300);
    }
}

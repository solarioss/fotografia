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

        return response()->json($evento,300);
    }

    public function retornarAlbum(Request $request){

        $album= Foto::select('id','url','id_evento')->where('id_evento',$request->evento)->get();

        return response()->json($album,300);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Usuario;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class UsuarioController extends Controller
{
    public function singup(Request $request){

        $validator = Validator::make($request->all(),
        ['nombre'=>'required|string',
        'email'=>'required|string',
        'sexo'=>'required|string',
        'edad'=>'required|int',
        'foto1'=>'required|string',
        'foto2'=>'required|string',
        'foto3'=>'required|string',
        'password'=>'required|string']);

        if ($validator->fails()) {
            return response()->json('Datos invalidos');
        }

        $existe= Usuario::where('email',$request->email)->first();
        if(!is_null($existe)){
            return response()->json('este email ya esta registrado');
        }

        $datos=[
            
            'nombre'=>$request->nombre,
            'email'=>$request->email,
            'sexo'=>$request->sexo,
            'edad'=>$request->edad,
            'foto1'=>$request->foto1,
            'foto2'=>$request->foto2,
            'foto3'=>$request->foto3,
            'password'=>Hash::make($request->password),            
        ];

        $usuario=Usuario::create($datos);        
        return response()->json('Cuenta Creada');    
        
    }

    public function login(Request $request){

        $credentials=$this->validate(request(),
        ['email'=>'email|required|string',
        'password'=>'required|string']);


        $usuario=Usuario::select('id','nombre','email','password','sexo','edad','foto1','foto2','foto3')->where('email',$request->email)->first();
        
        if (Auth::guard('usuario')->attempt($credentials)){ 
            return response()->json($usuario,200);
        }
            
        return response()->json('incorrecto',500);

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Usuario;
use App\Models\Ingreso;
use App\Models\Login;
use App\Models\Servicio;
use Exception;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    //
    public function servicios(){
        $response = Servicio::get();
        return response()->json([
            'success' => true,
            'result' => $response,
        ]);
    }

    public function usuarios(Request $request){
        if(!is_null($request->identificacion)){
            $response = Usuario::where('identificacion', $request->identificacion)->first();
        }else{
            $response = Usuario::get();
        }
        return response()->json([
            'success' => true,
            'result' => $response,
        ]);
    }

    public function ingresosByUsuario(Request $request){
        $usuario = Usuario::where('identificacion', $request->identificacion)->first();
        $ingresos = [];
        if(!is_null($usuario)){
            $ingresos = Ingreso::where('id_usuario',$usuario->id)->get();

        }
        return response()->json([
            'success' => true,
            'result' => $ingresos,
        ]);
        //TODO: MAppear el nombre de los servicios
    }

    public function login(Request $request){
        try {
            $response = Login::where('identificacion_usuario',$request->identificacion)
            ->where('password',$request->password)->first();

            if($response != false){
                return response()->json([
                    'success' => true,
                    'message' => "Bienvenido",
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => "Password o usuario errado",
                ]);
            }   
            
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function guardar(Request $request){

        // $identificacion = 1234;
        // $nombres = "octavio";
        // $telefono = 3004158084;
        // $direccion = "cll 17 #32-80";
        // $id_paciente_admision = 025;
        // $id_servicio = 2;

        $identificacion = $request->identificacion;
        $nombres =  $request->nombres;
        $telefono =  $request->telefono;
        $direccion =  $request->direccion;
        $id_paciente_admision =  $request->id_paciente_admision;
        $id_servicio =  $request->id_servicio;

        $response = Usuario::where('identificacion', $identificacion)->first();
        if(is_null($response)){
            //nUEVO
            $usuario = new Usuario();
            $usuario->id = null;
            $usuario->identificacion = $identificacion;
            $usuario->nombres = $nombres;
            $usuario->telefono = $telefono;
            $usuario->direccion = $direccion;
            $usuario->save();

            $usuario_nuevo = Usuario::where('identificacion', $identificacion)->first();
          
            $ingreso = new Ingreso();
            $ingreso->id_usuario = $usuario_nuevo->id;
            $ingreso->id_paciente_admision = $id_paciente_admision;
            $ingreso->id_servicio = $id_servicio;
            $ingreso->save();

            $login = new Login();
            $login->identificacion_usuario = $identificacion;
            $login->password = $identificacion; //INicial, by defualt
            $login->save();

            
        }else{
            //Existe
            $usuario = Usuario::where('identificacion', $identificacion)->first();
            $ingreso = new Ingreso();
            $ingreso->id_usuario = $usuario->id;
            $ingreso->id_paciente_admision = $id_paciente_admision;
            $ingreso->id_servicio = $id_servicio;
            $ingreso->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Ingreso registrado exitosamente',
        ]);
    }

    public function eliminar(Request $request){
        $usuario = Usuario::where('identificacion',$request->identificacion)->first();
        if(!is_null($usuario)){
            DB::table('ingresos')->where('id_usuario', $usuario->id)->delete(); 
            DB::table('login')->where('identificacion_usuario',  $usuario->identificacion)->delete(); 

            // Ingreso::where('id_usuario', $usuario->id)->delete(); //todo: reVIEW
            // Login::where('identificacion_usuario', $usuario->identificacion)->delete();
            $usuario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Registro eliminado exitosamente',
            ]);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'El usuario no existe',
            ]);
        }
    }

    public function editar(Request $request){
        $usuario = Usuario::where('identificacion',$request->identificacion)->first();
        if(!is_null($usuario)){
            $usuario->nombres = $request->nombres;
            $usuario->telefono = $request->telefono;
            $usuario->direccion = $request->direccion;
            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => 'Registro modificado exitosamente',
            ]);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'El usuario no existe',
            ]);
        }
    }
}

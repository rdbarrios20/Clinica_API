<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Usuario;
use App\Models\Ingreso;
use App\Models\Login;
use App\Models\Servicio;
use Carbon\Carbon;
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
            // iterar la fecha por cada registro cambiando el formato de la fecha por uno mas amigable
            $responseV2 = [];
            foreach ($response as $item) {
                // dd($item->created_at->format('Y-m-d h:m'));
                
                $responseV2 [] = [
                    "id"=> $item->id,
                    "identificacion" => $item->identificacion,
                    "nombres" => $item->nombres,
                    "telefono" => $item->telefono,
                    "direccion" => $item->direccion,
                    "created_at" => $item->created_at->format('Y-m-d h:m'),
                    "updated_at" => $item->updated_at->format('Y-m-d h:m')
                ];
                //ejecmplo de date en tipo string
                // $date_string = '31-01-2021';
                // $item->created_at = Carbon::createFromFormat('d-m-Y', $date_string  )->format('Y-m-d'); // >> 2021-01-31
                
                //Como item->created_at ya este tipo TIMESTAMP, laravel automatimticamente lo mapea a CARBON object class,
                //POr lo tanto,  no se necita la anterior implemnetacion
                // $item->created_at = $item->created_at->format('Y-m-d h:m');
            }
            // cuando termina de iterar remplaza response por $responseV2
            $response = $responseV2;
        }
        return response()->json([
            'success' => true,
            'result' => $response,
        ]);
    }

    // Objectivo: Obtener los ingresos del usuario, VISITANTE, No del admin.... por su identificacion
    public function ingresosByUsuario(Request $request){
        // Consultar el usuario por identificacion
        $usuario = Usuario::where('identificacion', $request->identificacion)->first();
        $ingresos = [];
        // Validamos que exista el usuario
        if(!is_null($usuario)){
            // Si el usuario existe entonces consultamos los ingresos de ese usuario
            // select ingresos.id_paciente_admision,ingresos.id_servicio,servicios.nombre,ingresos.created_at
            // from ingresos inner join servicios
            // on ingresos.id_servicio = servicios.id_servicio;
            $ingresos = Ingreso::select('ingresos.id_paciente_admision','ingresos.id_servicio',
            'servicios.nombre as nombre_servicio','ingresos.created_at')
            ->join('servicios','ingresos.id_servicio','=','servicios.id_servicio')
            ->where('ingresos.id_usuario',$usuario->id)
            ->get();

            $ingresos2 =[];

             foreach ($ingresos as $item) {
                 if(is_null($item->id_paciente_admision)){
                    $id_paciente = 0;
                 }else{
                    $id_paciente = $item->id_paciente_admision;
                 }

                $ingresos2[] = [
                 "id_paciente_admision" => $id_paciente,
                 "id_servicio" => $item->id_servicio,
                 "nombre_servicio" => $item->nombre_servicio,
                 "created_at" => $item->created_at->format('Y-m-d h:m')
                ];
             }

             $ingresos = $ingresos2;
            // Retornamos los ingresos de ese usuario
            return response()->json([
                'success' => true,
                'result' => $ingresos,
            ]);

        }else {
            return response()->json([
                'success' => false,
                'result' => $ingresos,
                'message' => "Este usuario no existe",
            ]);
        }
        //TODO: MAppear el nombre de los servicios
    }

    // validar las credenciales de acceso del usuario, vistanteN. NO DEL ADMIN...
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

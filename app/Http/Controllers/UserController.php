<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Helpers\Token;

class UserController extends Controller
{
    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        if (!$user->emailExists($request->email) && !$user->usernameExists($request->username)){
            $user->create($request);
            $data_token = [
                "email" => $user->email,
            ];
            $token = new Token($data_token);
            
            $tokenEncoded = $token->encode();
            return response()->json([
                "token" => $tokenEncoded
            ], 200);
        }else{
            return response()->json(["Error" => "No se pueden crear usuarios con el mismo email o nombre de usuario"]
            , 401);
        }
    }

    public function login(Request $request){
        //Buscar el email de los usuarios de la BDD
        $user = User::where('email', $request->email)->get();

        //Comprobar que email y password de user son iguales
            $data = ['email' => $request->email];

            $user = User::where($data)->first();

            if(decrypt($user->password) == $request->password)
            {
                //Si son iguales codifico el token
                $token = new Token($data);
                $tokenEncode = $token->encode();

                //Devolver la respuesta en formato JSON con el token y código 200
                return response()->json([
                "token" => $tokenEncode
                ],200);
                var_dump('Login correcto');
            }
            return response()->json([
            "error" => "Usuario incorrecto"
            ],401);
    }

    public function resetPassword (Request $request){
        $user = User::where('email',$request->email)->first();  
        if (isset($user)) {   
            $newPassword = self::randomPassword();
            self::sendEmail($user->email,$newPassword);
            
                $user->password = $newPassword;
                $user->update();
            
            return response()->json([
                "Operación con éxito" => "Se ha reestablecido su contraseña, revise su correo electrónico."
                ],200);
        }else{
            return response()->json([
                "Error" => "El email no está registrado en la aplicación"
                ],401);
        }
    }

    public function changePassword (Request $request){
        $email = $request->data_token->email;
        $user = User::where('email', $email)->first();
        
        if (isset($user)) {
            $user->password = decrypt($user->password);
            $newPassword = $request->password;
            if ($newPassword != $user->password){
                $user->password = encrypt($newPassword);
                $user->update();
                return response()->json([
                    "Operación con éxito" => "Se ha reestablecido su contraseña."
                    ],200);
            }else{
            return response()->json([
                "Error" => "La contraseña es la misma que la anterior"
                ],401);
            }
        }else{
            return response()->json(["Error" => "El ususario no existe"], 400);
        } 
    }

    public function sendEmail ($email, $newPassword){
        $para      = $email;
        $titulo    = 'Recuperar contraseña de Bienestar Digital';
        $mensaje   = 'Se ha establecido "'.$newPassword.'" como su nueva contraseña. Podrá cambiarla si quiere por la que desee desde la pantalla de su perfil.';
        mail($para, $titulo, $mensaje);
    }
    
    public function randomPassword() {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $email = $request->data_token->email;
        $user = User::where('email', $email)->first();
        
        if(isset($user)){
            $user->password = decrypt($user->password);
            return response()->json([
                'name' => $user->name, 
                'surname'=> $user->surname, 
                'username' => $user->username , 
                'email' => $user->email, 
                'password' => $user->password
            ], 200);
        }else{
            return response()->json(["Error" => "El usuario no existe"], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

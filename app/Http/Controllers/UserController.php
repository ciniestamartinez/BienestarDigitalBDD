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
        $user->create($request); 
        var_dump('añadido');

        $token = new Token($user->email);
        $tokenEncode = $token->encode();
        
        return response()->json([
            "token" => $tokenEncode
        ],200);
    }

    public function login(Request $request){
        //Buscar el email de los usuarios de la BDD
        $user = User::where('email', $request->email)->get();

        //Comprobar que email y password de user son iguales
            $data = ['email' => $request->email];

            $user = User::where($data)->first();

            if($user->password == $request->password)
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

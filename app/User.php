<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'surname', 'username', 'email', 'password'];

    public function create($request){
        $user = new User;
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = encrypt($request->password);
        $user->save();
    }

    public function emailExists($email){
        $users = self::where('email',$email)->get();
        
        foreach ($users as $key => $value) {
            if($value->email == $email){
                return true;
            }
        }
        return false;
    }

    public function usernameExists($username){
        $users = self::where('username',$username)->get();
        
        foreach ($users as $key => $value) {
            if($value->username == $username){
                return true;
            }
        }
        return false;
    }
}

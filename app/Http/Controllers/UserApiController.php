<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
    
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }
    
    public function login(Request  $request)
    {
        $request->validate([
            
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if(!Auth::attempt($request->only('email' , 'password'))){
            return response()->json(['error'=>'Unauthorized'],404) ;
        }
        $user = User::where('email', $request->email)->first();

        return response()->json([
            'message'=>'login successfuly' ,
            'user'=> $user 
        ]) ;
    }



    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return $request->input();
        // $username = $request->username;
        // $password = $request->password;
        // $query = User::where(array(
        //     'username' => $username,
        //     'password' => md5($password)
        // ))->firstOrFail();
    }
}

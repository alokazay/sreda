<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function getMain()
    {
        if (Auth::check()) {
            return view('welcome');
        }
        return Redirect::to('login');
    }

    public function getLogin()
    {
        return view('auth.login', array('title' => 'Login'));
    }

    public function postLogin()
    {
        $login = request()->get('login');
        $password = request()->get('password');

        if (Auth::attempt(array('name' => $login, 'password' => $password))) {
            // Проверить активирован или нет
            if (Auth::user()->activation == 1) {
                return Response::json(array('success' => "true"), 200);
            } else {
                Auth::logout();
                return Response::json(array('success' => "false", 'error' => 'Sorry, please write to admin'), 200);
            }
        } else {
            return Response::json(array('success' => "false", 'error' => 'Sorry login or password invalid'), 200);
        }
    }

    public function getLogout()
    {
        Auth::logout();
        return Redirect::to(URL::to('/') . '/');
    }


    public function getSignup()
    {
        return view('auth.signup', array('title' => 'Signup'));
    }

    public function postSignup(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'name' => 'required|unique:users,name,',
            'phone' => 'required',
            'number' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(array('success' => "false", 'error' => $error), 200);
        }

        $user = new User();
        $user->name = request('name');
        $user->phone = request('phone');
        $user->activation = 1;
        $user->group_id = 2;
        if ($user->save()) {
            Auth::loginUsingId($user->id);
            return response(array('success' => "true"), 200);
        } else {
            return response(array('success' => "false", 'error' => "Error!"), 200);
        }
    }

}


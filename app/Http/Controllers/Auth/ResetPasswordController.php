<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected function resetPassword($user, $password)
    {

        // Set the user's new password
        $user->password = bcrypt($password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Log the user in after resetting password
        Auth::login($user);

        $id = $user->id;
        $email = $user->email;
        $name = $user->name;
        $role = $user->role_id;

        if ($user->status == 1) {
            $systemRoles = getSystemRoles($role);
            if ($systemRoles && count($systemRoles)) {
            $systemRoles = json_decode($systemRoles[0]['permission'], true);
            } else {
            $systemRoles = [];
            }
            Session::put('id', $id);
            Session::put('name', $name);
            Session::put('email', $email);
            Session::put('role', $role);
            Session::put('access_name', 'admin');
            Session::put('system_roles', $systemRoles);
        }

    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
}

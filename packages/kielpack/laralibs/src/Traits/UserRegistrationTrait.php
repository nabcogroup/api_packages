<?php

namespace KielPack\LaraLibs\Traits;

use Illuminate\Auth\Events\Registered;
use KielPack\LaraLibs\Users\Role;
use KielPack\LaraLibs\Users\User;

trait UserRegistrationTrait {
     
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:30',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'user_role'=> 'required|exists:roles,id'
        ]);
    }


    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        
        return redirect()->back();
    }


    public function showRegistrationForm()
    {
        $roles =  Role::all();
        $action = route('register');

        return view('auth.register',compact('roles','action'));
    }

    public function showRegistrationFormWithRoles() {
        
        $users = User::with('roles')->get();

        $roles =  Role::all();
        $action = route('register');

        return view('auth.register',compact('users','user','roles','action'));

    }
}
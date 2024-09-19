<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;

class UserController extends Controller
{
    /*
    public function store(Request $request)
    {
        $validateData = $request->validate*([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        User::create($validateData);

        ...
    }
    */

    public function store(StoreUserRequest $request)
    {
        // la validación se realiza en el Form Request StoreUserRequest
        $validateData = $request->validated();

        User::create($validateData);

    }
}

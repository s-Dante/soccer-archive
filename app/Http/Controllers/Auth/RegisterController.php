<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __invoke(RegisterUserRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo_path'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        $user = User::create([
            'name'                => $data['name'],
            'last_name'           => $data['last_name'],
            'username'            => $data['username'],
            'email'               => $data['email'],
            'password'            => Hash::make($data['password']),
            'gender'              => $data['gender'],
            'birthdate'           => $data['birthdate'],
            'country'             => $data['country'],
            'profile_photo_path'  => $data['profile_photo_path'] ?? null,
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('status', 'Registro exitoso');
    }
}

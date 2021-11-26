<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterStudent;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource as UserResource;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function register(RegisterStudent $request, $id)
    {
        $school = School::find($id)->first();
        $name = $request->name;
        $email    = $request->email;
        $password = $request->password;
        $user = User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password), 'user_type' => 'student']);
        $start = $school->students;
        $school->update([
            'students' => array_push($start, $user)
        ]);
        $school->save();
        return (new UserResource($school));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        $managers = User::where('role','manager')->get();
        return view('admin.users.create', compact('managers'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'role'=>'required|in:admin,manager,employee',
        ]);

        $user = User::create([
            'name' => $r->name,
            'email' => $r->email,
            'password' => Hash::make($r->password ?? 'password'),
            'role' => $r->role,
            'company_id' => auth()->user()->company_id,
            'manager_id' => $r->manager_id ?: null,
        ]);

        return redirect()->back()->with('success','User created. Default password: "password" (change it).');
    }
}

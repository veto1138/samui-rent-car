<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'nickname' => $request->name, // ใช้ชื่อเป็น nickname
            'phone' => $request->phone ?? 'ไม่ระบุ', // ใช้ phone จาก request หรือ default
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'ผู้ใช้งานถูกสร้างเรียบร้อยแล้ว');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'ข้อมูลผู้ใช้งานถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'ไม่สามารถลบบัญชีของตัวเองได้');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'ผู้ใช้งานถูกลบเรียบร้อยแล้ว');
    }
}

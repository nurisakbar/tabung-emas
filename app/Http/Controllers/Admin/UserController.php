<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'nasabah')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $goldSaving = $user->goldSaving;
        $transactions = $user->transactions()->latest()->paginate(10);
        return view('admin.users.show', compact('user', 'goldSaving', 'transactions'));
    }

    public function updateStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();
        
        return redirect()->back()->with('success', 'User status updated successfully.');
    }
}

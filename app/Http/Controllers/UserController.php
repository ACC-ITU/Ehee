<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $query = User::query();

        if ($request->filled('search')) {
            $query->whereAny(['name', 'email'], 'LIKE', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('admin')) {
            $query->where('is_admin', $request->admin);
        }
        $users = $query->orderBy('is_admin')->paginate(20);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function activate(User $user)
    {
        $user->status = 1;
        $user->save();

        return redirect()->back();
    }

    public function deactivate(User $user)
    {
        $user->status = 0;
        $user->save();

        return redirect()->back();
    }

    public function makeAdmin(User $user)
    {
        $user->is_admin = 1;
        $user->save();

        return redirect()->back();
    }

    public function removeAdmin(User $user)
    {
        $user->is_admin = 0;
        $user->save();

        return redirect()->back();
    }
}

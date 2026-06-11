<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    // List account requests (pending) and other statuses
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->get('filter') === 'pending') {
            $query->where('status', 'pending');
        } elseif ($request->get('filter') === 'rejected') {
            $query->where('status', 'rejected');
        } elseif ($request->get('filter') === 'approved') {
            $query->where('status', 'approved');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('accounts.index', compact('users'));
    }

    // Approve account
    public function approve(User $user, Request $request)
    {
        if (!Auth::check() || (Auth::user()->role ?? '') !== 'super-admin') {
            abort(403);
        }

        $user->status = 'approved';
        $user->approved_at = now();
        $user->approved_by = Auth::id();
        $user->save();

        return redirect()->back()->with('success', 'Account approved.');
    }

    // Reject account with optional reason
    public function reject(User $user, Request $request)
    {
        if (!Auth::check() || (Auth::user()->role ?? '') !== 'super-admin') {
            abort(403);
        }

        $request->validate(['reason' => 'nullable|string|max:1000']);

        $user->status = 'rejected';
        $user->rejected_at = now();
        $user->rejection_reason = $request->input('reason');
        $user->save();

        return redirect()->back()->with('success', 'Account rejected.');
    }

    // Delete pending account
    public function destroy(User $user)
    {
        if (!Auth::check() || (Auth::user()->role ?? '') !== 'super-admin') {
            abort(403);
        }

        // Allow deleting pending or approved accounts
        $status = $user->status ?? 'approved';
        if (!in_array($status, ['pending', 'approved'])) {
            return redirect()->back()->with('error', 'Only pending or approved accounts can be deleted.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Account deleted.');
    }
}

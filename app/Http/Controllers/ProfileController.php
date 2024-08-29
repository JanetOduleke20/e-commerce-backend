<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        if ($request->is('admin/*')) {
            return view('profile.edit', [
                'user' => auth()->guard('admin')->user()
            ]);
        } else {
            return view('profile.edit', [
                'user' => $request->user(),
            ]);
        }
        
    }

    /**
     * Update the user's profile information.
     */
    public function updateAdmin(Request $request)  {
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'max: 255'],
            'email' => ['required', 'lowercase', 'email', 'unique:'.Admin::class]
        ]);

        $authenticatedAdmin = Auth::guard('admin')->user();
       
        $update = Admin::where('id', $authenticatedAdmin->id)->update([
            'name' => $request->name,
            'email' => $request->email
        ]);
        if ($update) {
            return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
        } else {
            return Redirect::route('admin.profile.edit')->with('error', 'error-occurred');
        }
        
    }

    
    public function update( ProfileUpdateRequest $request) : RedirectResponse
    {
            $request->user()->fill($request->validated());
    
            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }
    
            $request->user()->save();
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

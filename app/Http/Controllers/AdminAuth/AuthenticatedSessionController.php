<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */

    protected $admin = [];
    public function create(): View
    {
        return view('adminauth.login');
    }


    public function getAdmin() {
        session_start();
      
        // if (session(['admin'])) {
        //     return response()->json([
        //         'status' => true,
        //         'admin' => Auth::guard('admin')->user()
        //     ]);
        // } else {
        //     return response()->json([
        //         'status' => false
        //     ]);
        // }

        return response()->json([
            // 'session' => session(['admin'])
            'session' => Auth::guard('admin')->user()
        ]);
        
    }
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        session_start();
       
        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('admin')->attempt($credentials)) {
            session()->regenerate();
     
            // return redirect('/admin/dashboard');
            // session(['admin' => Auth::guard('admin')->user()]);
            // Session::put('admin', Auth::guard('admin')->user());
            
            return response()->json([
                'status' => true,
                'admin' => Auth::guard('admin')->user(),
                // 'session' => Session::get('admin')
            ]);
        }else {
            // return back()->with([
            //     'email' => 'The provided credentials do not match our records'
            // ]);
            return response()->json([
                'status' => false,
                'error' => 'The provided credentials do not match our records'
            ]);

        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    { 
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    // ==== AUTHENTICATION & ROUTING ====

    public function login()
    {
        return view('login');
    }

    public function signup()
    {
        return view('registration');
    }

    public function logincheck(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('kasir');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function registercheck(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $validation['password'] = bcrypt($validation['password']);

        $user = User::create($validation);
        Auth::login($user);

        return redirect()->route('login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function goKasir()
    {
        if (Auth::check() && Auth::user()->usertype == 'admin') {
            return redirect()->route('dashboard');
        } elseif (Auth::check() && Auth::user()->usertype == 'user') {
            return view('kasir');
        } else {
            return redirect()->route('login');
        }
    }

    // public function dashboard()
    // {
    //     if (Auth::check() && Auth::user()->usertype == 'admin') {
    //         return view('dashboard');
    //     } else {
    //         return redirect()->route('login');
    //     }
    // }

    public function accountManagement()
    {
        if (Auth::check() && Auth::user()->usertype == 'admin') {
            $users = User::where('usertype', 'user')->orderBy('created_at', 'desc')->paginate(5);
            return view('account-management', compact('users'));
        } else {
            return redirect()->route('login');
        }
    }

    // ==== USER CRUD (API) ====

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ];

        if ($request->has('password') && !empty($request->password)) {
            $rules['password'] = 'string|min:6';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->has('password') && !empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'updated_at' => $user->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUsers()
    {
        try {
            $users = User::select('id', 'name', 'email', 'created_at', 'updated_at')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching users: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUser($id)
    {
        try {
            $user = User::select('id', 'name', 'email', 'created_at', 'updated_at')
                ->find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==== ORDER & SALES ====

    public function orderList()
    {
        if (Auth::check() && Auth::user()->usertype === 'user') {
            $orders = Order::with('items')
                ->whereDate('created_at', now()->toDateString())
                ->orderBy('created_at', 'desc')
                ->paginate(5);
            return view('order-list', compact('orders'));
        } else {
            return redirect()->route('login');
        }
    }

    public function downloadOrderListPdf()
    {
        if (Auth::check() && Auth::user()->usertype === 'user') {
            $orders = Order::with('items')
                ->whereDate('created_at', now()->toDateString())
                ->orderBy('created_at', 'desc')
                ->get();
            $pdf = Pdf::loadView('order-list-pdf', compact('orders'));
            return $pdf->download('order-list-' . now()->format('Y-m-d') . '.pdf');
        } else {
            return redirect()->route('login');
        }
    }

    public function salesReport(Request $request)
    {
        if (Auth::check() && Auth::user()->usertype == 'admin') {
            $query = Order::with('items');

            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->input('date'));
            }
            if ($request->filled('year')) {
                $query->whereYear('created_at', $request->input('year'));
            }

            $orders = $query->orderBy('created_at', 'desc')->paginate(10);
            return view('sales-report', compact('orders'));
        } else {
            return redirect()->route('login');
        }
    }
}

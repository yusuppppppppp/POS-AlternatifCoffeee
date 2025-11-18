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
        
        // Clear all session data
        session()->flush();
        session()->regenerate();
        
        // Create response with cache control headers
        $response = redirect()->route('login');
        
        // Add headers to prevent caching
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
        
        return $response;
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

    public function dashboard()
    {
        if (Auth::check() && Auth::user()->usertype == 'admin') {
            return view('dashboard');
        } else {
            return redirect()->route('login');
        }
    }

    public function accountManagement()
    {
        if (Auth::check() && Auth::user()->usertype == 'admin') {
            $users = User::where('usertype', 'user')->orderBy('created_at', 'desc')->paginate(5);
            return view('account-management', compact('users'));
        } else {
            return redirect()->route('login');
        }
    }

    public function changePassword()
    {
        if (Auth::check()) {
            return view('change_password');
        } else {
            return redirect()->route('login');
        }
    }

    public function updatePassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak benar'])->withInput();
        }

        // Update password
        try {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return back()->with('success', 'Password berhasil diubah!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengubah password'])->withInput();
        }
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        // Add password validation only if user wants to change password
        if ($request->filled('current_password') || $request->filled('new_password') || $request->filled('new_password_confirmation')) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|min:6|confirmed';
        }

        $messages = [
            'name.required' => 'Nama harus diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain',
            'current_password.required' => 'Password saat ini harus diisi untuk mengubah password',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Update name and email
            $user->name = $request->name;
            $user->email = $request->email;

            // Update password if provided
            if ($request->filled('current_password')) {
                // Check if current password is correct
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'Password saat ini tidak benar'])->withInput();
                }

                // Update password
                $user->password = Hash::make($request->new_password);
            }

            $user->save();

            $message = 'Profil berhasil diperbarui!';
            if ($request->filled('current_password')) {
                $message = 'Profil dan password berhasil diperbarui!';
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage()])->withInput();
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

    public function orderList(Request $request)
    {
        if (Auth::check() && Auth::user()->usertype === 'user') {
            // Get per_page from request, default to 5
            $perPage = $request->get('per_page', 5);
            $search = $request->get('search', '');
            
            $query = Order::with(['items', 'user'])
                ->whereDate('created_at', now()->toDateString());
            
            // Add search functionality
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('customer_name', 'LIKE', "%{$search}%")
                      ->orWhere('order_type', 'LIKE', "%{$search}%")
                      ->orWhere('id', 'LIKE', "%{$search}%")
                      ->orWhere('total_amount', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('items', function($itemQuery) use ($search) {
                          $itemQuery->where('menu_name', 'LIKE', "%{$search}%");
                      });
                });
            }
            
            $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            // Calculate total orders for today (same as dashboard)
            $totalOrdersToday = Order::whereDate('created_at', now()->toDateString())
                ->count();
                
            // Calculate total revenue for today (same as dashboard)
            $totalRevenueToday = Order::whereDate('created_at', now()->toDateString())
                ->sum('total_amount');
                
            return view('order-list', compact('orders', 'totalOrdersToday', 'totalRevenueToday', 'perPage', 'search'));
        } else {
            return redirect()->route('login');
        }
    }

    public function downloadOrderListPdf()
    {
        if (Auth::check() && Auth::user()->usertype === 'user') {
            $orders = Order::with(['items', 'user'])
                ->whereDate('created_at', now()->toDateString())
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Calculate summary statistics
            $totalOrders = $orders->count();
            $totalRevenue = $orders->sum('total_amount');
            $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
            
            // Get the admin who is downloading the report
            $downloadedBy = Auth::user()->name;
            
            $pdf = Pdf::loadView('order-list-pdf', compact('orders', 'totalOrders', 'totalRevenue', 'averageOrderValue', 'downloadedBy'));
            return $pdf->download('order-list-' . now()->format('Y-m-d') . '.pdf');
        } else {
            return redirect()->route('login');
        }
    }

    public function downloadSalesReportPdf(Request $request)
    {
        if (Auth::check() && Auth::user()->usertype == 'admin') {
            $query = Order::with(['items', 'user']);
            $fileName = 'sales-report';
            $period = 'custom'; // Default period for filter-based downloads
            
            // Handle date filtering based on filter type (same as salesReport method)
            $filterType = $request->get('filter_type', 'single');
            
            if ($filterType === 'range') {
                // Date range filtering
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
                    $fileName .= '-range-' . $request->start_date . '-to-' . $request->end_date;
                    $period = 'custom';
                } elseif ($request->filled('start_date')) {
                    $query->whereDate('created_at', '>=', $request->input('start_date'));
                    $fileName .= '-from-' . $request->start_date;
                    $period = 'custom';
                } elseif ($request->filled('end_date')) {
                    $query->whereDate('created_at', '<=', $request->input('end_date'));
                    $fileName .= '-until-' . $request->end_date;
                    $period = 'custom';
                }
            } elseif ($filterType === 'week') {
                // Weekly filtering (this week)
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                $fileName .= '-week-' . now()->format('Y-W');
                $period = 'week';
            } elseif ($filterType === 'month') {
                // Monthly filtering (this month)
                $query->whereYear('created_at', now()->year)
                      ->whereMonth('created_at', now()->month);
                $fileName .= '-month-' . now()->format('Y-m');
                $period = 'month';
            } else {
                // Single date filtering
                if ($request->filled('date')) {
                    $query->whereDate('created_at', $request->input('date'));
                    $fileName .= '-date-' . $request->date;
                    $period = 'single';
                } else {
                    // If no date filter, default to today
                    $query->whereDate('created_at', now()->toDateString());
                    $fileName .= '-today-' . now()->format('Y-m-d');
                    $period = 'today';
                }
            }
            
            // Support legacy period parameter for backward compatibility
            if ($request->filled('period') && !$request->filled('filter_type')) {
                $period = $request->get('period', 'today');
                switch ($period) {
                    case 'today':
                        $query->whereDate('created_at', now()->toDateString());
                        $fileName = 'sales-report-today-' . now()->format('Y-m-d');
                        break;
                    case 'week':
                        $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        $fileName = 'sales-report-week-' . now()->format('Y-W');
                        break;
                    case 'month':
                        $query->whereYear('created_at', now()->year)
                              ->whereMonth('created_at', now()->month);
                        $fileName = 'sales-report-month-' . now()->format('Y-m');
                        break;
                    case 'custom':
                        if ($request->filled('start_date') && $request->filled('end_date')) {
                            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
                            $fileName = 'sales-report-custom-' . $request->start_date . '-to-' . $request->end_date;
                        }
                        break;
                }
            }
            
            // Add search functionality if provided
            $search = $request->get('search', '');
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('customer_name', 'LIKE', "%{$search}%")
                      ->orWhere('order_type', 'LIKE', "%{$search}%")
                      ->orWhere('id', 'LIKE', "%{$search}%")
                      ->orWhere('total_amount', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('items', function($itemQuery) use ($search) {
                          $itemQuery->where('menu_name', 'LIKE', "%{$search}%");
                      });
                });
                $fileName .= '-search-' . substr($search, 0, 20);
            }
            
            $orders = $query->orderBy('created_at', 'desc')->get();
            
            // Calculate summary statistics
            $totalOrders = $orders->count();
            $totalRevenue = $orders->sum('total_amount');
            $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
            
            // Get the admin who is downloading the report
            $downloadedBy = Auth::user()->name;
            
            // Pass filter_type to view for proper title display
            // Use the filterType from the request, or determine from period
            $filterType = $request->get('filter_type', 'single');
            if (!$request->filled('filter_type')) {
                // If filter_type not provided, use period to determine
                $filterType = $period;
            }
            
            // Pass date information for single date display
            $selectedDate = $request->get('date');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            
            $pdf = Pdf::loadView('sales-report-pdf', compact('orders', 'period', 'totalOrders', 'totalRevenue', 'averageOrderValue', 'downloadedBy', 'filterType', 'selectedDate', 'startDate', 'endDate'));
            return $pdf->download($fileName . '.pdf');
        } else {
            return redirect()->route('login');
        }
    }

    public function salesReport(Request $request)
    {
        if (Auth::check() && Auth::user()->usertype == 'admin') {
            $query = Order::with(['items', 'user']);

            // Handle date filtering based on filter type
            $filterType = $request->get('filter_type', 'single');
            
            if ($filterType === 'range') {
                // Date range filtering
                if ($request->filled('start_date')) {
                    $query->whereDate('created_at', '>=', $request->input('start_date'));
                }
                if ($request->filled('end_date')) {
                    $query->whereDate('created_at', '<=', $request->input('end_date'));
                }
            } elseif ($filterType === 'week') {
                // Weekly filtering (this week)
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($filterType === 'month') {
                // Monthly filtering (this month)
                $query->whereYear('created_at', now()->year)
                      ->whereMonth('created_at', now()->month);
            } else {
                // Single date filtering (existing functionality)
                if ($request->filled('date')) {
                    $query->whereDate('created_at', $request->input('date'));
                }
            }
            
            if ($request->filled('year')) {
                $query->whereYear('created_at', $request->input('year'));
            }

            // Add search functionality
            $search = $request->get('search', '');
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('customer_name', 'LIKE', "%{$search}%")
                      ->orWhere('order_type', 'LIKE', "%{$search}%")
                      ->orWhere('id', 'LIKE', "%{$search}%")
                      ->orWhere('total_amount', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('items', function($itemQuery) use ($search) {
                          $itemQuery->where('menu_name', 'LIKE', "%{$search}%");
                      });
                });
            }

            // Get per_page from request, default to 10
            $perPage = $request->get('per_page', 10);
            $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            // Calculate monthly totals (same as dashboard)
            $currentMonth = \Carbon\Carbon::now()->startOfMonth();
            
            // Total orders this month (same as dashboard)
            $totalOrdersThisMonth = Order::whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->count();
                
            // Total revenue this month (same as dashboard)
            $totalRevenueThisMonth = Order::whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->sum('total_amount');
            
            return view('sales-report', compact('orders', 'perPage', 'search', 'totalOrdersThisMonth', 'totalRevenueThisMonth'));
        } else {
            return redirect()->route('login');
        }
    }
}

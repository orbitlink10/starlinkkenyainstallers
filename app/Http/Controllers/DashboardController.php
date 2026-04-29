<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'orders' => Order::count(),
            'invoices' => Invoice::count(),
            'users' => User::count(),
            'enquiries' => Enquiry::count(),
            'totalRevenue' => Order::whereNotNull('paid_at')->sum('amount'),
            'recentOrders' => Order::where('created_at', '>=', now()->subDays(7))->count(),
            'newUsers' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'activeUsers' => User::where('last_login_at', '>=', now()->subDay())->count(),
        ];

        return view('dashboard.index', compact('stats'));
    }
}

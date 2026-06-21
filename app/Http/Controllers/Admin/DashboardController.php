<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'services' => Service::count(),
            'products' => Product::count(),
            'categories' => Category::count(),
            'active_services' => Service::where('active', true)->count(),
            'active_products' => Product::where('active', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

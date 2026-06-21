<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Models\Tenant;

class PublicController extends Controller
{
    private function getTenant(): ?Tenant
    {
        return Tenant::where('active', true)->first();
    }

    public function home()
    {
        $tenant = $this->getTenant();

        if (!$tenant) {
            return view('public.coming-soon');
        }

        $featuredServices = Service::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenant->id)
            ->where('active', true)
            ->orderBy('order')
            ->take(6)
            ->get();

        $featuredProducts = Product::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenant->id)
            ->where('active', true)
            ->orderBy('order')
            ->take(6)
            ->get();

        return view('public.home', compact('tenant', 'featuredServices', 'featuredProducts'));
    }

    public function services()
    {
        $tenant = $this->getTenant();

        $categories = Category::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenant?->id)
            ->where('type', 'service')
            ->where('active', true)
            ->with(['services' => fn($q) => $q->where('active', true)->orderBy('order')])
            ->get();

        return view('public.services', compact('tenant', 'categories'));
    }

    public function products()
    {
        $tenant = $this->getTenant();

        $categories = Category::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenant?->id)
            ->where('type', 'product')
            ->where('active', true)
            ->with(['products' => fn($q) => $q->where('active', true)->orderBy('order')])
            ->get();

        return view('public.products', compact('tenant', 'categories'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Medicine;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'medicineCount' => Medicine::count(),
            'categoryCount' => Category::count(),
            'expiringSoonCount' => Medicine::expiringSoon()->count(),
            'expiredCount' => Medicine::expired()->count(),
            'expiringSoon' => Medicine::with('category')->expiringSoon()->orderBy('expired')->take(5)->get(),
            'latestMedicines' => Medicine::with('category')->latest('id')->take(5)->get(),
        ]);
    }
}

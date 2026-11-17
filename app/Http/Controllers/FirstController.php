<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\View\View;

class FirstController extends Controller
{
    public function home(): View
    {
        return view('index', [
            'latestMedicines' => Medicine::with('category')->latest('id')->take(6)->get(),
            'categories' => Category::withCount('medicines')->orderBy('name')->get(),
            'medicineCount' => Medicine::count(),
        ]);
    }

    public function about(): View
    {
        return view('about');
    }
}

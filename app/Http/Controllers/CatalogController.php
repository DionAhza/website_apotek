<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->trim()->toString();
        $categoryId = $request->integer('category') ?: null;
        $sort = $request->query('sort');

        $medicines = Medicine::with('category')
            ->search($search)
            ->inCategory($categoryId)
            ->sortBy(is_string($sort) ? $sort : null)
            ->paginate(9)
            ->withQueryString();

        return view('catalog.index', [
            'medicines' => $medicines,
            'categories' => Category::withCount('medicines')->orderBy('name')->get(),
            'search' => $search,
            'categoryId' => $categoryId,
            'sort' => is_string($sort) ? $sort : null,
        ]);
    }

    public function show(Medicine $medicine): View
    {
        $medicine->load('category');

        $related = Medicine::with('category')
            ->where('category_id', $medicine->category_id)
            ->whereKeyNot($medicine->id)
            ->latest('id')
            ->take(4)
            ->get();

        return view('catalog.show', compact('medicine', 'related'));
    }
}

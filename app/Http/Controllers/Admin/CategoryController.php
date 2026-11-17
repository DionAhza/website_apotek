<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('medicines')->orderBy('name')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()->route('admin.categories.index')->with('sukses', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->route('admin.categories.index')->with('sukses', 'Kategori berhasil diperbarui.');
    }

    /**
     * Kategori yang masih dipakai obat TIDAK dihapus, dan obatnya tidak ikut terhapus.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $total = $category->medicines()->count();

        if ($total > 0) {
            return back()->with('gagal', "Kategori \"{$category->name}\" masih dipakai oleh {$total} obat. Pindahkan atau hapus obatnya terlebih dahulu.");
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('sukses', 'Kategori berhasil dihapus.');
    }
}

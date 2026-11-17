<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicineRequest;
use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicineController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->trim()->toString();
        $categoryId = $request->integer('category') ?: null;

        $medicines = Medicine::with('category')
            ->search($search)
            ->inCategory($categoryId)
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.medicines.index', [
            'medicines' => $medicines,
            'categories' => Category::orderBy('name')->get(),
            'search' => $search,
            'categoryId' => $categoryId,
        ]);
    }

    public function create(): View
    {
        return view('admin.medicines.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(MedicineRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('medicine_image');

        if ($request->hasFile('medicine_image')) {
            $data['medicine_image'] = $request->file('medicine_image')
                ->store(Medicine::IMAGE_DIR, 'public');
        }

        Medicine::create($data);

        return redirect()->route('admin.medicines.index')->with('sukses', 'Obat berhasil ditambahkan.');
    }

    public function edit(Medicine $medicine): View
    {
        return view('admin.medicines.edit', [
            'medicine' => $medicine,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(MedicineRequest $request, Medicine $medicine): RedirectResponse
    {
        $data = $request->safe()->except('medicine_image');
        $oldImage = $medicine->medicine_image;

        // Gambar lama dipertahankan jika tidak ada gambar baru yang diunggah.
        if ($request->hasFile('medicine_image')) {
            $data['medicine_image'] = $request->file('medicine_image')
                ->store(Medicine::IMAGE_DIR, 'public');
        }

        $medicine->update($data);

        // File lama baru dihapus setelah file baru dan data berhasil tersimpan.
        if ($request->hasFile('medicine_image')) {
            Medicine::deleteStoredFile($oldImage);
        }

        return redirect()->route('admin.medicines.index')->with('sukses', 'Data obat berhasil diperbarui.');
    }

    public function destroy(Medicine $medicine): RedirectResponse
    {
        $medicine->delete();
        Medicine::deleteStoredFile($medicine->medicine_image);

        return redirect()->route('admin.medicines.index')->with('sukses', 'Obat berhasil dihapus.');
    }
}

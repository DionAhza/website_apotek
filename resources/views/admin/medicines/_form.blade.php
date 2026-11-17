@php($editing = isset($medicine))

<form action="{{ $editing ? route('admin.medicines.update', $medicine) : route('admin.medicines.store') }}" method="POST" enctype="multipart/form-data" class="card space-y-5 p-6" novalidate>
    @csrf
    @if ($editing) @method('PUT') @endif

    <div>
        <label for="name" class="label">Nama obat</label>
        <input id="name" type="text" name="name" value="{{ old('name', $medicine->name ?? '') }}" required class="input @error('name') input-invalid @enderror" @error('name') aria-invalid="true" @enderror>
        @error('name') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="category_id" class="label">Kategori</label>
        <select id="category_id" name="category_id" required class="input @error('category_id') input-invalid @enderror" @error('category_id') aria-invalid="true" @enderror>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $medicine->category_id ?? 0) === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="field-error">{{ $message }}</p> @enderror
        @if ($categories->isEmpty())
            <p class="field-hint">Belum ada kategori. <a href="{{ route('admin.categories.create') }}" class="font-semibold text-emerald-700 hover:underline">Buat kategori dulu</a>.</p>
        @endif
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="price" class="label">Harga (Rp)</label>
            <input id="price" type="number" name="price" min="0" step="1" value="{{ old('price', $medicine->price ?? '') }}" required class="input @error('price') input-invalid @enderror" @error('price') aria-invalid="true" @enderror>
            @error('price') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="expired" class="label">Tanggal kedaluwarsa</label>
            <input id="expired" type="date" name="expired" value="{{ old('expired', isset($medicine) ? $medicine->expired->toDateString() : '') }}" required class="input @error('expired') input-invalid @enderror" @error('expired') aria-invalid="true" @enderror>
            @error('expired') <p class="field-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="description" class="label">Deskripsi</label>
        <textarea id="description" name="description" rows="5" required class="input @error('description') input-invalid @enderror" @error('description') aria-invalid="true" @enderror>{{ old('description', $medicine->description ?? '') }}</textarea>
        <p class="field-hint">Isi hanya informasi yang sudah diverifikasi pengelola apotek.</p>
        @error('description') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="medicine_image" class="label">Gambar obat {{ $editing ? '(opsional)' : '' }}</label>
        <div class="flex items-start gap-4">
            <div>
                @if ($editing && $medicine->image_url)
                    <img id="image-preview" src="{{ $medicine->image_url }}" alt="Gambar saat ini: {{ $medicine->name }}" class="size-24 rounded-xl border border-slate-200 object-cover">
                @else
                    <img id="image-preview" src="" alt="Pratinjau gambar" class="hidden size-24 rounded-xl border border-slate-200 object-cover" data-placeholder="image-placeholder">
                    <div id="image-placeholder" class="grid size-24 place-content-center rounded-xl border border-dashed border-slate-300 bg-slate-50 text-xs text-slate-400">Belum ada</div>
                @endif
            </div>
            <div class="flex-1">
                <input id="medicine_image" type="file" name="medicine_image" accept="image/jpeg,image/png,image/webp" data-image-input="image-preview"
                       class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="field-hint">JPG, PNG, atau WEBP. Maksimal 2 MB.{{ $editing ? ' Biarkan kosong untuk mempertahankan gambar saat ini.' : '' }}</p>
                @error('medicine_image') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
        <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">{{ $editing ? 'Simpan perubahan' : 'Simpan obat' }}</button>
    </div>
</form>

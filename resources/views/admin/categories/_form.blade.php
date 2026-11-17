@php($editing = isset($category))

<form action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" class="card space-y-5 p-6" novalidate>
    @csrf
    @if ($editing) @method('PUT') @endif

    <div>
        <label for="name" class="label">Nama kategori</label>
        <input id="name" type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="input @error('name') input-invalid @enderror" @error('name') aria-invalid="true" @enderror>
        @error('name') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="label">Deskripsi (opsional)</label>
        <textarea id="description" name="description" rows="3" class="input @error('description') input-invalid @enderror">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">{{ $editing ? 'Simpan perubahan' : 'Simpan kategori' }}</button>
    </div>
</form>

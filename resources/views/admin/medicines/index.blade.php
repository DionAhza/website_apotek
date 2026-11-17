@extends('layouts.admin')

@section('title', 'Data Obat')
@section('heading', 'Data Obat')
@section('subheading', 'Kelola seluruh obat yang tampil di katalog.')

@section('actions')
    <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">+ Tambah Obat</a>
@endsection

@section('content')
    <form action="{{ route('admin.medicines.index') }}" method="GET" class="card mb-6 grid gap-4 p-4 sm:grid-cols-[2fr_1fr_auto]" role="search">
        <div>
            <label for="q" class="label">Cari nama obat</label>
            <input id="q" type="search" name="q" value="{{ $search }}" class="input" placeholder="Ketik nama obat...">
        </div>
        <div>
            <label for="category" class="label">Kategori</label>
            <select id="category" name="category" class="input">
                <option value="">Semua</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn btn-primary">Cari</button>
            @if ($search !== '' || $categoryId)
                <a href="{{ route('admin.medicines.index') }}" class="btn btn-ghost">Reset</a>
            @endif
        </div>
    </form>

    @if ($medicines->isEmpty())
        <x-empty-state title="Belum ada data obat" text="Tambahkan obat pertama atau ubah kata kunci pencarian.">
            <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">+ Tambah Obat</a>
        </x-empty-state>
    @else
        <div class="card overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Obat</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Kedaluwarsa</th>
                        <th scope="col" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicines as $medicine)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <x-medicine-image :medicine="$medicine" class="size-12 shrink-0 rounded-lg" />
                                    <a href="{{ route('catalog.show', $medicine) }}" class="font-medium text-slate-900 hover:text-emerald-700">{{ $medicine->name }}</a>
                                </div>
                            </td>
                            <td><span class="badge badge-info">{{ $medicine->category->name }}</span></td>
                            <td class="whitespace-nowrap font-medium">{{ $medicine->formatted_price }}</td>
                            <td class="whitespace-nowrap">
                                <div>{{ $medicine->expired->translatedFormat('d M Y') }}</div>
                                <div class="mt-1"><x-expiry-badge :medicine="$medicine" /></div>
                            </td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.medicines.edit', $medicine) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('admin.medicines.destroy', $medicine) }}" method="POST" data-confirm="Obat &quot;{{ $medicine->name }}&quot; akan dihapus permanen.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $medicines->links() }}</div>
    @endif
@endsection

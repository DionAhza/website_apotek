@extends('layouts.admin')

@section('title', 'Kategori')
@section('heading', 'Kategori Obat')
@section('subheading', 'Kelompokkan obat agar mudah dicari pelanggan.')

@section('actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
@endsection

@section('content')
    @if ($categories->isEmpty())
        <x-empty-state title="Belum ada kategori" text="Buat kategori terlebih dahulu sebelum menambahkan obat.">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
        </x-empty-state>
    @else
        <div class="card overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Jumlah obat</th>
                        <th scope="col" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td class="font-medium text-slate-900">{{ $category->name }}</td>
                            <td class="max-w-sm text-slate-500">{{ $category->description ?: '—' }}</td>
                            <td><span class="badge badge-neutral">{{ $category->medicines_count }} obat</span></td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" data-confirm="Kategori &quot;{{ $category->name }}&quot; akan dihapus.">
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
        <div class="mt-6">{{ $categories->links() }}</div>
    @endif
@endsection

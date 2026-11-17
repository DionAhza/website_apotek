@extends('layouts.admin')

@section('title', 'Edit '.$medicine->name)
@section('heading', 'Edit Obat')
@section('subheading', $medicine->name)

@section('content')
    <div class="max-w-3xl">
        @include('admin.medicines._form')
    </div>
@endsection

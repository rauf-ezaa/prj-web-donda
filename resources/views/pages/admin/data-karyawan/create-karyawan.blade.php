@extends('layouts.app')
@section('content')
    <x-common.page-breadcrumb pageTitle="Create Data Karyawan"
        parentTitle="Data Karyawan"
        :parentRoute="route('data-pengguna.index')" />

				@if(session('error'))
						<x-ui.alert variant="error" title="Gagal" :message="session('error')" linkHref="/" />
						<br>
				@endif

    @if($errors->any())
        <x-ui.alert variant="error" title="Gagal" message="Periksa kembali input yang dimasukkan." linkHref="/" />
        <br>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('data-pengguna.store') }}" method="POST">
            @csrf
            @include('pages.admin.data-karyawan.form')
        </form>
    </div>
@endsection

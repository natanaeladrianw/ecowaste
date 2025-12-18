@extends('layouts.app')

@section('title', 'Statistik Bulanan')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold mb-6">Statistik Bulanan</h1>

                <div class="mb-6">
                    <label for="month" class="block text-sm font-medium text-gray-700">Pilih Bulan</label>
                    <input type="month" id="month" name="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-green-100 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-700">Total Sampah Bulan Ini</h3>
                        <p class="text-3xl font-bold text-green-600">0 kg</p>
                    </div>
                    <div class="bg-blue-100 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-700">Poin Bulan Ini</h3>
                        <p class="text-3xl font-bold text-blue-600">0</p>
                    </div>
                    <div class="bg-yellow-100 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-700">Transaksi Bulan Ini</h3>
                        <p class="text-3xl font-bold text-yellow-600">0</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-4">Grafik Bulanan</h3>
                    <p class="text-gray-600">Grafik akan ditampilkan di sini</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


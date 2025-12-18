@extends('layouts.app')

@section('title', 'Artikel Edukasi')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold mb-6">Artikel Edukasi</h1>

                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                        <h3 class="text-xl font-semibold mb-2">Cara Mengelola Sampah dengan Baik</h3>
                        <p class="text-gray-600 mb-4">Pelajari cara-cara praktis untuk mengelola sampah rumah tangga dengan baik dan benar.</p>
                        <a href="#" class="text-green-600 hover:text-green-800 font-semibold">Baca selengkapnya →</a>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                        <h3 class="text-xl font-semibold mb-2">Manfaat Daur Ulang Sampah</h3>
                        <p class="text-gray-600 mb-4">Ketahui manfaat yang bisa didapat dari proses daur ulang sampah untuk lingkungan.</p>
                        <a href="#" class="text-green-600 hover:text-green-800 font-semibold">Baca selengkapnya →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Pencapaian')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold mb-6">Pencapaian Saya</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-100 border border-gray-300 rounded-lg p-6 text-center">
                        <div class="text-4xl mb-4">🏆</div>
                        <h3 class="text-xl font-semibold mb-2">Pemula</h3>
                        <p class="text-gray-600 mb-4">Kumpulkan 10 kg sampah</p>
                        <div class="bg-gray-200 rounded-full h-2 mb-2">
                            <div class="bg-gray-400 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        <p class="text-sm text-gray-500">Belum tercapai</p>
                    </div>

                    <div class="bg-gray-100 border border-gray-300 rounded-lg p-6 text-center">
                        <div class="text-4xl mb-4">⭐</div>
                        <h3 class="text-xl font-semibold mb-2">Pecinta Lingkungan</h3>
                        <p class="text-gray-600 mb-4">Kumpulkan 50 kg sampah</p>
                        <div class="bg-gray-200 rounded-full h-2 mb-2">
                            <div class="bg-gray-400 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        <p class="text-sm text-gray-500">Belum tercapai</p>
                    </div>

                    <div class="bg-gray-100 border border-gray-300 rounded-lg p-6 text-center">
                        <div class="text-4xl mb-4">🌱</div>
                        <h3 class="text-xl font-semibold mb-2">Eco Warrior</h3>
                        <p class="text-gray-600 mb-4">Kumpulkan 100 kg sampah</p>
                        <div class="bg-gray-200 rounded-full h-2 mb-2">
                            <div class="bg-gray-400 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        <p class="text-sm text-gray-500">Belum tercapai</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


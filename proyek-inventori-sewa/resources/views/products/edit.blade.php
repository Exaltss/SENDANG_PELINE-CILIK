<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-200">
            Edit Produk: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-700">
                    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Nama Produk --}}
                        <div>
                            <x-input-label for="name" value="Kode / Nama Produk" class="text-gray-300"/>
                            <x-text-input id="name" class="block w-full mt-1 text-gray-200 bg-gray-700 border-gray-600" type="text" name="name" :value="old('name', $product->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <x-input-label for="category" value="Kategori" class="text-gray-300"/>
                            <select name="category" id="category" class="block w-full mt-1 text-gray-200 bg-gray-700 border-gray-600 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500" required>
                                <option value="Baju Premium" @selected(old('category', $product->category) == 'Baju Premium')>Baju Premium</option>
                                <option value="Baju Original" @selected(old('category', $product->category) == 'Baju Original')>Baju Original</option>
                                <option value="Aksesoris" @selected(old('category', $product->category) == 'Aksesoris')>Aksesoris</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        {{-- Stok --}}
                        <div>
                            <x-input-label for="stock" value="Stok" class="text-gray-300"/>
                            <x-text-input id="stock" class="block w-full mt-1 text-gray-200 bg-gray-700 border-gray-600" type="number" name="stock" :value="old('stock', $product->stock)" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>

                        {{-- Gambar --}}
                        <div>
                            <x-input-label for="image" value="Ganti Gambar (Opsional)" class="text-gray-300"/>
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-24 h-24 my-2 rounded-md">
                            <input id="image" type="file" name="image" class="block w-full mt-1 text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-yellow-500 file:text-black hover:file:bg-yellow-400">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end pt-4 space-x-4">
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-white">Batal</a>
                            <x-primary-button class="text-black bg-yellow-500 hover:bg-yellow-600">
                                Update Produk
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

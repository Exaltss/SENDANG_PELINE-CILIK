<x-app-layout>
    {{-- Slot untuk styling kustom dan script --}}
    <x-slot name="header">
        <style>
            :root {
                --bg-dark: #1a1a1a;
                --text-gold: #D4AF37;
                --text-light: #f5f5f5;
                --bg-soft: #2d2d2d;
                --bg-darker: #242424;
            }
            .font-serif { font-family: 'Playfair Display', serif; }
            .btn-primary {
                background-color: var(--text-gold); color: var(--bg-dark);
                transition: all 0.3s ease; border: 2px solid transparent;
            }
            .btn-primary:hover {
                background-color: transparent; color: var(--text-gold);
                border-color: var(--text-gold);
            }
            .tab-active {
                border-bottom: 2px solid var(--text-gold); color: var(--text-gold);
                font-weight: 600;
            }
            .tab-inactive {
                border-bottom: 2px solid transparent; color: #a1a1aa;
            }
        </style>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    </x-slot>

    <div class="text-white bg-gray-900">
        {{-- Hero Section --}}
        <section class="relative h-[60vh] bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1529139574466-a303027c1d8b?q=80&w=1974&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-black bg-opacity-60"></div>
            <div class="container relative z-10 flex flex-col items-center justify-center h-full px-5 mx-auto">
                <h1 class="font-serif text-6xl font-bold leading-tight text-center text-white lg:text-8xl">
                    AMIRA COLECTIONS
                </h1>
            </div>
        </section>

        {{-- Koleksi Kami (diadaptasi untuk admin) --}}
        <section id="koleksi" class="py-16" x-data="{ tab: 'premium' }" style="background-color: var(--bg-soft);">
            <div class="container px-5 mx-auto">
                {{-- Judul dan Tombol Aksi --}}
                <div class="flex flex-col items-center justify-between gap-6 mb-12 text-center md:flex-row md:text-left">
                    <h2 class="font-serif text-4xl font-bold" style="color: var(--text-light);">Pratinjau Koleksi</h2>
                    <div class="flex flex-col items-center gap-4 md:flex-row">
                        {{-- Form Pencarian --}}
                        <form action="{{ route('dashboard') }}" method="GET" class="w-full md:w-auto">
                            <div class="relative">
                                <input type="text" name="search" placeholder="Cari nama produk..." value="{{ $search ?? '' }}" class="w-full py-2 pl-4 pr-10 text-black border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent sm:text-sm">
                                <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-4">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                                </button>
                            </div>
                        </form>
                        {{-- Tombol Tambah --}}
                        <a href="{{ route('products.create') }}" class="inline-block w-full px-8 py-3 text-base font-bold text-center rounded-md md:w-auto btn-primary shrink-0">+ Tambah Produk Baru</a>
                    </div>
                </div>

                {{-- Navigasi Tab --}}
                <div class="flex justify-center mb-10 border-b border-gray-700">
                    <button @click="tab = 'premium'" :class="tab === 'premium' ? 'tab-active' : 'tab-inactive'" class="px-8 py-3 text-lg transition">Baju Premium</button>
                    <button @click="tab = 'original'" :class="tab === 'original' ? 'tab-active' : 'tab-inactive'" class="px-8 py-3 text-lg transition">Baju Original</button>
                    <button @click="tab = 'aksesoris'" :class="tab === 'aksesoris' ? 'tab-active' : 'tab-inactive'" class="px-8 py-3 text-lg transition">Aksesoris</button>
                </div>

                <div>
                    {{-- Tab Baju Premium --}}
                    <div x-show="tab === 'premium'" class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                        @forelse ($premiumProducts as $product)
                            <div class="overflow-hidden transition-all duration-300 transform border border-gray-800 rounded-lg group hover:shadow-2xl hover:shadow-yellow-500/20 hover:-translate-y-2" style="background-color: var(--bg-darker);">
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full transition-transform duration-300 transform h-96 group-hover:scale-105">
                                <div class="p-5 text-center">
                                    <h3 class="mb-2 text-xl font-bold transition text-light group-hover:text-gold-400">{{ $product->name }}</h3>
                                    <div class="flex justify-center space-x-4">
                                        <a href="{{ route('products.edit', $product) }}" class="text-sm font-medium text-yellow-400 transition hover:text-yellow-300">Edit</a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-500 transition hover:text-red-400">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="col-span-4 text-center text-gray-400">Belum ada produk Baju Premium.</p>
                        @endforelse
                    </div>

                    {{-- Tab Baju Original --}}
                    <div x-show="tab === 'original'" class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4" style="display: none;">
                        @forelse ($originalProducts as $product)
                             <div class="overflow-hidden transition-all duration-300 transform border border-gray-800 rounded-lg group hover:shadow-2xl hover:shadow-yellow-500/20 hover:-translate-y-2" style="background-color: var(--bg-darker);">
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full transition-transform duration-300 transform h-96 group-hover:scale-105">
                                <div class="p-5 text-center">
                                    <h3 class="mb-2 text-xl font-bold transition text-light group-hover:text-gold-400">{{ $product->name }}</h3>
                                    <div class="flex justify-center space-x-4">
                                        <a href="{{ route('products.edit', $product) }}" class="text-sm font-medium text-yellow-400 transition hover:text-yellow-300">Edit</a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-500 transition hover:text-red-400">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="col-span-4 text-center text-gray-400">Belum ada produk Baju Original.</p>
                        @endforelse
                    </div>

                    {{-- Tab Aksesoris --}}
                    <div x-show="tab === 'aksesoris'" class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4" style="display: none;">
                        @forelse ($accessoryProducts as $product)
                             <div class="overflow-hidden transition-all duration-300 transform border border-gray-800 rounded-lg group hover:shadow-2xl hover:shadow-yellow-500/20 hover:-translate-y-2" style="background-color: var(--bg-darker);">
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full transition-transform duration-300 transform h-96 group-hover:scale-105">
                                <div class="p-5 text-center">
                                    <h3 class="mb-2 text-xl font-bold transition text-light group-hover:text-gold-400">{{ $product->name }}</h3>
                                    <div class="flex justify-center space-x-4">
                                        <a href="{{ route('products.edit', $product) }}" class="text-sm font-medium text-yellow-400 transition hover:text-yellow-300">Edit</a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-500 transition hover:text-red-400">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="col-span-4 text-center text-gray-400">Belum ada produk Aksesoris.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>

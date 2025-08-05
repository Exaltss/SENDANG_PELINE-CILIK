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
        <section class="relative h-[60vh] bg-cover bg-center" style="background-image: url('https://placehold.co/1920x1080/000000/FFFFFF?text=Selamat+Datang');">
            <div class="absolute inset-0 bg-black bg-opacity-60"></div>
            <div class="container relative z-10 flex flex-col justify-center h-full px-5 mx-auto">
                <div class="max-w-xl">
                    <h1 class="mb-4 font-serif text-5xl font-bold leading-tight lg:text-7xl">
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h1>
                    <p class="mb-6 text-lg text-gray-300 lg:text-xl">Kelola inventori, catat penyewaan, dan lihat riwayat dengan mudah.</p>
                    <a href="{{ route('products.create') }}" class="inline-block px-10 py-3 mt-4 text-lg font-bold rounded-md btn-primary">
                        + Tambah Produk Baru
                    </a>
                </div>
            </div>
        </section>

        {{-- Koleksi Kami (diadaptasi untuk admin) --}}
        <section id="koleksi" class="py-16" x-data="{ tab: 'premium' }" style="background-color: var(--bg-soft);">
            <div class="container px-5 mx-auto text-center">
                <h2 class="mb-12 font-serif text-4xl font-bold" style="color: var(--text-light);">Pratinjau Koleksi</h2>
                <div class="flex justify-center mb-10 border-b border-gray-700">
                    <button @click="tab = 'premium'" :class="tab === 'premium' ? 'tab-active' : 'tab-inactive'" class="px-8 py-3 text-lg transition">Baju Premium</button>
                    <button @click="tab = 'original'" :class="tab === 'original' ? 'tab-active' : 'tab-inactive'" class="px-8 py-3 text-lg transition">Baju Original</button>
                    <button @click="tab = 'aksesoris'" :class="tab === 'aksesoris' ? 'tab-active' : 'tab-inactive'" class="px-8 py-3 text-lg transition">Aksesoris</button>
                </div>
                <div>
                    {{-- Tab Baju Premium --}}
                    <div x-show="tab === 'premium'" class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                        @forelse ($premiumProducts as $product)
                            <a href="{{ route('products.show', $product) }}" class="block group">
                                <div class="overflow-hidden transition-all duration-300 transform border border-gray-800 rounded-lg hover:shadow-2xl hover:shadow-yellow-500/20 hover:-translate-y-2" style="background-color: var(--bg-darker);">
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full transition-transform duration-300 transform h-96 group-hover:scale-105">
                                    <div class="p-5 text-center">
                                        <h3 class="mb-1 text-xl font-bold transition text-light group-hover:text-gold-400">{{ $product->name }}</h3>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p class="col-span-4 text-gray-400">Belum ada produk Baju Premium.</p>
                        @endforelse
                    </div>

                    {{-- Tab Baju Original --}}
                    <div x-show="tab === 'original'" class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4" style="display: none;">
                        @forelse ($originalProducts as $product)
                            <a href="{{ route('products.show', $product) }}" class="block group">
                                <div class="overflow-hidden transition-all duration-300 transform border border-gray-800 rounded-lg hover:shadow-2xl hover:shadow-yellow-500/20 hover:-translate-y-2" style="background-color: var(--bg-darker);">
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full transition-transform duration-300 transform h-96 group-hover:scale-105">
                                    <div class="p-5 text-center">
                                        <h3 class="mb-1 text-xl font-bold transition text-light group-hover:text-gold-400">{{ $product->name }}</h3>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p class="col-span-4 text-gray-400">Belum ada produk Baju Original.</p>
                        @endforelse
                    </div>

                    {{-- Tab Aksesoris --}}
                    <div x-show="tab === 'aksesoris'" class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4" style="display: none;">
                        @forelse ($accessoryProducts as $product)
                            <a href="{{ route('products.show', $product) }}" class="block group">
                                <div class="overflow-hidden transition-all duration-300 transform border border-gray-800 rounded-lg hover:shadow-2xl hover:shadow-yellow-500/20 hover:-translate-y-2" style="background-color: var(--bg-darker);">
                                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full transition-transform duration-300 transform h-96 group-hover:scale-105">
                                    <div class="p-5 text-center">
                                        <h3 class="mb-1 text-xl font-bold transition text-light group-hover:text-gold-400">{{ $product->name }}</h3>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p class="col-span-4 text-gray-400">Belum ada produk Aksesoris.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>

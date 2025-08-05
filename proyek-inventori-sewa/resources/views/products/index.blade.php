<x-app-layout>
    {{-- Slot untuk styling kustom --}}
    <x-slot name="header">
        <style>
            .btn-gold {
                background-color: #D4AF37; /* Warna Emas */
                color: #1a1a1a; /* Warna Teks Gelap */
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }
            .btn-gold:hover {
                background-color: transparent;
                color: #D4AF37;
                border-color: #D4AF37;
            }
            .card-product {
                background-color: #2d2d2d;
                transition: all 0.3s ease;
            }
            .card-product:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(212, 175, 55, 0.15);
            }
            .card-product-title {
                color: #f5f5f5;
                transition: color 0.3s ease;
            }
            .card-product:hover .card-product-title {
                color: #D4AF37;
            }
        </style>
        <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
            <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                {{ $category }}
            </h2>

            {{-- Form Pencarian dengan gaya baru --}}
            <form action="{{ url()->current() }}" method="GET" class="w-full sm:w-auto sm:max-w-xs">
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari nama produk..." value="{{ request('search') }}" class="w-full py-2 pl-4 pr-10 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent sm:text-sm">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </form>

            <a href="{{ route('products.create') }}" class="inline-flex items-center px-6 py-2 text-xs font-bold tracking-wider uppercase rounded-full btn-gold shrink-0">
                + Tambah Produk
            </a>
        </div>
    </x-slot>

    {{-- Latar belakang halaman disamakan dengan tema dashboard --}}
    <div class="py-12 bg-gray-900">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="relative px-4 py-3 mb-6 text-green-300 bg-green-900 bg-opacity-50 border border-green-500 rounded-lg" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @forelse ($products as $product)
                    <div class="flex flex-col overflow-hidden rounded-lg shadow-lg card-product">
                        <a href="{{ route('products.show', $product) }}" class="block">
                            <div class="aspect-w-3 aspect-h-4">
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full h-full">
                            </div>
                        </a>
                        <div class="flex flex-col flex-grow p-5 text-center">
                            <h3 class="flex-grow mb-2 text-xl font-bold card-product-title">{{ $product->name }}</h3>
                            <p class="mb-4 text-base text-gray-400">
                                Stok: {{ $product->stock }}
                            </p>
                            <div class="flex justify-center mt-auto space-x-4">
                                <a href="{{ route('products.edit', $product) }}" class="text-sm font-medium text-yellow-400 transition hover:text-yellow-300">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-500 transition hover:text-red-400">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-800 rounded-lg shadow-lg col-span-full">
                         <div class="p-10 text-center text-gray-400">
                            @if (request('search'))
                                Produk dengan nama "{{ request('search') }}" tidak ditemukan.
                            @else
                                Belum ada produk di kategori ini.
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Link Pagination dengan gaya baru --}}
            <div class="mt-10">
                {{ $products->withQueryString()->links() }}
            </div>

        </div>
    </div>
</x-app-layout>

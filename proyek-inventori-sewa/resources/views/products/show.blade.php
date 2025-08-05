<x-app-layout>
    {{-- Import CSS dan JS untuk Flatpickr Calendar --}}
    <x-slot name="header">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Produk: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-10">
                    {{-- Kolom Kiri: Gambar Produk --}}
                    <div>
                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover rounded-lg shadow-md">
                    </div>

                    {{-- Kolom Kanan: Info dan Form Penyewaan --}}
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h3>
                        <p class="mt-2 text-sm text-gray-500">Kategori: {{ $product->category }}</p>
                        <p class="mt-4 text-lg font-semibold text-gray-700">
                            Stok Tersedia: <span class="text-blue-600">{{ $product->stock }}</span>
                        </p>

                        <div class="mt-6 border-t pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Formulir Penyewaan</h4>
                            <form action="{{ route('rentals.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                {{-- Jumlah Stok --}}
                                <div>
                                    <x-input-label for="quantity" value="Jumlah Sewa" />
                                    <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" :value="old('quantity', 1)" min="1" max="{{ $product->stock }}" required />
                                </div>

                                {{-- Pemilihan Tanggal --}}
                                <div class="mt-4">
                                    <x-input-label for="rent_date" value="Pilih Tanggal Sewa" />
                                    <x-text-input id="rent_date_picker" class="block mt-1 w-full" type="text" name="rent_date" required placeholder="Pilih tanggal..." />
                                </div>

                                <div class="flex items-center justify-end mt-6">
                                    <x-primary-button>
                                        {{ __('Simpan Penyewaan') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#rent_date_picker", {
                dateFormat: "Y-m-d",
                // Menonaktifkan tanggal yang sudah dibooking
                disable: @json($bookedDates),
                // Menonaktifkan semua tanggal di masa lalu
                minDate: "today",
                altInput: true,
                altFormat: "j F Y",
            });
        });
    </script>
    @endpush
</x-app-layout>

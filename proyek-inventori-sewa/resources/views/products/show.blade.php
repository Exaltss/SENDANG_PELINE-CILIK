<x-app-layout>
    {{-- Slot untuk styling kustom dan script pustaka eksternal --}}
    <x-slot name="header">
        {{-- Impor CSS dan JS untuk Flatpickr Calendar --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        {{-- Styling tambahan untuk kalender --}}
        <style>
            .flatpickr-day.booked {
                background: #ef4444; /* Merah */
                color: #ffffff;
                border-color: #ef4444;
                font-weight: bold;
            }
        </style>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses atau Error --}}
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-300 bg-green-800 bg-opacity-50 border border-green-600 rounded-lg" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-300 bg-red-800 bg-opacity-50 border border-red-600 rounded-lg" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="overflow-hidden bg-gray-800 shadow-xl sm:rounded-lg">
                <div class="grid grid-cols-1 p-6 md:grid-cols-2 lg:grid-cols-3 gap-x-10 gap-y-8">

                    {{-- Kolom Kiri: Gambar Produk --}}
                    <div class="lg:col-span-2">
                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full h-auto rounded-lg shadow-lg aspect-square">
                    </div>

                    {{-- Kolom Kanan: Info dan Form Penyewaan --}}
                    <div class="flex flex-col text-gray-200">
                        <h1 class="mb-2 text-4xl font-bold tracking-tight uppercase">{{ $product->name }}</h1>
                        <p class="text-sm text-gray-400">Kategori: {{ $product->category }}</p>

                        <div class="flex items-center my-4">
                            <p class="px-3 py-1 text-sm font-semibold text-blue-200 bg-blue-800 rounded-full">
                                Stok: {{ $product->stock }}
                            </p>
                        </div>

                        {{-- Form Penyewaan --}}
                        <div class="p-6 mt-4 border border-gray-700 rounded-lg bg-gray-900_">
                            <h2 class="mb-4 text-xl font-semibold">Formulir Penyewaan</h2>
                            <form action="{{ route('rentals.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                {{-- Jumlah Sewa --}}
                                <div>
                                    <x-input-label for="quantity" value="Jumlah Sewa" />
                                    <x-text-input id="quantity" class="block w-full mt-1 bg-gray-700 border-gray-600" type="number" name="quantity" :value="old('quantity', 1)" min="1" max="{{ $product->stock }}" required />
                                </div>

                                {{-- Pemilihan Tanggal --}}
                                <div class="mt-4">
                                    <x-input-label for="rent_date_picker" value="Pilih Tanggal Sewa" />
                                    <x-text-input id="rent_date_picker" class="block w-full mt-1 bg-gray-700 border-gray-600" type="text" name="rent_date" required placeholder="Pilih tanggal..." />
                                </div>

                                <div class="mt-6">
                                    <button type="submit" class="justify-center w-full px-4 py-3 text-sm font-bold text-black uppercase transition-all duration-300 bg-yellow-400 border border-transparent rounded-md shadow-sm hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 focus:ring-offset-gray-800">
                                        Simpan Penyewaan
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Kalender Ketersediaan --}}
                        <div class="mt-8">
                            <h3 class="mb-4 text-xl font-semibold">Kalender Ketersediaan</h3>
                            <div id="availability-calendar" class="p-4 rounded-lg bg-gray-900_"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil data tanggal yang sudah disewa dari PHP
            const bookedDates = @json($bookedDates);

            // Inisialisasi Flatpickr untuk input form
            flatpickr("#rent_date_picker", {
                dateFormat: "Y-m-d",
                disable: bookedDates, // Nonaktifkan tanggal yang sudah disewa
                minDate: "today",     // Nonaktifkan tanggal di masa lalu
                altInput: true,
                altFormat: "j F Y",
            });

            // Inisialisasi Flatpickr untuk kalender ketersediaan
            flatpickr("#availability-calendar", {
                inline: true, // Tampilkan kalender secara langsung
                dateFormat: "Y-m-d",
                minDate: "today",
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // Cek jika tanggal ada di dalam array bookedDates
                    if (bookedDates.indexOf(dayElem.dateObj.toISOString().slice(0, 10)) > -1) {
                        dayElem.classList.add("booked");
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>

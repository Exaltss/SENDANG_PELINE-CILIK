<x-app-layout>
    {{-- Slot untuk styling kustom dan script pustaka eksternal --}}
    <x-slot name="header">
        {{-- Impor CSS dan JS untuk Flatpickr Calendar --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <style>
            .flatpickr-day.booked {
                background: #ef4444; color: #ffffff; border-color: #ef4444;
                font-weight: bold; cursor: pointer;
            }
            .flatpickr-day.booked:hover { background: #dc2626; }
        </style>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-gray-800 shadow-xl sm:rounded-lg">
                <div class="grid grid-cols-1 gap-12 p-6 lg:grid-cols-2 lg:gap-16">

                    {{-- KOLOM KIRI: Gambar Produk --}}
                    <div class="w-full">
                        <div class="lg:sticky lg:top-8">
                             <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full rounded-lg shadow-lg aspect-square">
                        </div>
                    </div>

                    {{-- KOLOM KANAN: Semua Informasi dan Aksi --}}
                    <div class="flex flex-col space-y-8 text-gray-200">
                        {{-- Notifikasi --}}
                        <div>
                            @if (session('success'))
                                <div class="p-4 mb-6 text-sm text-green-300 bg-green-800 bg-opacity-50 border border-green-600 rounded-lg" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any() || session('error'))
                                 <div class="p-4 mb-6 text-sm text-red-300 bg-red-800 bg-opacity-50 border border-red-600 rounded-lg" role="alert">
                                    {{ session('error') ?? $errors->first() }}
                                </div>
                            @endif
                        </div>

                        {{-- Info Produk --}}
                        <div>
                            <p class="text-sm tracking-widest text-gray-400 uppercase">{{ $product->category }}</p>
                            <h1 class="mt-1 text-4xl font-bold tracking-tight text-white lg:text-5xl">{{ $product->name }}</h1>
                            <p class="inline-block px-3 py-1 mt-4 text-sm font-semibold text-blue-200 bg-blue-800 rounded-full">
                                Stok: {{ $product->stock }}
                            </p>
                        </div>

                        {{-- Form Penyewaan --}}
                        <div class="p-6 border border-gray-700 rounded-lg bg-gray-900/50">
                            <h2 class="mb-4 text-xl font-semibold">Formulir Penyewaan</h2>
                            <form action="{{ route('rentals.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div>
                                    <x-input-label for="renter_code" value="Kode Halaman" />
                                    <x-text-input id="renter_code" class="block w-full mt-1 bg-gray-700 border-gray-600" type="text" name="renter_code" :value="old('renter_code')" required />
                                </div>
                                <div>
                                    <x-input-label for="quantity" value="Jumlah Sewa" />
                                    <x-text-input id="quantity" class="block w-full mt-1 bg-gray-700 border-gray-600" type="number" name="quantity" :value="old('quantity', 1)" min="1" max="{{ $product->stock }}" required />
                                </div>
                                <div>
                                    <x-input-label for="rent_date_picker" value="Pilih Tanggal Sewa" />
                                    <x-text-input id="rent_date_picker" class="block w-full mt-1 bg-gray-700 border-gray-600" type="text" name="rent_date" required placeholder="Pilih tanggal..." />
                                </div>
                                <div class="pt-2">
                                    <button type="submit" class="justify-center w-full px-4 py-3 text-sm font-bold text-black uppercase transition-all duration-300 bg-yellow-400 border border-transparent rounded-md shadow-sm hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 focus:ring-offset-gray-800">
                                        Simpan Penyewaan
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Riwayat Penyewaan --}}
                        <div>
                             <h3 class="mb-4 text-xl font-semibold">Riwayat Penyewaan</h3>
                             <div class="pr-2 space-y-3 overflow-y-auto max-h-72">
                                @forelse ($product->rentals->sortByDesc('rent_date') as $rental)
                                    <div class="p-3 bg-gray-700 border border-gray-600 rounded-lg">
                                        <p class="font-semibold text-yellow-400">Kode: <span class="font-normal text-white">{{ $rental->renter_code }}</span></p>
                                        <p class="text-sm font-semibold text-yellow-400">Tanggal: <span class="font-normal text-white">{{ $rental->rent_date->format('j F Y') }}</span></p>
                                        <p class="text-sm font-semibold text-yellow-400">Jumlah: <span class="font-normal text-white">{{ $rental->quantity }}</span></p>
                                    </div>
                                @empty
                                    <div class="p-3 text-center text-gray-400 border border-gray-700 border-dashed rounded-lg">
                                        <p>Belum ada riwayat penyewaan.</p>
                                    </div>
                                @endforelse
                             </div>
                        </div>

                        {{-- Kalender Ketersediaan (dengan Alpine.js) --}}
                        <div x-data="{ open: false }">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold">Kalender Ketersediaan</h3>
                                <button @click="open = !open" class="px-3 py-1 text-xs font-semibold text-gray-300 bg-gray-700 rounded-full hover:bg-gray-600">
                                    <span x-show="!open">Tampilkan</span>
                                    <span x-show="open">Sembunyikan</span>
                                </button>
                            </div>
                            <div x-show="open" x-transition class="p-2 rounded-lg bg-gray-900/50">
                                <div id="availability-calendar"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rentalDetails = @json($rentalDetailsByDate ?? []);
            flatpickr("#rent_date_picker", {
                dateFormat: "Y-m-d",
                disable: Object.keys(rentalDetails),
                minDate: "today",
            });
            flatpickr("#availability-calendar", {
                inline: true,
                dateFormat: "Y-m-d",
                minDate: "today",
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    const dateStr = dayElem.dateObj.toISOString().slice(0, 10);
                    if (rentalDetails[dateStr]) {
                        dayElem.classList.add("booked");
                        dayElem.addEventListener('click', function() {
                            const details = rentalDetails[dateStr];
                            alert(
                                'Detail Penyewaan:\n\n' +
                                'Kode: ' + details.renter_code + '\n' +
                                'Tanggal: ' + details.rent_date + '\n' +
                                'Jumlah: ' + details.quantity
                            );
                        });
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>

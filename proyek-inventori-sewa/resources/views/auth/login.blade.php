<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - {{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        {{-- Menambahkan Alpine.js untuk interaktivitas --}}
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="antialiased">
        {{-- Container utama dengan latar belakang gambar dan form di tengah --}}
        <div class="flex flex-col items-center justify-center min-h-screen p-4 bg-center bg-cover" style="background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop')">

            {{-- Box Login --}}
            <div class="w-full max-w-sm p-8 space-y-6 bg-black bg-opacity-75 shadow-2xl rounded-xl">

                <!-- Judul Form -->
                <div class="text-center">
                    <h1 class="text-3xl font-bold tracking-wider text-white">
                        ADMIN LOGIN
                    </h1>
                    <p class="mt-2 text-sm text-gray-400">Sistem Inventori Penyewaan</p>
                </div>

                <!-- Session Status (diganti dari komponen Blade) -->
                @if (session('status'))
                    <div class="mb-4 text-sm font-medium text-green-400">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="text-xs font-bold text-gray-300 uppercase">Username</label>
                        {{-- Menggunakan input HTML standar --}}
                        <input id="email" class="block w-full mt-1 text-gray-900 bg-gray-200 border-transparent rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                        {{-- Menggunakan direktif @error --}}
                        @error('email')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div x-data="{ showPassword: false }">
                        <label for="password" class="text-xs font-bold text-gray-300 uppercase">Password</label>
                        <div class="relative">
                             {{-- Menggunakan input HTML standar --}}
                            <input id="password"
                                          class="block w-full pr-10 mt-1 text-gray-900 bg-gray-200 border-transparent rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                                          :type="showPassword ? 'text' : 'password'"
                                          name="password"
                                          required autocomplete="current-password" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <button type="button" @click="showPassword = !showPassword" class="text-gray-500 focus:outline-none">
                                    {{-- Ikon Mata Terbuka --}}
                                    <svg x-show="!showPassword" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{-- Ikon Mata Tertutup --}}
                                    <svg x-show="showPassword" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                         {{-- Menggunakan direktif @error --}}
                        @error('password')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lupa Password & Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="text-yellow-500 bg-gray-800 border-gray-700 rounded shadow-sm focus:ring-yellow-600" name="remember">
                            <span class="text-sm text-gray-400 ms-2">{{ __('Ingat saya') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-gray-400 underline rounded-md hover:text-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <!-- Tombol Login -->
                    <div class="pt-4">
                        <button type="submit" class="justify-center w-full px-4 py-3 text-sm font-bold text-black uppercase transition-all duration-300 bg-yellow-400 border border-transparent rounded-md shadow-sm hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Login
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </body>
</html>

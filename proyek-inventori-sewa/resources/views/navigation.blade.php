<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('products.premium')" :active="request()->routeIs('products.premium')">
        {{ __('Baju Premium') }}
    </x-nav-link>
    <x-nav-link :href="route('products.original')" :active="request()->routeIs('products.original')">
        {{ __('Baju Original') }}
    </x-nav-link>
    <x-nav-link :href="route('products.accessories')" :active="request()->routeIs('products.accessories')">
        {{ __('Aksesoris') }}
    </x-nav-link>
</div>

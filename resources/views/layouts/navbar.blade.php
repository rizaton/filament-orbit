<nav class="bg-white dark:bg-gray-800 antialiased">
    <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0 py-4">
        <div class="flex items-center justify-between">

            <div class="flex items-center space-x-8">
                <div class="shrink-0">
                    <a href="/" title="" class="">
                        <img src="/assets/images/logo.svg" alt="Logo" class="h-8 sm:h-10">
                    </a>
                </div>

                <ul class="hidden lg:flex items-center justify-start gap-6 md:gap-8 py-3 sm:justify-center">
                    <x-navbar-link href="/" :active="request()->is('/') ? 'page' : false">Beranda</x-navbar-link>
                    <x-navbar-link href="/items" :active="request()->is('/items') ? 'page' : false">Alat</x-navbar-link>
                    <x-navbar-link href="/contact" :active="request()->is('/contact') ? 'page' : false">Kontak</x-navbar-link>
                </ul>
            </div>

            <div class="flex items-center lg:space-x-2">
                @if (!request()->is('cart'))
                    <button id="myCartDropdownButton1" data-dropdown-toggle="myCartDropdown1" type="button"
                        class="inline-flex items-center rounded-lg justify-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-medium leading-none text-gray-900 dark:text-white">
                        <span class="sr-only">
                            Keranjang
                        </span>
                        <svg class="w-5 h-5 lg:me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312" />
                        </svg>
                        <span class="hidden sm:flex">Keranjang</span>
                        <svg class="hidden sm:flex w-4 h-4 text-gray-900 dark:text-white ms-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="myCartDropdown1" x-data="{
                        cart: [],
                        loadCart() {
                            this.cart = JSON.parse(localStorage.getItem('cart') || '[]');
                        },
                        removeItem(idx) {
                            this.cart.splice(idx, 1);
                            localStorage.setItem('cart', JSON.stringify(this.cart));
                        }
                    }" x-init="document.getElementById('myCartDropdownButton1').addEventListener('click', () => {
                        loadCart();
                    });"
                        class="hidden z-10 mx-auto max-w-sm space-y-4 overflow-hidden rounded-lg bg-white p-4 antialiased shadow-lg dark:bg-gray-800">
                        {{-- Product Lists cart --}}
                        <template x-if="cart.length === 0">
                            <div class="text-gray-500 dark:text-gray-400 text-sm mt-2">Keranjang kamu kosong</div>
                        </template>
                        <template x-for="(item, idx) in cart" :key="item[0]">
                            <div class="grid grid-cols-2 mb-2">
                                <div>
                                    <span
                                        class="text-clip text-sm font-semibold leading-none text-gray-900 dark:text-white hover:underline"
                                        x-text="item[2]"></span>
                                </div>
                                <div class="flex items-center justify-end gap-6">
                                    <p class="text-sm font-normal leading-none text-gray-500 dark:text-gray-400">Qty:
                                        <span x-text="item[1]"></span>
                                    </p>
                                    <button type="button" @click="removeItem(idx)"
                                        class="text-red-600 hover:text-red-700 dark:text-red-500 dark:hover:text-red-600">
                                        <span class="sr-only"> Remove </span>
                                        <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12a10 10 0 1 1 20 0 10 10 0 0 1-20 0Zm7.7-3.7a1 1 0 0 0-1.4 1.4l2.3 2.3-2.3 2.3a1 1 0 1 0 1.4 1.4l2.3-2.3 2.3 2.3a1 1 0 0 0 1.4-1.4L13.4 12l2.3-2.3a1 1 0 0 0-1.4-1.4L12 10.6 9.7 8.3Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        {{-- Button --}}
                        <a href="/cart" title="" x-show="cart.length > 0"
                            class="mt-2 mb-2 me-2 inline-flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                            role="button">
                            Lihat Keranjang
                        </a>
                        <a href="/items" title="" x-show="cart.length === 0"
                            class="mt-2 mb-2 me-2 inline-flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                            role="button">
                            Sewa alat
                        </a>
                    </div>
                @endif

                <button type="button" data-collapse-toggle="ecommerce-navbar-menu-1"
                    aria-controls="ecommerce-navbar-menu-1" aria-expanded="false"
                    class="inline-flex lg:hidden items-center justify-center hover:bg-gray-100 rounded-md dark:hover:bg-gray-700 p-2 text-gray-900 dark:text-white">
                    <span class="sr-only">
                        Open Menu
                    </span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                            d="M5 7h14M5 12h14M5 17h14" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="ecommerce-navbar-menu-1"
            class="bg-gray-50 dark:bg-gray-700 dark:border-gray-600 border border-gray-200 rounded-lg py-3 hidden px-4 mt-4">
            <ul class="text-gray-900 dark:text-white text-sm font-medium space-y-3">
                <x-navbar-link href="/" :active="request()->is('/') ? 'page' : false">Beranda</x-navbar-link>
                <x-navbar-link href="/items" :active="request()->is('/items') ? 'page' : false">Alat</x-navbar-link>
                <x-navbar-link href="/contact" :active="request()->is('/contact') ? 'page' : false">Kontak</x-navbar-link>
            </ul>
        </div>
    </div>
</nav>

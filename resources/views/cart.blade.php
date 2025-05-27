<x-guest-layout>
    <section class="py-8 antialiased dark:bg-gray-900 md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Shopping Cart</h2>

            <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8" x-data="{
                cart: [],
                total: 0,
                loadCart() {
                    this.cart = JSON.parse(localStorage.getItem('cart') || '[]');
                },
                saveCart() {
                    localStorage.setItem('cart', JSON.stringify(this.cart));
                },
                increment(idx) {
                    let item = this.cart[idx];
                    if (item[1] < item[4]) {
                        item[1]++;
                        item[3] = item[1] * item[5];
                        this.saveCart();
                    }
                },
                decrement(idx) {
                    let item = this.cart[idx];
                    if (item[1] > 1) {
                        item[1]--;
                        item[3] = item[1] * item[5];
                        this.saveCart();
                    } else if (item[1] === 1) {
                        this.cart.splice(idx, 1);
                        this.saveCart();
                    }
                },
                remove(idx) {
                    this.cart.splice(idx, 1);
                    this.saveCart();
                },
                total() {
                    return this.cart.reduce((sum, item) => sum + (item[3] || 0), 0);
                }
            }"
                x-init="loadCart();
                window.addEventListener('storage', () => loadCart())">
                <template x-if="cart.length === 0">
                    <div class="w-fit mx-auto flex items-center justify-center flex-col">
                        <div class="text-gray-500 dark:text-gray-400 text-2xl text-center font-medium">
                            Keranjang kamu kosong nih
                        </div>
                        <a href="/items" title="Continue Shopping"
                            class="my-2 px-5 rounded-lg border border-gray-200 bg-white py-2.5 flex flex-row justify-center items-center gap-2 text-sm font-medium text-primary-700 hover:underline dark:text-primary-500">
                            Cari alat dulu yuk
                        </a>
                    </div>
                </template>
                <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl" x-show="cart.length > 0">
                    <template x-for="(item, idx) in cart" :key="item[0]">
                        <div class="space-y-6">
                            <div
                                class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6 mb-4">
                                <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                    <a href="#" class="shrink-0 md:order-1">
                                        <img class="h-20 w-20 dark:hidden"
                                            src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg"
                                            alt="imac image" />
                                        <img class="hidden h-20 w-20 dark:block"
                                            src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front-dark.svg"
                                            alt="imac image" />
                                    </a>
                                    <div class="flex items-center justify-between md:order-3 md:justify-end">
                                        <div class="flex items-center">
                                            <button type="button" x-data
                                                @click="
                                                    if (cart[idx][1] != 1) {
                                                        cart[idx][1]--;
                                                        cart[idx][3] = cart[idx][1] * cart[idx][5];
                                                        saveCart();
                                                        cart = [...cart];
                                                    } else if (cart[idx][1] === 1) {
                                                        cart.splice(idx, 1);
                                                        saveCart();
                                                        cart = [...cart];
                                                    }
                                                    total();
                                                "
                                                :disabled="item[1] <= 1"
                                                class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                                <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 18 2">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                                </svg>
                                            </button>
                                            <input type="text" id="quantity"
                                                class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0 dark:text-white"
                                                :value="item[1]" readonly />
                                            <button type="button"
                                                @click="
                                                    if (cart[idx][1] < cart[idx][4]) {
                                                        cart[idx][1]++;
                                                        cart[idx][3] = cart[idx][1] * cart[idx][5];
                                                        saveCart();
                                                        total();
                                                        cart = [...cart];
                                                    }
                                                "
                                                :disabled="item[1] >= item[4]"
                                                class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                                <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 18 18">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="text-end md:order-4 md:w-32">
                                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                                Rp.<span x-text="Number(item[5]).toLocaleString()"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                                        <a href="#"
                                            class="text-base font-medium text-gray-900 hover:underline dark:text-white"
                                            x-text="item[2]"></a>
                                        <div class="flex items-center gap-4">
                                            <button type="button"
                                                @click="
                                                    cart.splice(idx, 1);
                                                    saveCart();
                                                    cart = [...cart];
                                                "
                                                class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
                                                <svg class="me-1.5 h-5 w-5" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18 17.94 6M18 18 6.06 6" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full" x-show="cart.length > 0">
                    <div
                        class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">Ringkasan pemesanan</p>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <template x-for="item in cart" :key="item[0]">
                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400"
                                            x-text="item[2]">
                                        </dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white">
                                            Rp.<span x-text="Number(item[3]).toLocaleString()"></span>
                                        </dd>
                                    </dl>
                                </template>
                            </div>
                            <dl
                                class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
                                <dd class="text-base font-bold text-gray-900 dark:text-white">
                                    Rp.<span x-text="Number(total()).toLocaleString()"></span>
                                </dd>
                            </dl>
                        </div>
                        <a href="/checkout"
                            class="flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Proses Sewa
                        </a>
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> atau </span>
                            <a href="/items" title="Continue Shopping"
                                class="inline-flex items-center gap-2 text-sm font-medium text-primary-700 underline hover:no-underline dark:text-primary-500">
                                Lanjut cari alat
                                <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>

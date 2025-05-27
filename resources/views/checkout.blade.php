<x-guest-layout>
    <div x-data="{
        cart: [],
        rentInfo: {
            rentDate: '',
            returnDate: '',
            address: '',
            firstName: '',
            lastName: '',
            phone: ''
        },
        rentInfoFilled: false,
    
        openModal: false,
        termsOpenModal: false,
        rulesOpenModal: false,
    
        termsChecked: false,
        rulesChecked: false,
        errorMsg: '',
        init() {
            this.loadCart();
            this.loadRentInfo();
        },
        loadCart() {
            this.cart = JSON.parse(localStorage.getItem('cart') || '[]');
        },
        total() {
            return this.cart.reduce((sum, item) => sum + (item[3] || 0), 0);
        },
        saveTerms() {
            this.termsChecked = true;
            this.termsOpenModal = false;
            this.errorMsg = '';
        },
        saveRules() {
            this.rulesChecked = true;
            this.rulesOpenModal = false;
            this.errorMsg = '';
        },
        loadRentInfo() {
            const info = JSON.parse(localStorage.getItem('rentInfo') || '{}');
            if (info && info.address && info.firstName && info.lastName && info.phone && info.rentDate && info.returnDate) {
                this.rentInfo = info;
                this.rentInfoFilled = true;
            } else {
                this.rentInfoFilled = false;
            }
        },
        saveRentInfo() {
            if (
                this.rentInfo.address &&
                this.rentInfo.firstName &&
                this.rentInfo.lastName &&
                this.rentInfo.phone &&
                $refs.rentStart.value &&
                $refs.rentEnd.value
            ) {
                this.rentInfo.rentDate = $refs.rentStart.value;
                this.rentInfo.returnDate = $refs.rentEnd.value;
                localStorage.setItem('rentInfo', JSON.stringify(this.rentInfo));
                this.rentInfoFilled = true;
                this.openModal = false;
                this.errorMsg = '';
                console.log(this.rentInfo);
            } else {
                console.log(this.rentInfo);
            }
        },
    }" x-init="init()">
        <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
            <form method="POST" action="{{ route('checkout.create') }}" class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                @csrf
                <input type="hidden" name="firstName" x-model="rentInfo.firstName">
                <input type="hidden" name="lastName" x-model="rentInfo.lastName">
                <input type="hidden" name="address" x-model="rentInfo.address">
                <input type="hidden" name="phone" x-model="rentInfo.phone">
                <input type="hidden" name="rentDate" x-model="rentInfo.rentDate">
                <input type="hidden" name="returnDate" x-model="rentInfo.returnDate">

                <div class="mx-auto max-w-3xl">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                        Sewa
                    </h2>

                    <div class="mt-6 space-y-4 border-b border-t border-gray-200 py-8 dark:border-gray-700 sm:mt-8">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Informasi Penyewaan
                        </h4>
                        <template x-if="rentInfoFilled">
                            <dl>
                                <dt class="text-base font-medium text-gray-900 dark:text-white">
                                    Kontak Informasi
                                </dt>
                                <dd class="mt-1 text-base font-normal text-gray-500 dark:text-gray-400"
                                    x-text="`${rentInfo.firstName} ${rentInfo.lastName} - +62${rentInfo.phone}, ${rentInfo.address}`">
                                </dd>
                            </dl>
                        </template>
                        <template x-if="!rentInfoFilled">
                            <div class="text-base text-red-500">
                                Mohon untuk mengisi informasi penyewaan Anda.
                            </div>
                        </template>
                        <button type="button" @click="openModal = ! openModal"
                            class="text-base font-medium text-primary-700 hover:underline dark:text-primary-500">
                            Ubah
                        </button>
                    </div>
                    @if ($errors->any())
                        <div class="mt-6 text-sm text-red-600 dark:text-red-500 font-medium">
                            {{ __('Mohon cek ulang informasi sewa.') }}
                        </div>
                    @endif
                    <div class="mt-6 sm:mt-8">
                        <div class="relative overflow-x-auto border-b border-gray-200 dark:border-gray-800">
                            <table class="w-full text-left font-medium text-gray-900 dark:text-white md:table-fixed">
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                    <template x-for="item in cart" :key="item[0]">
                                        <tr>
                                            <td class="whitespace-nowrap py-4 md:w-[384px]">
                                                <div class="flex items-center gap-4">
                                                    <div class="flex items-center aspect-square w-10 h-10 shrink-0">
                                                        <img class="h-auto w-full max-h-full dark:hidden"
                                                            src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg"
                                                            alt="item image" />
                                                        <img class="hidden h-auto w-full max-h-full dark:block"
                                                            src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front-dark.svg"
                                                            alt="item image" />
                                                    </div>
                                                    <span x-text="item[2]" class="hover:underline"></span>
                                                </div>
                                            </td>
                                            <td class="p-4 text-base font-normal text-gray-900 dark:text-white"
                                                x-text="`x${item[1]}`"></td>
                                            <td class="p-4 text-right text-base font-bold text-gray-900 dark:text-white"
                                                x-text="`Rp.${Number(item[3]).toLocaleString()}`"></td>
                                        </tr>
                                    </template>
                                    <template x-for="(item, index) in cart" :key="item[0]">
                                        <div>
                                            <input type="hidden" :name="`cart[${index}][slug]`" :value="item[0]">
                                            <input type="hidden" :name="`cart[${index}][qty]`" :value="item[1]">
                                            <input type="hidden" :name="`cart[${index}][name]`" :value="item[2]">
                                            <input type="hidden" :name="`cart[${index}][price]`"
                                                :value="item[3]">
                                        </div>
                                    </template>
                                    <template x-if="cart.length === 0">
                                        <tr>
                                            <td colspan="3"
                                                class="py-4 text-center text-gray-500 dark:text-gray-400">
                                                Keranjang kosong</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 space-y-6">
                            <dl
                                class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                <dt class="text-lg font-bold text-gray-900 dark:text-white">Total</dt>
                                <dd class="text-lg font-bold text-gray-900 dark:text-white"
                                    x-text="`Rp.${Number(total()).toLocaleString()}`"></dd>
                            </dl>

                            <div class="flex items-start sm:items-center">
                                <input type="hidden" name="terms" x-model="termsChecked">
                                <input id="terms-checkbox-2" type="checkbox" x-model="termsChecked" name="terms"
                                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary-600 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                                <label for="terms-checkbox-2"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"> Saya setuju
                                    dengan
                                    <button type="button" @click="termsOpenModal = ! termsOpenModal"
                                        class="text-primary-700 underline hover:no-underline dark:text-primary-500">
                                        Syarat dan Ketentuan Penyewaan
                                    </button> dari Orbit Outdoor
                                </label>
                            </div>
                            @error('terms')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500 font-medium">
                                    {{ $message }}
                                </p>
                            @enderror
                            <div class="flex items-start sm:items-center">
                                <input type="hidden" name="rules" x-model="rulesChecked">
                                <input id="terms-checkbox-3" type="checkbox" x-model="rulesChecked" name="rules"
                                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary-600 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                                <label for="terms-checkbox-3"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"> Saya setuju
                                    dengan
                                    <button type="button" @click="rulesOpenModal = ! rulesOpenModal"
                                        class="text-primary-700 underline hover:no-underline dark:text-primary-500">
                                        Tata tertib penyewaan
                                    </button> dari Orbit Outdoor
                                </label>
                            </div>
                            @error('rules')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500 font-medium">
                                    {{ $message }}
                                </p>
                            @enderror

                            <template x-if="errorMsg">
                                <div class="text-red-500 text-sm" x-text="errorMsg"></div>
                            </template>
                            <div class="gap-4 sm:flex sm:items-center">
                                <a href="/cart"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 text-center block">
                                    Kembali ke daftar keranjang
                                </a>
                                <button type="submit"
                                    class="mt-4 flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 sm:mt-0">
                                    Sewa sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
        <div tabindex="-1" x-show="openModal" x-transition:enter="transition ease-out duration-300"
            class="antialiased fixed left-0 right-0 top-0 z-50 h-[calc(100%-1rem)] max-h-auto w-full max-h-full flex items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0">
            <div class="relative max-h-auto w-full max-h-full max-w-lg p-4">
                <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">
                    <div
                        class="flex items-center justify-between rounded-t border-b border-gray-200 p-4 dark:border-gray-700 md:p-5">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Informasi Sewa
                        </h3>
                        <button type="button" x-on:click="openModal = false"
                            class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d=" m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">
                                Close modal
                            </span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form class="p-4 md:p-5" @submit.prevent="saveRentInfo">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-5">
                            <div class="sm:col-span-2">

                                <div id="date-range-picker" date-rangepicker class="flex items-center">
                                    <div class="flex flex-col">
                                        <label for="datepicker-range-start"
                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                            Tanggal awal pinjam*
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                </svg>
                                            </div>
                                            <input id="datepicker-range-start" name="start" type="text"
                                                x-ref="rentStart"
                                                x-model="rentInfo.returnDate = $refs.rentStart.value"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Pilih tanggal awal">
                                        </div>
                                    </div>
                                    <span class="mx-4 mt-4 text-gray-500">
                                        Sampai
                                    </span>
                                    <div class="flex flex-col">
                                        <label for="datepicker-range-end"
                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                            Tanggal selesai pinjam*
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                </svg>
                                            </div>
                                            <input id="datepicker-range-end" name="end" type="text"
                                                x-ref="rentEnd" x-model="rentInfo.returnDate = $refs.rentEnd.value"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Pilih tanggal akhir">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="sm:col-span-2">
                                <label for="address_billing_modal"
                                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                    Alamat*
                                </label>
                                <input type="text" id="address_billing_modal" x-model="rentInfo.address"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                    placeholder="Masukkan alamat lengkap anda" required />
                            </div>
                            <div>
                                <label for="first_name_billing_modal"
                                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                    Nama depan*
                                </label>
                                <input type="text" id="first_name_billing_modal" x-model="rentInfo.firstName"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                    placeholder="Masukkan nama depan" required />
                            </div>
                            <div>
                                <label for="last_name_billing_modal"
                                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                    Nama belakang*
                                </label>
                                <input type="text" id="last_name_billing_modal" x-model="rentInfo.lastName"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                    placeholder="Masukkan nama belakang" required />
                            </div>
                            <div class="sm:col-span-2">
                                <label for="phone-input_billing_modal"
                                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                    Nomor Telefon*
                                </label>
                                <div class="flex items-center">
                                    <button id="dropdown_phone_input__button_billing_modal"
                                        class="z-10 inline-flex shrink-0 items-center rounded-s-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-center text-sm font-medium text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        type="button" disabled>
                                        <svg viewBox="0 0 20 15" class="me-2 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <rect width="20" height="7.5" fill="#D02F44" rx="2" />
                                            <rect y="7.5" width="20" height="7.5" fill="#FFFFFF" />
                                        </svg>
                                        +62
                                    </button>
                                    <div class="relative w-full">
                                        <input type="text" id="phone-input_billing_modal" x-model="rentInfo.phone"
                                            class="z-20 block w-full rounded-e-lg border border-s-0 border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:border-s-gray-700 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500"
                                            placeholder="812-3456-7890" pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}"
                                            required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-4 dark:border-gray-700 md:pt-5">
                            <button type="submit"
                                class="me-2 inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                Simpan Informasi
                            </button>
                            <button type="button" x-on:click="openModal = false"
                                class="me-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div tabindex="-1" x-show="termsOpenModal" x-transition:enter="transition ease-out duration-300"
            class="antialiased fixed left-0 right-0 top-0 z-50 h-[calc(100%-1rem)] max-h-auto w-full max-h-full flex items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0">
            <div class="relative max-h-auto w-full max-h-full max-w-lg lg:max-w-4xl p-4">
                <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">
                    <div
                        class="flex items-center justify-between rounded-t border-b border-gray-200 p-4 dark:border-gray-700 md:p-5">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Syarat dan Ketentuan
                        </h3>
                        <button type="button" x-on:click="termsOpenModal = false"
                            class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d=" m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">
                                Close modal
                            </span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form class="p-4 md:p-5" @submit.prevent="saveTerms">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-5">
                            <div class="sm:col-span-2">
                                <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                                    Syarat dan Ketentuan Penyewaan di Orbit Outdoor:
                                </h2>
                                <ul
                                    class="max-w-md sm:max-w-3xl space-y-1 text-gray-700 list-disc list-inside dark:text-gray-400">
                                    @foreach ($terms as $key => $term)
                                        <li class="text-justify">
                                            {{ $term->description }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <template x-if="!termsChecked">
                            <div class="border-t border-gray-200 pt-4 dark:border-gray-700 md:pt-5">
                                <button type="submit"
                                    class="me-2 inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                    Saya menyetujui syarat dan ketentuan
                                </button>
                                <button type="button" x-on:click="termsOpenModal = false"
                                    class="me-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                                    Tutup
                                </button>
                            </div>
                        </template>
                    </form>
                </div>
            </div>
        </div>
        <div tabindex="-1" x-show="rulesOpenModal" x-transition:enter="transition ease-out duration-300"
            class="antialiased fixed left-0 right-0 top-0 z-50 h-[calc(100%-1rem)] max-h-auto w-full max-h-full flex items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0">
            <div class="relative max-h-auto w-full max-h-full max-w-lg lg:max-w-2xl p-4">
                <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">
                    <div
                        class="flex items-center justify-between rounded-t border-b border-gray-200 p-4 dark:border-gray-700 md:p-5">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Tata tertib
                        </h3>
                        <button type="button" x-on:click="rulesOpenModal = false"
                            class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d=" m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">
                                Close modal
                            </span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form class="p-4 md:p-5" @submit.prevent="saveRules">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-5">
                            <div class="sm:col-span-2">
                                <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                                    Tata tertib Penyewaan di Orbit Outdoor:
                                </h2>
                                <ul
                                    class="max-w-md sm:max-w-xl space-y-1 text-gray-700 list-disc list-inside dark:text-gray-400">
                                    @foreach ($terms as $key => $term)
                                        <li>
                                            {{ $term->description }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <template x-if="!rulesChecked">
                            <div class="border-t border-gray-200 pt-4 dark:border-gray-700 md:pt-5">
                                <button type="submit"
                                    class="me-2 inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                    Saya menyetujui Tata tertib penyewaan
                                </button>
                                <button type="button" x-on:click="rulesOpenModal = false"
                                    class="me-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                                    Tutup
                                </button>
                            </div>
                        </template>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

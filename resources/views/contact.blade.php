<x-guest-layout>
    <section class="dark:bg-gray-900">
        <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-center text-gray-900 dark:text-white">Kontak Kami
            </h2>
            <p class="mb-8 lg:mb-16 font-light text-center text-gray-500 dark:text-gray-400 sm:text-xl">
                Ada masalah teknis? Ingin mengirim umpan balik tentang fitur beta? Butuh detail tentang penyewaan kami?
                Beri tahu kami.
            </p>
            @if (session('status') === 'message-sent')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-500">
                    {{ __('Pesan Terkirim!') }}
                </p>
            @endif
            <form method="POST" action="{{ route('contact.create') }}" class="space-y-8">
                @csrf
                <div>
                    <label for="name"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nama</label>
                    <input type="text" id="name" name="name"
                        class="@error('name') is-invalid @enderror block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light"
                        placeholder="Ahmad Kusumah" required>
                    @error('name')
                        <div class="mt-2 text-sm text-red-600 dark:text-red-500 font-medium">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                        Email</label>
                    <input type="email" id="email" name="email"
                        class="@error('email') is-invalid @enderror shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light"
                        placeholder="ahmadkusumah@example.com" required>
                    @error('email')
                        <div class="mt-2 text-sm text-red-600 dark:text-red-500 font-medium">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="subject"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Subjek</label>
                    <input type="text" id="subject" name="subject"
                        class="@error('subject') is-invalid @enderror block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light"
                        placeholder="Beritahu kami bagaimana kami bisa membantu" required>
                    @error('subject')
                        <div class="mt-2 text-sm text-red-600 dark:text-red-500 font-medium">{{ $message }}</div>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
                        Isi pesan
                    </label>
                    <textarea id="message" rows="6" name="message"
                        class="@error('message') is-invalid @enderror block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg shadow-sm border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Tinggalkan komentar..."></textarea>
                    @error('message')
                        <div class="mt-2 text-sm text-red-600 dark:text-red-500 font-medium">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit"
                    class="py-3 px-5 text-sm font-medium text-center text-white rounded-lg bg-primary-700 sm:w-fit hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Kirim pesan
                </button>
            </form>
        </div>
    </section>
</x-guest-layout>

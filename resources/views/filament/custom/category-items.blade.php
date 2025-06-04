<div class="space-y-4">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Detail Kategori</h2>
        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-600 dark:text-gray-100">
            <div>
                <dt class="font-medium text-gray-700 dark:text-gray-200">Nama Kategori</dt>
                <dd class="font-medium text-gray-800 dark:text-gray-100">{{ $name }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-700 dark:text-gray-200">Slug</dt>
                <dd class="font-medium text-gray-800 dark:text-gray-100">{{ $slug }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-700 dark:text-gray-200">Jumlah Alat</dt>
                <dd class="font-medium text-gray-800 dark:text-gray-100">{{ $items->count() }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-700 dark:text-gray-200">Warna Kategori</dt>
                <dd>
                    <span class="inline-flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full" style="background-color: {{ $color }}"></span>
                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $color }}</span>
                    </span>
                </dd>
            </div>
        </dl>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Daftar Item</h2>
        <table class="w-full text-sm text-left table-auto">
            <thead class="text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="py-2">ID Alat</th>
                    <th class="py-2">Nama Alat</th>
                    <th class="py-2">Stok</th>
                    <th class="py-2">Dibuat</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-200">
                @forelse ($items as $item)
                    <tr
                        class="border-b border-gray-100 dark:border-gray-800 dark:hover:text-gray-600 dark:hover:bg-gray-800 hover:bg-gray-50 transition">
                        <td class="py-2">{{ $item->id }}</td>
                        <td class="py-2">{{ $item->name }}</td>
                        <td class="py-2">{{ $item->stock }}</td>
                        <td class="py-2">{{ $item->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada item dalam kategori ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

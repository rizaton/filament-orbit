<div class="space-y-4">
    <div class="flex items-center space-x-4">
        @if ($record->image)
            <img src="{{ $record->image }}" alt="imageg{{ $record->name }}"
                class="w-24 h-24 rounded-md object-cover shadow" />
        @else
            <div class="w-24 h-24 flex items-center justify-center bg-gray-200 text-gray-500 text-sm rounded-md">
                Tidak ada gambar
            </div>
        @endif

        <div class="space-y-1">
            <h2 class="text-lg font-semibold">{{ $record->name }}</h2>
            <p class="text-sm text-gray-600">Slug: <span class="font-mono">{{ $record->slug }}</span></p>
            <p class="text-sm text-gray-600">Kategori: {{ $record->category->name ?? 'Tidak ada' }}</p>
            <p class="text-sm text-gray-600">Stok: {{ $record->stock }}</p>
            <p class="text-sm text-gray-600">Tersedia: {{ $record->is_available ? 'Ya' : 'Tidak' }}</p>
            <p class="text-sm text-gray-600">Harga Sewa: Rp{{ number_format($record->rent_price, 0, ',', '.') }}</p>
        </div>
    </div>

    <div>
        <h3 class="font-medium text-sm text-gray-700">Deskripsi</h3>
        <p class="text-sm text-gray-800">{{ $record->description ?? 'Tidak ada deskripsi.' }}</p>
    </div>

    <div class="text-sm text-gray-500">
        <p>Dibuat: {{ $record->created_at->format('d M Y H:i') }}</p>
        <p>Terakhir diubah: {{ $record->updated_at->format('d M Y H:i') }}</p>
    </div>
</div>

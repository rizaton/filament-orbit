<div class="space-y-4 text-sm text-gray-800">
    <div>
        <h2 class="text-base font-semibold">Informasi Penyewaan</h2>
        <p>
            <span class="font-medium text-gray-600">
                ID Sewa:
            </span>
            {{ $record->rental_id }}
        </p>
        <p>
            <span class="font-medium text-gray-600">
                Nama Penyewa
                :</span>
            {{ $record->rental->name ?? '-' }}
        </p>
        <p>
            <span class="font-medium text-gray-600">
                Tanggal Dibuat:
            </span>
            {{ $record->rental->created_at->format('d M Y H:i') }}
        </p>
        <p>
            <span class="font-medium text-gray-600">
                Tanggal Diubah:
            </span>
            {{ $record->rental->updated_at->format('d M Y H:i') }}
        </p>
    </div>

    <div>
        <h2 class="text-base font-semibold">Informasi Alat</h2>
        <p><span class="font-medium text-gray-600">ID Barang:</span> {{ $record->item_id }}</p>
        <p><span class="font-medium text-gray-600">Nama Alat:</span> {{ $record->item->name ?? '-' }}</p>
        <p><span class="font-medium text-gray-600">Jumlah:</span> {{ $record->quantity }}</p>
        <p><span class="font-medium text-gray-600">Sub Total:</span>
            Rp{{ number_format($record->sub_total, 0, ',', '.') }}</p>
    </div>

    <div>
        <h2 class="text-base font-semibold">Status Pengembalian</h2>
        <p>
            <span
                class="inline-flex items-center px-2 py-1 rounded text-white text-xs {{ $record->is_returned ? 'bg-green-600' : 'bg-red-600' }}">
                {{ $record->is_returned ? 'Sudah Dikembalikan' : 'Belum Dikembalikan' }}
            </span>
        </p>
    </div>
</div>

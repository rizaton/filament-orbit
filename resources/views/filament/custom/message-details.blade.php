<div class="space-y-4 text-sm text-gray-800">
    <div>
        <h2 class="text-base font-semibold">Informasi Pengirim</h2>
        <p><span class="font-medium text-gray-600">Nama:</span> {{ $record->name }}</p>
        <p><span class="font-medium text-gray-600">Email:</span> {{ $record->email }}</p>
    </div>

    <div>
        <h2 class="text-base font-semibold">Subjek</h2>
        <p>{{ $record->subject }}</p>
    </div>

    <div>
        <h2 class="text-base font-semibold">Isi Pesan</h2>
        <div class="p-3 border rounded bg-gray-50 text-gray-700 whitespace-pre-wrap">
            {{ $record->message }}
        </div>
    </div>

    <div class="text-xs text-gray-500">
        <p>Dibuat: {{ $record->created_at->format('d M Y H:i') }}</p>
        <p>Diubah: {{ $record->updated_at->format('d M Y H:i') }}</p>
    </div>
</div>

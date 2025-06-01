<div class="space-y-4 p-4">
    <div class="bg-white dark:bg-gray-900 shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Detail Peminjaman</h2>

        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700 dark:text-gray-300">
            <div>
                <dt class="font-medium">ID Sewa</dt>
                <dd>{{ $rental->id }}</dd>
            </div>
            <div>
                <dt class="font-medium">Nama Penyewa</dt>
                <dd>{{ $rental->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-medium">Tanggal Pinjam</dt>
                <dd>{{ $rental->rent_date?->format('d M Y') ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-medium">Tanggal Kembali</dt>
                <dd>{{ $rental->return_date?->format('d M Y') ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-medium">Terlambat (Jika Ada)</dt>
                <dd>{{ $rental->late_date?->format('d M Y') ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-medium">Status</dt>
                <dd>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'approved' => 'bg-blue-100 text-blue-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'returned' => 'bg-green-100 text-green-800',
                            'late' => 'bg-red-100 text-red-800',
                        ];
                        $statusColor = $statusColors[$rental->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp

                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusColor }}">
                        {{ ucfirst($rental->status) }}
                    </span>
                </dd>
            </div>
        </dl>
    </div>

    <div class="bg-white dark:bg-gray-900 shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Detail Alat yang Dipinjam</h2>

        <table class="w-full text-sm text-left table-auto">
            <thead class="text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="py-2">ID Alat</th>
                    <th class="py-2">Nama Alat</th>
                    <th class="py-2 text-center">Jumlah</th>
                    <th class="py-2 text-center">Sudah Dikembalikan</th>
                    <th class="py-2 text-right">Sub Total</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-200">
                @forelse ($details as $detail)
                    <tr
                        class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <td class="py-2">{{ $detail->item_id }}</td>
                        <td class="py-2">{{ $detail->item->name ?? '-' }}</td>
                        <td class="py-2 text-center">{{ $detail->quantity }}</td>
                        <td class="py-2 text-center">
                            @if ($detail->is_returned)
                                <span class="text-green-600 font-medium">✔</span>
                            @else
                                <span class="text-red-600 font-medium">✘</span>
                            @endif
                        </td>
                        <td class="py-2 text-right">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada detail peminjaman ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

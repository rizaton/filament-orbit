<div class="space-y-4 p-4">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Detail Peminjaman</h2>
        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-100">ID Sewa</p>
                <p class="text-gray-700 dark:text-gray-200">{{ $rental->id_rental }}</p>
            </div>
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-100">Tanggal Pinjam</p>
                <p class="text-gray-700 dark:text-gray-200">{{ $rental->rent_date?->format('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-100">Tanggal Kembali</p>
                <p class="text-gray-700 dark:text-gray-200">{{ $rental->return_date?->format('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-100">Total Biaya</p>
                <p class="text-gray-700 dark:text-gray-200">{{ 'IDR ' . number_format($rental->total_fees, 2) ?? '0' }}
                </p>
            </div>
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-100">Terlambat</p>
                <p class="text-gray-700 dark:text-gray-200">{{ $rental->late_date?->format('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-100">Status</p>
                @php
                    $statusLabels = [
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rented' => 'Disewa',
                        'rejected' => 'Ditolak',
                        'returned' => 'Dikembalikan',
                        'late' => 'Terlambat',
                    ];

                    $statusColors = [
                        'pending' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                        'rented' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                        'late' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                        'returned' => 'bg-stone-100 text-stone-800 dark:bg-stone-900 dark:text-stone-200',
                    ];

                    $status = $rental->status;
                    $label = $statusLabels[$status] ?? ucfirst($status);
                    $colorClass =
                        $statusColors[$status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200';
                @endphp

                <p>
                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold {{ $colorClass }}">
                        {{ $label }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Detail Alat yang Dipinjam</h2>

        <table class="w-full text-sm text-left table-auto">
            <thead class="text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="py-2">Nama Alat</th>
                    <th class="py-2 text-center">Jumlah</th>
                    <th class="py-2 text-center">Sudah Dikembalikan</th>
                    <th class="py-2 text-right">Sub Total</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-200">
                @forelse ($details as $detail)
                    <tr
                        class="border-b border-gray-200 dark:border-gray-800 hover:bg-gray-200 dark:hover:bg-gray-800 transition">
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

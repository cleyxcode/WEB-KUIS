<x-filament-panels::page>
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">#</th>
                    <th class="px-4 py-3 text-left font-semibold">Siswa</th>
                    <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                    <th class="px-4 py-3 text-left font-semibold">Total Poin</th>
                    <th class="px-4 py-3 text-left font-semibold">Streak</th>
                    <th class="px-4 py-3 text-left font-semibold">Lencana</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($this->siswa as $index => $siswa)
                    @php $rank = $index + 1; @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3 font-bold text-lg">
                            @if ($rank === 1) 🥇
                            @elseif ($rank === 2) 🥈
                            @elseif ($rank === 3) 🥉
                            @else {{ $rank }}
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $siswa->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $siswa->nis }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $siswa->kelas }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full bg-teal-100 px-2.5 py-0.5 text-xs font-semibold text-teal-800 dark:bg-teal-900 dark:text-teal-200">
                                {{ number_format($siswa->total_poin) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">🔥 {{ $siswa->streak_sekarang }} hari</td>
                        <td class="px-4 py-3">🏅 {{ $siswa->lencana_count }}</td>
                    </tr>
                @endforeach

                @if ($this->siswa->isEmpty())
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            Belum ada data siswa.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</x-filament-panels::page>

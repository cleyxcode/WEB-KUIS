<x-filament-panels::page>

    {{-- Tab Navigation --}}
    <div class="flex gap-2 border-b border-gray-200 dark:border-gray-700 mb-6">
        <button
            wire:click="switchTab('kuis')"
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors
                {{ $this->activeTab === 'kuis'
                    ? 'border-b-2 border-teal-600 text-teal-600 dark:text-teal-400 dark:border-teal-400'
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
        >
            🎯 Riwayat Kuis
        </button>
        <button
            wire:click="switchTab('materi')"
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors
                {{ $this->activeTab === 'materi'
                    ? 'border-b-2 border-teal-600 text-teal-600 dark:text-teal-400 dark:border-teal-400'
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
        >
            📖 Riwayat Baca Materi
        </button>
    </div>

    {{-- ===================== TAB KUIS ===================== --}}
    @if ($this->activeTab === 'kuis')
        {{-- Filter Bar --}}
        <div class="flex flex-wrap gap-3 mb-4 items-end">
            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Status</label>
                <select
                    wire:model.live="filterStatusKuis"
                    class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm px-3 py-2 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
                    <option value="">Semua Status</option>
                    <option value="selesai">Selesai</option>
                    <option value="berlangsung">Berlangsung</option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Mata Pelajaran</label>
                <select
                    wire:model.live="filterMapelKuis"
                    class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm px-3 py-2 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
                    <option value="">Semua Mapel</option>
                    @foreach ($this->mataPelajaranOptions as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Siswa</label>
                <select
                    wire:model.live="filterSiswaKuis"
                    class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm px-3 py-2 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
                    <option value="">Semua Siswa</option>
                    @foreach ($this->siswaOptions as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            @if ($filterStatusKuis || $filterMapelKuis || $filterSiswaKuis)
                <button
                    wire:click="resetFiltersKuis"
                    class="px-3 py-2 text-sm text-red-600 dark:text-red-400 border border-red-300 dark:border-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                >
                    Reset Filter
                </button>
            @endif

            <div class="ml-auto text-sm text-gray-500 dark:text-gray-400 self-end pb-2">
                {{ $this->riwayatKuis->count() }} data
            </div>
        </div>

        {{-- Tabel Kuis --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Siswa</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Kuis</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Mapel</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Nilai</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Benar/Total</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Poin</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Waktu</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Dimulai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($this->riwayatKuis as $percobaan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $percobaan->siswa?->nama_lengkap ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $percobaan->siswa?->nis ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-800 dark:text-gray-200">
                                {{ \Illuminate\Support\Str::limit($percobaan->kuis?->judul ?? '-', 40) }}
                            </td>
                            <td class="px-4 py-3">
                                @php $jenis = $percobaan->kuis?->mataPelajaran?->jenis; @endphp
                                @if ($jenis)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                        {{ $jenis === 'IPA' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                        {{ $jenis }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($percobaan->status === 'selesai')
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                        {{ $percobaan->nilai >= 80 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                            : ($percobaan->nilai >= 60 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                        {{ $percobaan->nilai }}/100
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                {{ $percobaan->jumlah_benar ?? '-' }}/{{ $percobaan->total_soal ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-teal-100 px-2 py-0.5 text-xs font-semibold text-teal-800 dark:bg-teal-900 dark:text-teal-200">
                                    {{ $percobaan->poin_diperoleh ?? 0 }} poin
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 text-xs">
                                {{ $percobaan->waktu_pengerjaan ? gmdate('i:s', $percobaan->waktu_pengerjaan) : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    {{ $percobaan->status === 'selesai'
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                    {{ $percobaan->status === 'selesai' ? '✅ Selesai' : '⏳ Berlangsung' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400">
                                {{ $percobaan->dimulai_pada?->format('d M Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                Belum ada riwayat kuis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- ===================== TAB MATERI ===================== --}}
    @if ($this->activeTab === 'materi')
        {{-- Filter Bar --}}
        <div class="flex flex-wrap gap-3 mb-4 items-end">
            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Mata Pelajaran</label>
                <select
                    wire:model.live="filterMapelMateri"
                    class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm px-3 py-2 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
                    <option value="">Semua Mapel</option>
                    @foreach ($this->mataPelajaranOptions as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Siswa</label>
                <select
                    wire:model.live="filterSiswaMateri"
                    class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm px-3 py-2 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
                    <option value="">Semua Siswa</option>
                    @foreach ($this->siswaOptions as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            @if ($filterMapelMateri || $filterSiswaMateri)
                <button
                    wire:click="resetFiltersMateri"
                    class="px-3 py-2 text-sm text-red-600 dark:text-red-400 border border-red-300 dark:border-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                >
                    Reset Filter
                </button>
            @endif

            <div class="ml-auto text-sm text-gray-500 dark:text-gray-400 self-end pb-2">
                {{ $this->riwayatMateri->count() }} data
            </div>
        </div>

        {{-- Tabel Materi --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Siswa</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Judul Materi</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Bab</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Mapel</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Poin</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Dibaca Pada</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($this->riwayatMateri as $riwayat)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $riwayat->siswa?->nama_lengkap ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $riwayat->siswa?->nis ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-800 dark:text-gray-200">
                                {{ \Illuminate\Support\Str::limit($riwayat->materi?->judul ?? '-', 45) }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $riwayat->materi?->bab ? 'Bab ' . $riwayat->materi->bab : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @php $jenis = $riwayat->materi?->mataPelajaran?->jenis; @endphp
                                @if ($jenis)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                        {{ $jenis === 'IPA' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                        {{ $jenis }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-teal-100 px-2 py-0.5 text-xs font-semibold text-teal-800 dark:bg-teal-900 dark:text-teal-200">
                                    {{ $riwayat->poin_diperoleh ?? 3 }} poin
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    ✅ Sudah Dibaca
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400">
                                {{ $riwayat->dibaca_pada ? \Carbon\Carbon::parse($riwayat->dibaca_pada)->format('d M Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                Belum ada riwayat baca materi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

</x-filament-panels::page>

@extends('siswa.layouts.siswa')
@php $pageTitle = 'Kuis'; @endphp

@push('styles')
<style>
.filter-btn.active {
    background: #0d6e55;
    color: #fff;
    box-shadow: 0 4px 12px rgba(13,110,85,.3);
}
.kuis-card {
    transition: transform .15s ease, box-shadow .15s ease;
}
.kuis-card:active { transform: scale(.98); }
.kuis-card:hover  { box-shadow: 0 8px 24px rgba(13,110,85,.12); }

.badge-ipa { background:#f0fdf4; color:#166534; border-color:#bbf7d0; }
.badge-ips { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }

@keyframes slide-up {
    from { opacity:0; transform:translateY(18px); }
    to   { opacity:1; transform:translateY(0); }
}
.kuis-card { animation: slide-up .35s ease both; }
</style>
@endpush

@section('page-content')

{{-- ── Banner hero ─────────────────────────────────────────── --}}
<div class="bg-primary rounded-2xl p-5 md:p-6 relative overflow-hidden mb-4">
    <div class="absolute inset-0 opacity-10"
         style="background-image:radial-gradient(#fff 1px,transparent 1px);background-size:20px 20px;"></div>
    <div class="absolute -right-4 top-2 text-6xl opacity-20 select-none rotate-12">🎮</div>
    <div class="absolute right-14 bottom-3 text-4xl opacity-20 select-none -rotate-6">⭐</div>

    <div class="relative z-10">
        <p class="text-white/70 text-xs font-bold uppercase tracking-widest mb-1">Pilih Kuis</p>
        <h1 class="text-white text-xl md:text-2xl font-extrabold leading-tight mb-3">
            Yuk, Mulai Kuis Seru! 🚀
        </h1>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2
                         text-slate-400 text-xl pointer-events-none">search</span>
            <input id="searchInput"
                   type="text"
                   placeholder="Cari kuis..."
                   oninput="filterKuis()"
                   class="w-full h-11 pl-10 pr-4 rounded-full bg-white dark:bg-slate-800
                          text-sm font-semibold text-slate-700 dark:text-slate-200
                          placeholder:text-slate-400 border-0
                          focus:ring-2 focus:ring-primary/40 focus:outline-none shadow-sm"/>
        </div>
    </div>
</div>

{{-- ── Tombol masuk dengan kode ─────────────────────────────── --}}
<div class="mb-4 animate-fade-up">
    <a href="{{ route('siswa.kuis.masuk') }}"
       class="flex items-center gap-4 bg-amber-400 hover:bg-amber-500 active:scale-[.98]
              rounded-2xl px-5 py-4 shadow-lg shadow-amber-400/30 transition-all">
        <div class="size-12 bg-white/30 rounded-xl flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-white text-2xl">tag</span>
        </div>
        <div class="flex-1">
            <p class="text-white font-extrabold text-sm">Punya Kode Kuis?</p>
            <p class="text-white/80 text-xs font-medium">Masukkan kode dari gurumu</p>
        </div>
        <span class="material-symbols-outlined text-white text-xl">arrow_forward</span>
    </a>
</div>

{{-- ── Filter Mapel ─────────────────────────────────────────── --}}
@php
    $mapelList = $kuisList->pluck('mataPelajaran')->filter()->unique('id')->values();
@endphp

@if ($mapelList->isNotEmpty())
<div class="mb-4 -mx-1 animate-fade-up" style="animation-delay:.05s">
    <div class="flex gap-2 overflow-x-auto no-scrollbar px-1 pb-1">
        <button onclick="setFilter('semua', this)"
                class="filter-btn active shrink-0 px-4 py-2 rounded-full text-xs font-extrabold
                       uppercase tracking-wide border border-slate-200 dark:border-slate-700
                       text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800
                       transition-all whitespace-nowrap">
            Semua
        </button>
        @foreach ($mapelList as $mapel)
        <button onclick="setFilter('{{ $mapel->id }}', this)"
                data-mapel="{{ $mapel->id }}"
                class="filter-btn shrink-0 px-4 py-2 rounded-full text-xs font-extrabold
                       uppercase tracking-wide border border-slate-200 dark:border-slate-700
                       text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800
                       transition-all whitespace-nowrap flex items-center gap-1.5">
            <span>{{ $mapel->ikon }}</span>
            {{ $mapel->nama }}
        </button>
        @endforeach
    </div>
</div>
@endif

{{-- ── Jumlah hasil --}}
<p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 px-1">
    <span id="kuis-count">{{ $kuisList->count() }}</span> kuis tersedia
</p>

{{-- ── Daftar Kuis ──────────────────────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-3 pb-4" id="kuis-list">
    {{-- Loading saat filter/search (hidden by default) --}}
    <div id="filter-loading" class="hidden col-span-full py-8 flex flex-col items-center">
        <dotlottie-wc
            src="https://lottie.host/f58cb72b-0b3b-4d49-a3d0-59eb12699a64/tTowtuMPwA.lottie"
            autoplay loop
            style="width:120px;height:120px;display:block;">
        </dotlottie-wc>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest -mt-2">Mencari...</p>
    </div>

    @forelse ($kuisList as $i => $kuis)
    @php
        $jenis      = $kuis->mataPelajaran?->jenis ?? 'IPA';
        $badgeClass = $jenis === 'IPA' ? 'badge-ipa' : 'badge-ips';
        $sudah      = in_array($kuis->id, $sudahDikerjakan);
        $diffMap    = ['mudah' => '😊 Mudah', 'sedang' => '🤔 Sedang', 'sulit' => '🔥 Sulit'];
    @endphp

    <div class="kuis-card bg-white dark:bg-slate-800
                rounded-2xl border border-slate-100 dark:border-slate-700
                overflow-hidden shadow-sm"
         data-mapel="{{ $kuis->mata_pelajaran_id }}"
         data-judul="{{ strtolower($kuis->judul) }}"
         data-jenis="{{ strtolower($jenis) }}"
         style="animation-delay:{{ $i * 0.04 }}s">

        {{-- Top strip warna mapel --}}
        <div class="h-1.5 w-full {{ $jenis === 'IPA' ? 'bg-teal-400' : 'bg-blue-400' }}"></div>

        <div class="p-4">
            <div class="flex items-start gap-3">

                {{-- Ikon mapel --}}
                <div class="size-14 rounded-2xl {{ $jenis === 'IPA' ? 'bg-teal-50 dark:bg-teal-900/20' : 'bg-blue-50 dark:bg-blue-900/20' }}
                            flex items-center justify-center text-3xl shrink-0 border
                            {{ $jenis === 'IPA' ? 'border-teal-100' : 'border-blue-100' }}">
                    {{ $kuis->mataPelajaran?->ikon ?? '📚' }}
                </div>

                <div class="flex-1 min-w-0">
                    {{-- Judul + badge selesai --}}
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-extrabold text-slate-800 dark:text-slate-100 text-sm leading-snug">
                            {{ $kuis->judul }}
                        </h3>
                        @if ($sudah)
                        <span class="shrink-0 text-[10px] font-extrabold bg-emerald-100 text-emerald-700
                                     border border-emerald-200 px-2 py-0.5 rounded-full whitespace-nowrap">
                            ✓ Selesai
                        </span>
                        @endif
                    </div>

                    {{-- Badge mapel + kesulitan --}}
                    <div class="flex flex-wrap gap-1.5 mt-1.5">
                        <span class="text-[10px] font-extrabold border px-2 py-0.5 rounded-full {{ $badgeClass }}">
                            {{ $jenis }}
                        </span>
                        @if ($kuis->soal->isNotEmpty())
                        @php $diff = $kuis->soal->groupBy('tingkat_kesulitan')->keys()->first() ?? 'mudah'; @endphp
                        <span class="text-[10px] font-bold text-slate-500 bg-slate-50 dark:bg-slate-700
                                     border border-slate-200 dark:border-slate-600 px-2 py-0.5 rounded-full">
                            {{ $diffMap[$diff] ?? '' }}
                        </span>
                        @endif
                    </div>

                    {{-- Meta info --}}
                    <div class="flex items-center gap-3 mt-2 text-[11px] text-slate-500 font-semibold">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">quiz</span>
                            {{ $kuis->jumlah_soal }} soal
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">timer</span>
                            {{ $kuis->waktu_per_soal }}dtk/soal
                        </span>
                        @if ($kuis->berakhir_pada)
                        <span class="flex items-center gap-1 text-amber-500">
                            <span class="material-symbols-outlined text-sm">schedule</span>
                            Berakhir {{ $kuis->berakhir_pada->diffForHumans() }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            @if ($kuis->deskripsi)
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 leading-relaxed line-clamp-2 font-medium">
                {{ $kuis->deskripsi }}
            </p>
            @endif

            {{-- Tombol aksi --}}
            <div class="mt-3 flex gap-2">
                @if ($sudah)
                <a href="{{ route('siswa.kuis.hasil', $kuis->kode_kuis) }}"
                   class="flex-1 h-10 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300
                          rounded-full font-bold text-xs flex items-center justify-center gap-1.5
                          hover:bg-slate-200 transition-colors">
                    <span class="material-symbols-outlined text-sm">bar_chart</span>
                    Lihat Hasil
                </a>
                @else
                <form method="POST" action="{{ route('siswa.kuis.mulai', $kuis->kode_kuis) }}" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full h-10 bg-primary hover:bg-primary/90 text-white
                                   rounded-full font-extrabold text-xs
                                   shadow-[0_4px_0_0_#064e3b] active:shadow-none active:translate-y-0.5
                                   flex items-center justify-center gap-1.5 transition-all">
                        <span class="material-symbols-outlined text-sm">play_arrow</span>
                        Mulai Kuis
                    </button>
                </form>
                @endif

                {{-- Kode badge --}}
                <div class="h-10 px-3 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600
                            rounded-full flex items-center gap-1.5 text-xs font-extrabold
                            text-slate-600 dark:text-slate-300">
                    <span class="material-symbols-outlined text-sm text-slate-400">tag</span>
                    {{ $kuis->kode_kuis }}
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="py-16 flex flex-col items-center text-slate-400" id="empty-state">
        <span class="text-6xl mb-3">🎮</span>
        <p class="font-extrabold text-slate-600 dark:text-slate-300">Belum ada kuis aktif</p>
        <p class="text-sm mt-1">Cek lagi nanti ya!</p>
    </div>
    @endforelse
</div>

{{-- Empty state saat search --}}
<div id="empty-search" class="hidden col-span-full py-8 flex flex-col items-center text-slate-400">
    <div class="flex justify-center">
        <dotlottie-wc
            src="https://lottie.host/f58cb72b-0b3b-4d49-a3d0-59eb12699a64/tTowtuMPwA.lottie"
            autoplay loop
            style="width:120px;height:120px;display:block;">
        </dotlottie-wc>
    </div>
    <p class="font-extrabold text-slate-600 dark:text-slate-300 -mt-2">Kuis tidak ditemukan</p>
    <p class="text-xs mt-1 text-slate-400">Coba kata kunci lain</p>
</div>

@endsection

@push('scripts')
<script>
let activeFilter = 'semua';

function setFilter(id, btn) {
    activeFilter = id;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    filterKuis();
}

let filterTimer = null;
function filterKuis() {
    clearTimeout(filterTimer);

    // Tampilkan lottie loading singkat saat mengetik
    const fl = document.getElementById('filter-loading');
    fl.classList.remove('hidden');
    fl.classList.add('flex');
    document.querySelectorAll('#kuis-list [data-mapel]').forEach(c => c.style.display = 'none');
    document.getElementById('empty-search').classList.add('hidden');

    filterTimer = setTimeout(() => {
        fl.classList.add('hidden');
        fl.classList.remove('flex');

        const q     = document.getElementById('searchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('#kuis-list [data-mapel]');
        let visible = 0;

        cards.forEach(card => {
            const matchFilter = activeFilter === 'semua' || card.dataset.mapel == activeFilter;
            const matchSearch = !q || card.dataset.judul.includes(q) || card.dataset.jenis.includes(q);
            const show        = matchFilter && matchSearch;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('kuis-count').textContent = visible;
        document.getElementById('empty-search').classList.toggle('hidden', visible > 0);
    }, 420); // delay 420ms — cukup terlihat tapi tidak lambat
}
</script>
@endpush

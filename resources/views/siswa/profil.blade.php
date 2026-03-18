@extends('siswa.layouts.siswa')
@php $pageTitle = 'Profil'; @endphp

@push('styles')
<style>
.stat-card { transition: transform .15s ease; }
.stat-card:active { transform: scale(.97); }

@keyframes count-pop {
    0%   { transform:scale(.7); opacity:0; }
    70%  { transform:scale(1.1); }
    100% { transform:scale(1); opacity:1; }
}
.count-anim { animation: count-pop .4s cubic-bezier(.34,1.56,.64,1) both; }

.lencana-locked { filter: grayscale(1); opacity:.35; }

@keyframes row-in {
    from { opacity:0; transform:translateX(-12px); }
    to   { opacity:1; transform:translateX(0); }
}
.row-in { animation: row-in .3s ease both; }

.no-scrollbar::-webkit-scrollbar { display:none; }
.no-scrollbar { -ms-overflow-style:none; scrollbar-width:none; }
</style>
@endpush

@section('page-content')

{{-- ══ HERO PROFIL ══════════════════════════════════════════ --}}
<div class="bg-primary rounded-2xl relative overflow-hidden px-5 py-5 md:py-6 mb-5">
    <div class="absolute inset-0 opacity-10"
         style="background-image:radial-gradient(#fff 1px,transparent 1px);background-size:20px 20px;"></div>
    <div class="absolute -right-4 top-4 text-7xl opacity-15 select-none rotate-12">🎓</div>

    <div class="relative z-10 flex items-center gap-4">
        <div class="size-20 rounded-full border-4 border-white/50 overflow-hidden
                    bg-white/20 flex items-center justify-center shadow-xl shrink-0">
            @if ($siswa->foto)
                <img src="{{ asset('storage/' . $siswa->foto) }}" class="w-full h-full object-cover"/>
            @else
                <span class="text-white font-extrabold text-3xl">
                    {{ strtoupper(substr($siswa->nama_panggilan, 0, 1)) }}
                </span>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-white/70 text-[10px] font-bold uppercase tracking-widest">Profil Siswa</p>
            <h1 class="text-white text-xl font-extrabold leading-tight truncate">{{ $siswa->nama_lengkap }}</h1>
            <p class="text-white/80 text-xs font-semibold mt-0.5">{{ $siswa->nama_panggilan }} · Kelas {{ $siswa->kelas }}</p>
            <div class="flex flex-wrap items-center gap-2 mt-2">
                <span class="bg-white/20 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full">NIS: {{ $siswa->nis }}</span>
                <span class="bg-white/20 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full">
                    {{ $siswa->jenis_kelamin === 'L' ? '👦 Laki-laki' : '👧 Perempuan' }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ══ STAT CARDS ══════════════════════════════════════════ --}}
<div class="mb-5 animate-fade-up">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        {{-- Poin --}}
        <div class="stat-card col-span-2 md:col-span-4 bg-white dark:bg-slate-800 rounded-2xl p-4
                    shadow-lg shadow-primary/10 border border-slate-100 dark:border-slate-700
                    flex items-center gap-4">
            <div class="size-14 bg-amber-50 dark:bg-amber-900/20 rounded-2xl
                        flex items-center justify-center border border-amber-200 dark:border-amber-800 shrink-0">
                <span class="material-symbols-outlined text-amber-500 text-3xl"
                      style="font-variation-settings:'FILL' 1">workspace_premium</span>
            </div>
            <div>
                <p class="text-3xl font-black text-amber-500 tabular-nums count-anim">
                    {{ number_format($siswa->total_poin) }}
                </p>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wide">Total Poin</p>
            </div>
        </div>

        {{-- Streak --}}
        <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-4
                    shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="size-10 bg-orange-50 dark:bg-orange-900/20 rounded-xl
                        flex items-center justify-center mb-2">
                <span class="material-symbols-outlined text-orange-500 text-2xl"
                      style="font-variation-settings:'FILL' 1">local_fire_department</span>
            </div>
            <p class="text-2xl font-black text-slate-800 dark:text-slate-100 tabular-nums count-anim"
               style="animation-delay:.1s">
                {{ $siswa->streak_sekarang }}
            </p>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wide">Hari Streak</p>
            @if ($siswa->streak_terpanjang > 0)
            <p class="text-[10px] text-slate-400 mt-0.5">Terpanjang: {{ $siswa->streak_terpanjang }} hari</p>
            @endif
        </div>

        {{-- Total kuis --}}
        <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-4
                    shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center mb-2">
                <span class="material-symbols-outlined text-primary text-2xl"
                      style="font-variation-settings:'FILL' 1">sports_esports</span>
            </div>
            <p class="text-2xl font-black text-slate-800 dark:text-slate-100 tabular-nums count-anim"
               style="animation-delay:.15s">
                {{ $totalKuis }}
            </p>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wide">Kuis Selesai</p>
            @if ($rataRataNilai)
            <p class="text-[10px] text-slate-400 mt-0.5">Rata-rata: {{ round($rataRataNilai) }}/100</p>
            @endif
        </div>

        {{-- Lencana --}}
        <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-4
                    shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="size-10 bg-purple-50 dark:bg-purple-900/20 rounded-xl flex items-center justify-center mb-2">
                <span class="text-2xl">🏅</span>
            </div>
            <p class="text-2xl font-black text-slate-800 dark:text-slate-100 tabular-nums count-anim"
               style="animation-delay:.2s">
                {{ $siswa->lencana->count() }}
            </p>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wide">Lencana Diraih</p>
        </div>

        {{-- Terakhir aktif --}}
        <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-4
                    shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="size-10 bg-teal-50 dark:bg-teal-900/20 rounded-xl flex items-center justify-center mb-2">
                <span class="material-symbols-outlined text-teal-600 text-2xl">schedule</span>
            </div>
            <p class="text-sm font-extrabold text-slate-800 dark:text-slate-100 leading-tight">
                {{ $siswa->terakhir_aktif ? $siswa->terakhir_aktif->diffForHumans() : 'Baru bergabung' }}
            </p>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wide mt-0.5">Terakhir Aktif</p>
        </div>
    </div>
</div>

{{-- ══ LENCANA ══════════════════════════════════════════════ --}}
<section class="mb-5 animate-fade-up" style="animation-delay:.1s">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-widest">
            🏅 Koleksi Lencana
        </h2>
        <span class="text-xs font-bold text-slate-400">
            {{ $siswa->lencana->count() }} diraih
        </span>
    </div>

    @php
        $colorMap = [
            'green'  => ['bg' => 'bg-emerald-100 dark:bg-emerald-900/30', 'text' => 'text-emerald-700'],
            'blue'   => ['bg' => 'bg-blue-100 dark:bg-blue-900/30',       'text' => 'text-blue-700'],
            'purple' => ['bg' => 'bg-purple-100 dark:bg-purple-900/30',   'text' => 'text-purple-700'],
            'amber'  => ['bg' => 'bg-amber-100 dark:bg-amber-900/30',     'text' => 'text-amber-700'],
            'teal'   => ['bg' => 'bg-teal-100 dark:bg-teal-900/30',       'text' => 'text-teal-700'],
            'coral'  => ['bg' => 'bg-orange-100 dark:bg-orange-900/30',   'text' => 'text-orange-600'],
        ];
    @endphp

    <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2 -mx-1 px-1">
        @forelse ($siswa->lencana as $lencana)
        @php $c = $colorMap[$lencana->warna] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-600']; @endphp
        <div class="shrink-0 flex flex-col items-center gap-1.5 w-20">
            <div class="size-16 rounded-full {{ $c['bg'] }} border-4 border-white dark:border-slate-800
                        flex items-center justify-center shadow-md">
                <span class="text-3xl leading-none">{{ $lencana->ikon }}</span>
            </div>
            <p class="text-[10px] font-extrabold text-slate-600 dark:text-slate-400
                      text-center leading-tight max-w-[80px]">{{ $lencana->nama }}</p>
            <p class="text-[9px] text-slate-400 font-medium">
                {{ $lencana->pivot->diperoleh_pada ? \Carbon\Carbon::parse($lencana->pivot->diperoleh_pada)->format('d/m/y') : '' }}
            </p>
        </div>
        @empty
        <div class="py-6 px-4 text-slate-400 text-center w-full">
            <p class="text-3xl mb-1">🔒</p>
            <p class="text-xs font-bold">Belum ada lencana</p>
        </div>
        @endforelse

        {{-- Slot terkunci --}}
        <div class="shrink-0 flex flex-col items-center gap-1.5 w-20 lencana-locked">
            <div class="size-16 rounded-full bg-slate-100 dark:bg-slate-700
                        border-4 border-white dark:border-slate-800
                        flex items-center justify-center shadow-md">
                <span class="material-symbols-outlined text-slate-400 text-3xl">lock</span>
            </div>
            <p class="text-[10px] font-extrabold text-slate-400 text-center">???</p>
        </div>
    </div>
</section>

{{-- ══ LOG POIN TERBARU ═════════════════════════════════════ --}}
@if ($siswa->logPoin->isNotEmpty())
<section class="mb-5 animate-fade-up" style="animation-delay:.15s">
    <h2 class="text-sm font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-3">
        ⭐ Riwayat Poin
    </h2>
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700
                shadow-sm overflow-hidden">
        @foreach ($siswa->logPoin as $idx => $log)
        @php
            $sumberIcon = ['kuis' => '🎮', 'materi' => '📖', 'streak' => '🔥', 'bonus' => '🎁'];
            $sumberColor= ['kuis' => 'bg-primary/10 text-primary',
                           'streak' => 'bg-orange-50 text-orange-600',
                           'bonus' => 'bg-purple-50 text-purple-600'];
        @endphp
        <div class="row-in flex items-center gap-3 px-4 py-3
                    {{ !$loop->last ? 'border-b border-slate-50 dark:border-slate-700' : '' }}"
             style="animation-delay:{{ $idx * 0.04 }}s">
            <div class="size-9 rounded-xl {{ $sumberColor[$log->sumber] ?? 'bg-slate-50 text-slate-500' }}
                        flex items-center justify-center text-lg shrink-0">
                {{ $sumberIcon[$log->sumber] ?? '⭐' }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">
                    {{ $log->keterangan ?: ucfirst($log->sumber) }}
                </p>
                <p class="text-[10px] text-slate-400 font-medium">
                    {{ $log->created_at->diffForHumans() }}
                </p>
            </div>
            <span class="text-sm font-extrabold text-amber-500 shrink-0">+{{ $log->poin }}</span>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ══ RIWAYAT KUIS ═════════════════════════════════════════ --}}
<section class="mb-4 animate-fade-up" style="animation-delay:.2s">
    <h2 class="text-sm font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-3">
        🎯 Riwayat Kuis
    </h2>

    @if ($riwayatKuis->isEmpty())
    <div class="py-4 bg-white dark:bg-slate-800 rounded-2xl
                border border-slate-100 dark:border-slate-700
                flex flex-col items-center empty-lottie">
        <dotlottie-wc
            src="https://lottie.host/f58cb72b-0b3b-4d49-a3d0-59eb12699a64/tTowtuMPwA.lottie"
            autoplay loop>
        </dotlottie-wc>
        <p class="text-sm font-extrabold text-slate-600 dark:text-slate-300 -mt-2 pb-4">
            Belum pernah mengerjakan kuis
        </p>
        <a href="{{ route('siswa.kuis.masuk') }}"
           class="mb-4 px-6 py-2.5 bg-primary text-white rounded-full
                  text-xs font-extrabold shadow-[0_4px_0_0_#064e3b]
                  active:shadow-none active:translate-y-0.5 transition-all">
            Mulai Kuis Sekarang 🚀
        </a>
    </div>
    @else
    <div class="space-y-2">
        @foreach ($riwayatKuis as $idx => $percobaan)
        @php
            $nilai      = round($percobaan->nilai);
            $nilaiColor = $nilai >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                        : ($nilai >= 60 ? 'bg-amber-50 text-amber-700 border-amber-200'
                        : 'bg-red-50 text-red-600 border-red-200');
            $menit = floor($percobaan->waktu_pengerjaan / 60);
            $detik = $percobaan->waktu_pengerjaan % 60;
        @endphp
        <div class="row-in bg-white dark:bg-slate-800 rounded-2xl
                    border border-slate-100 dark:border-slate-700 shadow-sm p-4
                    flex items-center gap-3"
             style="animation-delay:{{ $idx * 0.04 }}s">

            {{-- Ikon mapel --}}
            <div class="size-12 rounded-xl bg-slate-50 dark:bg-slate-700 flex items-center justify-center
                        text-2xl shrink-0 border border-slate-100 dark:border-slate-600">
                {{ $percobaan->kuis->mataPelajaran?->ikon ?? '📝' }}
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-sm font-extrabold text-slate-800 dark:text-slate-100 truncate">
                    {{ $percobaan->kuis->judul }}
                </p>
                <div class="flex items-center gap-2 mt-1 flex-wrap">
                    <span class="text-[10px] font-bold text-slate-500">
                        {{ $percobaan->jumlah_benar }}/{{ $percobaan->total_soal }} benar
                    </span>
                    <span class="text-[10px] text-slate-400">·</span>
                    <span class="text-[10px] font-bold text-slate-500">
                        ⏱ {{ $menit }}:{{ str_pad($detik, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="text-[10px] text-slate-400">·</span>
                    <span class="text-[10px] text-amber-500 font-bold">
                        +{{ $percobaan->poin_diperoleh }} poin
                    </span>
                </div>
            </div>

            <div class="text-right shrink-0 flex flex-col items-end gap-1">
                <span class="text-sm font-extrabold border px-2.5 py-1 rounded-full {{ $nilaiColor }}">
                    {{ $nilai }}
                </span>
                <span class="text-[9px] text-slate-400 font-medium">
                    {{ $percobaan->diselesaikan_pada?->format('d/m/y') }}
                </span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if ($riwayatKuis->hasPages())
    <div class="mt-4 flex justify-center gap-2">
        @if ($riwayatKuis->onFirstPage())
        <span class="px-4 py-2 rounded-full bg-slate-100 text-slate-400 text-xs font-bold cursor-not-allowed">
            ← Prev
        </span>
        @else
        <a href="{{ $riwayatKuis->previousPageUrl() }}"
           class="px-4 py-2 rounded-full bg-white border border-slate-200 text-slate-600
                  text-xs font-bold hover:bg-primary hover:text-white hover:border-primary transition-all">
            ← Prev
        </a>
        @endif

        <span class="px-4 py-2 rounded-full bg-primary text-white text-xs font-extrabold">
            {{ $riwayatKuis->currentPage() }} / {{ $riwayatKuis->lastPage() }}
        </span>

        @if ($riwayatKuis->hasMorePages())
        <a href="{{ $riwayatKuis->nextPageUrl() }}"
           class="px-4 py-2 rounded-full bg-white border border-slate-200 text-slate-600
                  text-xs font-bold hover:bg-primary hover:text-white hover:border-primary transition-all">
            Next →
        </a>
        @else
        <span class="px-4 py-2 rounded-full bg-slate-100 text-slate-400 text-xs font-bold cursor-not-allowed">
            Next →
        </span>
        @endif
    </div>
    @endif
    @endif
</section>

@endsection

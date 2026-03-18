@extends('siswa.layouts.siswa')
@php $pageTitle = 'Beranda'; @endphp

@push('styles')
<style>
    .confetti-pattern {
        background-image:
            radial-gradient(#ffffff33 2px, transparent 2px),
            radial-gradient(#ffffff33 2px, transparent 2px);
        background-size: 32px 32px;
        background-position: 0 0, 16px 16px;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('page-content')

{{-- Grid utama desktop: 2 kolom --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 md:gap-6">

    {{-- ── Kolom Kiri (2/3 lebar) ── --}}
    <div class="lg:col-span-2 space-y-5 md:space-y-6">

        {{-- ── Hero Card: Poin & Peringkat ── --}}
        <section class="relative bg-primary rounded-2xl p-5 md:p-6 text-white overflow-hidden shadow-xl shadow-primary/20 animate-fade-up">
            <div class="absolute inset-0 confetti-pattern opacity-20 pointer-events-none"></div>

            <div class="relative z-10">
                {{-- Top row --}}
                <div class="flex justify-between items-start mb-4">
                    <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">emoji_events</span>
                        Peringkat #{{ $peringkat }}
                    </span>
                    <span class="material-symbols-outlined text-white/40 text-xl">workspace_premium</span>
                </div>

                {{-- Poin + progress --}}
                <div class="flex flex-col sm:flex-row sm:items-end sm:gap-8 mb-4">
                    <div>
                        <p class="text-white/60 font-bold text-[10px] tracking-[0.2em] uppercase mb-1">Total Poin Kamu</p>
                        <h1 class="text-4xl md:text-5xl font-black tracking-tight tabular-nums">
                            {{ number_format($siswa->total_poin) }}
                        </h1>
                    </div>
                    <div class="flex-1 mt-4 sm:mt-0 sm:pb-1">
                        <div class="flex justify-between text-[10px] font-bold text-white/60 mb-1.5">
                            <span>Menuju {{ number_format($targetPoin) }} poin</span>
                            <span>{{ $progressPct }}%</span>
                        </div>
                        <div class="h-3 w-full bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-400 rounded-full transition-all duration-700"
                                 style="width: {{ $progressPct }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white/10 rounded-xl px-3 py-2.5 text-center">
                        <p class="text-xl md:text-2xl font-black">{{ $siswa->streak_sekarang }}</p>
                        <p class="text-[9px] text-white/60 uppercase tracking-wide font-bold">Hari Streak</p>
                    </div>
                    <div class="bg-white/10 rounded-xl px-3 py-2.5 text-center">
                        <p class="text-xl md:text-2xl font-black">{{ $siswa->percobaanKuis()->where('status','selesai')->count() }}</p>
                        <p class="text-[9px] text-white/60 uppercase tracking-wide font-bold">Kuis Selesai</p>
                    </div>
                    <div class="bg-white/10 rounded-xl px-3 py-2.5 text-center">
                        <p class="text-xl md:text-2xl font-black">{{ $siswa->lencana->count() }}</p>
                        <p class="text-[9px] text-white/60 uppercase tracking-wide font-bold">Lencana</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Menu Cepat ── --}}
        <section class="animate-fade-up" style="animation-delay:.07s">
            <h3 class="text-xs font-extrabold mb-3 text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                Menu Cepat
            </h3>
            <div class="grid grid-cols-2 gap-3 md:gap-4">
                <a href="{{ route('siswa.kuis.masuk') }}"
                   class="bg-amber-400 hover:bg-amber-500 active:scale-95
                          rounded-2xl px-5 py-4 flex items-center gap-4
                          shadow-lg shadow-amber-400/30 transition-all duration-200">
                    <div class="bg-white/30 size-12 rounded-xl flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-2xl text-white" style="font-variation-settings:'FILL' 1">sports_esports</span>
                    </div>
                    <div>
                        <p class="text-white font-extrabold text-sm leading-tight">Masuk Kuis</p>
                        <p class="text-white/70 text-xs font-medium mt-0.5">Pakai kode guru</p>
                    </div>
                </a>

                <a href="{{ route('siswa.leaderboard') }}"
                   class="bg-emerald-500 hover:bg-emerald-600 active:scale-95
                          rounded-2xl px-5 py-4 flex items-center gap-4
                          shadow-lg shadow-emerald-500/30 transition-all duration-200">
                    <div class="bg-white/30 size-12 rounded-xl flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-2xl text-white" style="font-variation-settings:'FILL' 1">military_tech</span>
                    </div>
                    <div>
                        <p class="text-white font-extrabold text-sm leading-tight">Peringkat</p>
                        <p class="text-white/70 text-xs font-medium mt-0.5">Lihat papan top</p>
                    </div>
                </a>
            </div>
        </section>

        {{-- ── Kuis Aktif ── --}}
        @if ($kuisAktif->isNotEmpty())
        <section class="animate-fade-up" style="animation-delay:.14s">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                    Kuis Tersedia
                </h3>
                <a href="{{ route('siswa.kuis.index') }}" class="text-primary text-xs font-bold hover:underline">
                    Lihat Semua →
                </a>
            </div>

            <div class="space-y-2.5">
                @foreach ($kuisAktif as $kuis)
                    @php
                        $jenis      = $kuis->mataPelajaran?->jenis ?? 'IPA';
                        $bgMap      = ['IPA' => 'bg-teal-50 dark:bg-teal-900/20 border-teal-200 dark:border-teal-800',
                                       'IPS' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800'];
                        $badgeMap   = ['IPA' => 'bg-teal-100 text-teal-700', 'IPS' => 'bg-blue-100 text-blue-700'];
                    @endphp
                    <a href="{{ route('siswa.kuis.mulai', $kuis->kode_kuis) }}"
                       class="flex items-center gap-4 p-4 rounded-2xl border
                              {{ $bgMap[$jenis] ?? 'bg-slate-50 border-slate-200' }}
                              hover:shadow-md active:scale-[.98] transition-all duration-200 bg-white dark:bg-slate-800">
                        <div class="size-12 rounded-xl bg-white dark:bg-slate-700 shadow flex items-center justify-center shrink-0 text-2xl border border-slate-100 dark:border-slate-600">
                            {{ $kuis->mataPelajaran?->ikon ?? '📚' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-800 dark:text-slate-100 truncate text-sm">
                                {{ $kuis->judul }}
                            </p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $badgeMap[$jenis] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $jenis }}
                                </span>
                                <span class="text-[10px] text-slate-500 font-semibold">
                                    {{ $kuis->jumlah_soal }} soal · {{ $kuis->waktu_per_soal }}dtk/soal
                                </span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-slate-400 text-xl shrink-0">chevron_right</span>
                    </a>
                @endforeach
            </div>
        </section>
        @endif
    </div>

    {{-- ── Kolom Kanan (1/3 lebar) ── --}}
    <div class="space-y-5 md:space-y-6">

        {{-- ── Lencana ── --}}
        <section class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-800 animate-fade-up" style="animation-delay:.1s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                    Lencana Kamu
                </h3>
                <a href="{{ route('siswa.profil') }}" class="text-primary text-xs font-bold hover:underline">
                    Semua →
                </a>
            </div>

            <div class="grid grid-cols-4 lg:grid-cols-3 gap-3">
                @forelse ($siswa->lencana->take(8) as $lencana)
                    @php
                        $colorMap = [
                            'green'  => 'bg-emerald-100 text-emerald-600',
                            'blue'   => 'bg-blue-100 text-blue-600',
                            'purple' => 'bg-purple-100 text-purple-600',
                            'amber'  => 'bg-amber-100 text-amber-600',
                            'teal'   => 'bg-teal-100 text-teal-600',
                            'coral'  => 'bg-orange-100 text-orange-600',
                        ];
                        $colorClass = $colorMap[$lencana->warna] ?? 'bg-slate-100 text-slate-500';
                    @endphp
                    <div class="flex flex-col items-center gap-1.5 animate-pop-in">
                        <div class="size-12 rounded-full {{ $colorClass }} flex items-center justify-center
                                    border-2 border-white dark:border-slate-800 shadow-sm">
                            <span class="text-xl leading-none">{{ $lencana->ikon }}</span>
                        </div>
                        <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 text-center leading-tight line-clamp-2">
                            {{ $lencana->nama }}
                        </span>
                    </div>
                @empty
                    <div class="col-span-4 lg:col-span-3 text-center py-6">
                        <span class="text-3xl block mb-2">🔒</span>
                        <p class="text-xs text-slate-400 font-bold">Belum ada lencana</p>
                        <p class="text-[10px] text-slate-300 mt-1">Ikuti kuis untuk mendapatkan!</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- ── Riwayat Terakhir ── --}}
        @if ($riwayatKuis->isNotEmpty())
        <section class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-800 animate-fade-up" style="animation-delay:.2s">
            <h3 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-4">
                Riwayat Terakhir
            </h3>
            <div class="space-y-3">
                @foreach ($riwayatKuis as $percobaan)
                    @php
                        $nilaiColor = $percobaan->nilai >= 80 ? 'text-emerald-600 bg-emerald-50'
                                    : ($percobaan->nilai >= 60 ? 'text-amber-600 bg-amber-50'
                                    : 'text-red-500 bg-red-50');
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-base shrink-0">
                            {{ $percobaan->kuis->mataPelajaran?->ikon ?? '📝' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-700 dark:text-slate-200 truncate text-xs">
                                {{ $percobaan->kuis->judul }}
                            </p>
                            <p class="text-[10px] text-slate-400 font-semibold">
                                {{ $percobaan->diselesaikan_pada?->diffForHumans() }}
                            </p>
                        </div>
                        <span class="text-xs font-extrabold px-2 py-0.5 rounded-full {{ $nilaiColor }} shrink-0">
                            {{ round($percobaan->nilai) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</div>

@endsection

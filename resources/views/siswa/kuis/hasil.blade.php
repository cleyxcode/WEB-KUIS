@extends('siswa.layouts.app')
@section('title', 'Hasil Kuis — {{ $kuis->judul }}')

@push('styles')
<style>
/* Ring animasi masuk */
#nilai-ring {
    stroke-dashoffset: {{ 2 * M_PI * 74 }};
    transition: stroke-dashoffset 1.4s cubic-bezier(.4,0,.2,1) .5s;
}
/* Counter angka nilai */
@keyframes count-up {
    from { opacity: 0; transform: scale(.6); }
    to   { opacity: 1; transform: scale(1); }
}
#nilai-angka { animation: count-up .5s cubic-bezier(.34,1.56,.64,1) .4s both; }
/* Confetti */
.confetti-piece {
    position: fixed; pointer-events: none;
    width: 10px; height: 10px; border-radius: 3px;
    animation: fall 1.2s ease-out forwards;
}
@keyframes fall {
    0%   { opacity:1; transform:translateY(0) rotate(0deg) scale(1); }
    100% { opacity:0; transform:translateY(220px) rotate(720deg) scale(.3); }
}
/* Pembahasan item */
.pembahasan-item.benar  { border-left: 4px solid #16a34a; }
.pembahasan-item.salah  { border-left: 4px solid #ef4444; }
/* Slide-down pembahasan */
#pembahasan-wrapper {
    max-height: 0; overflow: hidden;
    transition: max-height .6s cubic-bezier(.4,0,.2,1);
}
#pembahasan-wrapper.open { max-height: 9999px; }
</style>
@endphp

@php
    $nilai      = round($percobaan->nilai);
    $circ       = 2 * M_PI * 74;          // ≈ 465
    $offset     = $circ * (1 - $nilai/100);
    $nilaiColor = $nilai >= 80 ? '#0d6e55' : ($nilai >= 60 ? '#f59e0b' : '#ef4444');
    $emoji      = $nilai >= 90 ? '🏆' : ($nilai >= 75 ? '🎉' : ($nilai >= 60 ? '👍' : '💪'));
    $kalimat    = $nilai >= 90 ? 'KEREN BANGET!' : ($nilai >= 75 ? 'BAGUS SEKALI!' : ($nilai >= 60 ? 'LUMAYAN NIH!' : 'TETAP SEMANGAT!'));
    $sub        = $nilai >= 75 ? 'Nilai terbaikmu hari ini!' : 'Coba lagi untuk hasil lebih baik!';

    // format waktu mm:ss
    $menit  = floor($percobaan->waktu_pengerjaan / 60);
    $detik  = $percobaan->waktu_pengerjaan % 60;
    $waktuFmt = sprintf('%d:%02d', $menit, $detik);
@endphp

@section('content')
<div class="relative min-h-screen max-w-md mx-auto bg-gradient-to-b
            from-primary/15 via-primary/5 to-background-light
            dark:from-primary/25 dark:to-background-dark
            overflow-x-hidden pb-10">

    {{-- ── Navigasi atas ── --}}
    <div class="flex items-center justify-between p-4">
        <a href="{{ route('siswa.dashboard') }}"
           class="size-12 flex items-center justify-center rounded-full
                  bg-white/60 dark:bg-white/10 backdrop-blur-sm
                  text-slate-800 dark:text-slate-200 hover:bg-white transition-colors">
            <span class="material-symbols-outlined">home</span>
        </a>
        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 flex-1 text-center px-3 truncate">
            {{ $kuis->judul }}
        </h2>
        <a href="{{ route('siswa.leaderboard') }}"
           class="size-12 flex items-center justify-center rounded-full
                  bg-white/60 dark:bg-white/10 backdrop-blur-sm
                  text-slate-800 dark:text-slate-200 hover:bg-white transition-colors">
            <span class="material-symbols-outlined">leaderboard</span>
        </a>
    </div>

    {{-- ── Header maskot ── --}}
    <div class="flex flex-col items-center pt-4 pb-6 animate-fade-up">
        <div class="relative mb-4">
            <div class="text-8xl animate-bounce-slow leading-none select-none">{{ $emoji }}</div>
            <div class="absolute -top-3 -right-3 text-amber-400 animate-bounce-slow"
                 style="animation-delay:.3s">
                <span class="material-symbols-outlined text-4xl"
                      style="font-variation-settings:'FILL' 1">celebration</span>
            </div>
            <div class="absolute top-1/2 -left-5 text-primary animate-bounce-slow"
                 style="animation-delay:.6s">
                <span class="material-symbols-outlined text-3xl"
                      style="font-variation-settings:'FILL' 1">star</span>
            </div>
        </div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-slate-100 tracking-tight text-center">
            {{ $kalimat }} {{ $emoji }}
        </h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium mt-1 text-sm">{{ $sub }}</p>

        {{-- Badge mapel --}}
        <div class="mt-3 flex items-center gap-2 bg-white/80 dark:bg-white/10
                    backdrop-blur-sm px-4 py-1.5 rounded-full border border-primary/20 shadow-sm">
            <span class="text-lg">{{ $kuis->mataPelajaran?->ikon ?? '📚' }}</span>
            <span class="text-xs font-extrabold text-primary uppercase tracking-widest">
                {{ $kuis->mataPelajaran?->nama ?? 'Kuis' }}
            </span>
        </div>
    </div>

    {{-- ── Score Card ── --}}
    <div class="px-5 animate-fade-up" style="animation-delay:.1s">
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-xl shadow-primary/10">

            {{-- Ring nilai --}}
            <div class="flex flex-col items-center mb-6">
                <div class="relative flex items-center justify-center size-40">
                    <svg class="absolute size-40 -rotate-90" viewBox="0 0 160 160">
                        <circle cx="80" cy="80" r="74" fill="transparent"
                                stroke="#f1f5f9" stroke-width="12"/>
                        <circle id="nilai-ring"
                                cx="80" cy="80" r="74" fill="transparent"
                                stroke="{{ $nilaiColor }}" stroke-width="12"
                                stroke-linecap="round"
                                stroke-dasharray="{{ $circ }}"
                                stroke-dashoffset="{{ $circ }}"/>
                    </svg>
                    <div class="flex flex-col items-center" id="nilai-angka">
                        <span class="text-5xl font-black leading-none" style="color:{{ $nilaiColor }}">
                            {{ $nilai }}
                        </span>
                        <div class="h-px w-8 bg-slate-200 my-1"></div>
                        <span class="text-slate-400 text-lg font-bold">100</span>
                    </div>
                </div>
            </div>

            {{-- Poin banner --}}
            <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700
                        px-5 py-2.5 rounded-full flex items-center justify-center gap-2 mb-4">
                <span class="material-symbols-outlined text-amber-500 text-xl"
                      style="font-variation-settings:'FILL' 1">workspace_premium</span>
                <span class="text-amber-700 dark:text-amber-400 font-extrabold text-lg">
                    +{{ $percobaan->poin_diperoleh }} Poin!
                </span>
            </div>

            {{-- Badge nilai sempurna --}}
            @if ($nilai === 100)
            <div class="flex items-center justify-center gap-2
                        bg-primary/10 border border-primary/30 px-4 py-2 rounded-xl mb-5 animate-pop-in">
                <span class="material-symbols-outlined text-primary text-lg">workspace_premium</span>
                <span class="text-primary font-extrabold text-sm">🎯 Nilai Sempurna!</span>
            </div>
            @endif

            {{-- Stat 3 kotak --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="flex flex-col items-center bg-emerald-50 dark:bg-emerald-900/20
                            p-3 rounded-2xl border border-emerald-100 dark:border-emerald-800">
                    <span class="text-2xl font-extrabold text-emerald-600">✅ {{ $percobaan->jumlah_benar }}</span>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wide mt-0.5">Benar</span>
                </div>
                <div class="flex flex-col items-center bg-red-50 dark:bg-red-900/20
                            p-3 rounded-2xl border border-red-100 dark:border-red-800">
                    <span class="text-2xl font-extrabold text-red-500">❌ {{ $percobaan->jumlah_salah }}</span>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wide mt-0.5">Salah</span>
                </div>
                <div class="flex flex-col items-center bg-slate-50 dark:bg-slate-700/50
                            p-3 rounded-2xl border border-slate-100 dark:border-slate-700">
                    <span class="text-xl font-extrabold text-slate-700 dark:text-slate-200">⏱ {{ $waktuFmt }}</span>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wide mt-0.5">Waktu</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tombol aksi ── --}}
    <div class="px-5 mt-5 flex flex-col gap-3 animate-fade-up" style="animation-delay:.2s">
        {{-- Lihat Pembahasan --}}
        <button onclick="togglePembahasan(this)"
                class="w-full h-14 bg-primary text-white rounded-full font-extrabold text-base
                       shadow-[0_6px_0_0_#064e3b] active:shadow-none active:translate-y-1.5
                       transition-all flex items-center justify-center gap-2">
            <span>Lihat Pembahasan</span>
            <span class="material-symbols-outlined text-xl" id="penj-icon">expand_more</span>
        </button>

        <a href="{{ route('siswa.dashboard') }}"
           class="w-full h-14 border-2 border-primary text-primary rounded-full
                  font-extrabold text-base hover:bg-primary/5 transition-colors
                  flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-xl">home</span>
            Kembali ke Beranda
        </a>

        <a href="{{ route('siswa.leaderboard') }}"
           class="w-full h-14 bg-amber-400 hover:bg-amber-500 text-white rounded-full
                  font-extrabold text-base shadow-[0_5px_0_0_#b4791a]
                  active:shadow-none active:translate-y-1 transition-all
                  flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-xl">leaderboard</span>
            Cek Peringkat
        </a>
    </div>

    {{-- ── Pembahasan ── --}}
    <div id="pembahasan-wrapper" class="px-5 mt-2">
        <div class="space-y-3 pt-2">
            @foreach ($percobaan->jawabanPercobaan as $idx => $jawaban)
            @php
                $soal   = $jawaban->soal;
                $labels = ['a'=>'A','b'=>'B','c'=>'C','d'=>'D'];
            @endphp
            <div class="pembahasan-item {{ $jawaban->benar ? 'benar' : 'salah' }}
                        bg-white dark:bg-slate-800 rounded-2xl p-4
                        shadow-sm border border-slate-100 dark:border-slate-700">

                {{-- Nomor + status --}}
                <div class="flex items-center gap-2 mb-2">
                    <span class="size-7 rounded-full flex items-center justify-center text-xs font-extrabold
                                 {{ $jawaban->benar ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                        {{ $idx + 1 }}
                    </span>
                    <span class="text-xs font-extrabold uppercase tracking-widest
                                 {{ $jawaban->benar ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $jawaban->benar ? '✓ Benar' : '✗ Salah' }}
                    </span>
                    @if ($jawaban->benar)
                    <span class="ml-auto text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                        +{{ $jawaban->poin_diperoleh }} poin
                    </span>
                    @endif
                </div>

                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2 leading-snug">
                    {{ $soal->teks_soal }}
                </p>

                {{-- Jawaban kamu vs benar --}}
                @if (!$jawaban->benar)
                <div class="flex gap-2 flex-wrap text-xs mb-2">
                    <span class="bg-red-50 text-red-600 border border-red-200 px-2.5 py-1 rounded-full font-bold">
                        Kamu: {{ $labels[$jawaban->jawaban_dipilih] ?? '–' }}
                    </span>
                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full font-bold">
                        Benar: {{ $labels[$soal->jawaban_benar] }}
                    </span>
                </div>
                @endif

                {{-- Penjelasan --}}
                @if ($soal->penjelasan)
                <p class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-700/50
                          px-3 py-2 rounded-xl leading-relaxed font-medium">
                    💡 {{ $soal->penjelasan }}
                </p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Confetti container --}}
<div id="confetti-container" class="pointer-events-none fixed inset-0 z-50 overflow-hidden"></div>
@endsection

@push('scripts')
<script>
const NILAI = {{ $nilai }};
const CIRC  = {{ $circ }};

// Animasi ring nilai setelah halaman load
window.addEventListener('load', () => {
    setTimeout(() => {
        const ring   = document.getElementById('nilai-ring');
        const offset = CIRC * (1 - NILAI / 100);
        ring.style.strokeDashoffset = offset;
    }, 300);

    if (NILAI >= 75) spawnConfetti(NILAI >= 90 ? 50 : 25);
});

// Toggle pembahasan
function togglePembahasan(btn) {
    const wrapper = document.getElementById('pembahasan-wrapper');
    const icon    = document.getElementById('penj-icon');
    const open    = wrapper.classList.toggle('open');
    icon.textContent = open ? 'expand_less' : 'expand_more';
    if (open) {
        setTimeout(() => wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
    }
}

// Confetti
function spawnConfetti(count = 30) {
    const colors  = ['#0d6e55','#f59e0b','#3b82f6','#ec4899','#10b981','#f97316','#a855f7'];
    const container = document.getElementById('confetti-container');
    for (let i = 0; i < count; i++) {
        const el = document.createElement('div');
        el.className = 'confetti-piece';
        el.style.cssText = `
            left:${5 + Math.random() * 90}vw;
            top:${5 + Math.random() * 40}vh;
            background:${colors[Math.floor(Math.random() * colors.length)]};
            animation-delay:${Math.random() * .6}s;
            animation-duration:${1 + Math.random() * .8}s;
            transform:rotate(${Math.random()*360}deg);
            border-radius:${Math.random() > .5 ? '50%' : '3px'};
        `;
        container.appendChild(el);
        setTimeout(() => el.remove(), 2000);
    }
}
</script>
@endpush

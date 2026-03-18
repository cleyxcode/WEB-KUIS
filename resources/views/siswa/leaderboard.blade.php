@extends('siswa.layouts.siswa')
@php $pageTitle = 'Papan Peringkat'; @endphp

@push('styles')
<style>
.podium-shadow { box-shadow: 0 10px 30px -5px rgba(13,110,85,.2); }
.no-scrollbar::-webkit-scrollbar { display:none; }
.no-scrollbar { -ms-overflow-style:none; scrollbar-width:none; }

/* Lottie di atas avatar podium */
.podium-lottie {
    position: absolute;
    top: -52px; left: 50%; transform: translateX(-50%);
    width: 72px;
    height: 72px;
    pointer-events: none;
}
.podium-lottie dotlottie-wc {
    width: 100%; height: 100%;
}
/* Glow ring rank 1 */
.rank1-glow {
    box-shadow: 0 0 0 3px #f59e0b,
                0 0 20px 6px rgba(245,158,11,.4),
                0 0 40px 10px rgba(245,158,11,.15);
    animation: glow-pulse 2s ease-in-out infinite;
}
@keyframes glow-pulse {
    0%,100% { box-shadow: 0 0 0 3px #f59e0b, 0 0 20px 6px rgba(245,158,11,.4); }
    50%      { box-shadow: 0 0 0 3px #f59e0b, 0 0 28px 10px rgba(245,158,11,.55); }
}
/* Glow ring rank 2 */
.rank2-glow {
    box-shadow: 0 0 0 3px #94a3b8,
                0 0 14px 4px rgba(148,163,184,.35);
}
/* Efek shine pada rank 1 */
.rank1-card {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 40%, #f59e0b 100%);
    background-size: 200% 200%;
    animation: gold-shimmer 3s ease infinite;
}
@keyframes gold-shimmer {
    0%   { background-position: 0% 50%; }
    50%  { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
/* Animasi masuk list item */
.rank-item { animation: slide-in .35s ease both; }
@keyframes slide-in {
    from { opacity:0; transform:translateX(-16px); }
    to   { opacity:1; transform:translateX(0); }
}
</style>
@endpush

@section('page-content')

{{-- ══ HERO HEADER ══════════════════════════════════════════ --}}
<div class="bg-primary rounded-2xl p-5 md:p-6 relative overflow-hidden mb-5">
    <div class="absolute inset-0 opacity-10"
         style="background-image:radial-gradient(#fff 1px,transparent 1px);background-size:20px 20px;"></div>
    <div class="relative z-10 text-center py-1">
        <h1 class="text-white text-xl md:text-2xl font-extrabold">🏆 Papan Peringkat</h1>
        <p class="text-white/70 text-xs font-semibold mt-1">Top 50 siswa terbaik</p>
    </div>
</div>

{{-- ══ PODIUM TOP 3 ══════════════════════════════════════════ --}}
@php
    $top3 = $peringkat->take(3);
    $p1   = $top3->firstWhere('rank', 1);
    $p2   = $top3->firstWhere('rank', 2);
    $p3   = $top3->firstWhere('rank', 3);
@endphp

<div class="mb-5 px-2 animate-fade-up max-w-sm md:max-w-md mx-auto">
    <div class="flex items-end justify-center gap-2">

        {{-- Rank 2 --}}
        @if ($p2)
        <div class="flex flex-col items-center flex-1">
            {{-- Avatar + Lottie perak di atas --}}
            <div class="relative mb-2">
                {{-- Lottie juara 2 --}}
                <div class="podium-lottie">
                    <dotlottie-wc
                        src="https://lottie.host/4a2bd979-240e-4c3e-9142-4c653c640631/ePj7Eiqj9m.lottie"
                        autoplay loop>
                    </dotlottie-wc>
                </div>
                <div class="size-16 rounded-full border-4 border-slate-300 bg-white
                            overflow-hidden shadow-md rank2-glow mt-12 shrink-0">
                    @if ($p2->foto)
                        <img src="{{ asset('storage/' . $p2->foto) }}" class="w-full h-full object-cover"/>
                    @else
                        <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                            <span class="text-slate-500 font-extrabold text-xl">
                                {{ strtoupper(substr($p2->nama_panggilan, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="w-full bg-white dark:bg-slate-800 pt-3 pb-6 rounded-t-2xl text-center
                        border-x border-t border-slate-200 dark:border-slate-700 podium-shadow">
                <p class="text-xs font-extrabold text-slate-600 dark:text-slate-300 truncate px-1">
                    {{ $p2->nama_panggilan }}
                </p>
                <p class="text-sm font-extrabold text-slate-500 mt-0.5">
                    🥈 {{ number_format($p2->total_poin) }}
                </p>
                @if ($p2->is_me)
                <span class="text-[9px] bg-amber-100 text-amber-700 font-bold px-1.5 rounded-full">KAMU</span>
                @endif
            </div>
        </div>
        @endif

        {{-- Rank 1 --}}
        @if ($p1)
        <div class="flex flex-col items-center flex-1 z-10 -mb-2">
            {{-- Avatar + Lottie mahkota di atas --}}
            <div class="relative mb-2">
                {{-- Lottie juara 1 --}}
                <div class="podium-lottie" style="top:-60px; width:clamp(72px,20vw,90px); height:clamp(72px,20vw,90px);">
                    <dotlottie-wc
                        src="https://lottie.host/65e0d4df-5360-4390-b9d6-6bf1c08f9e0f/dlWuVg4Gql.lottie"
                        autoplay loop>
                    </dotlottie-wc>
                </div>
                <div class="size-20 rounded-full border-4 border-amber-400 bg-white
                            overflow-hidden shadow-xl scale-110 shrink-0 rank1-glow mt-14">
                    @if ($p1->foto)
                        <img src="{{ asset('storage/' . $p1->foto) }}" class="w-full h-full object-cover"/>
                    @else
                        <div class="w-full h-full bg-amber-50 flex items-center justify-center">
                            <span class="text-amber-600 font-extrabold text-2xl">
                                {{ strtoupper(substr($p1->nama_panggilan, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="w-full bg-white dark:bg-slate-800 pt-5 pb-10 rounded-t-2xl text-center
                        border-x border-t border-amber-200 dark:border-amber-800 shadow-xl">
                <p class="text-sm font-extrabold text-primary truncate px-1">
                    {{ $p1->nama_panggilan }}
                </p>
                <p class="text-base font-extrabold text-amber-500 mt-0.5">
                    🥇 {{ number_format($p1->total_poin) }}
                </p>
                @if ($p1->is_me)
                <span class="text-[9px] bg-amber-100 text-amber-700 font-bold px-1.5 rounded-full">KAMU</span>
                @endif
            </div>
        </div>
        @endif

        {{-- Rank 3 --}}
        @if ($p3)
        <div class="flex flex-col items-center flex-1">
            <div class="size-16 rounded-full border-4 border-orange-400 bg-white
                        overflow-hidden shadow-md mb-2 shrink-0">
                @if ($p3->foto)
                    <img src="{{ asset('storage/' . $p3->foto) }}" class="w-full h-full object-cover"/>
                @else
                    <div class="w-full h-full bg-orange-50 flex items-center justify-center">
                        <span class="text-orange-500 font-extrabold text-xl">
                            {{ strtoupper(substr($p3->nama_panggilan, 0, 1)) }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="w-full bg-white dark:bg-slate-800 pt-3 pb-4 rounded-t-2xl text-center
                        border-x border-t border-slate-200 dark:border-slate-700 podium-shadow">
                <p class="text-xs font-extrabold text-slate-600 dark:text-slate-300 truncate px-1">
                    {{ $p3->nama_panggilan }}
                </p>
                <p class="text-sm font-extrabold text-primary mt-0.5">
                    🥉 {{ number_format($p3->total_poin) }}
                </p>
                @if ($p3->is_me)
                <span class="text-[9px] bg-amber-100 text-amber-700 font-bold px-1.5 rounded-full">KAMU</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ══ BANNER POSISI SAYA ═══════════════════════════════════ --}}
<div class="mb-4 animate-fade-up" style="animation-delay:.1s">
    <div class="bg-amber-50 dark:bg-amber-900/20
                border-2 border-amber-300 dark:border-amber-700
                p-4 rounded-2xl flex items-center gap-4">
        <div class="size-14 bg-amber-400/20 rounded-full flex items-center justify-center
                    border-2 border-amber-400 shrink-0">
            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-3xl"
                  style="font-variation-settings:'FILL' 1">trending_up</span>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-amber-900 dark:text-amber-100 font-extrabold text-base leading-tight">
                Kamu di Peringkat #{{ $rankSaya }}
            </h3>
            <p class="text-amber-700 dark:text-amber-300 text-xs mt-0.5 font-semibold">
                {{ number_format($siswaLogin->total_poin) }} poin
                @if ($poinKeRankAtas)
                · Butuh <strong>{{ number_format($poinKeRankAtas) }}</strong> poin lagi ke #{{ $rankSaya - 1 }} ✨
                @else
                · Kamu di puncak! 🔥
                @endif
            </p>
        </div>
    </div>
</div>

{{-- ══ DAFTAR PERINGKAT (rank 4+) ══════════════════════════ --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-2 pb-4">
    @foreach ($peringkat->skip(3) as $siswa)
    @php $delay = ($loop->index * 0.04) . 's'; @endphp

    @if ($siswa->is_me)
    {{-- Highlight diri sendiri --}}
    <div class="rank1-card rank-item p-4 rounded-2xl flex items-center gap-3 shadow-lg scale-[1.01]"
         style="animation-delay:{{ $delay }}">
        <span class="w-7 text-center font-extrabold text-amber-900 shrink-0">{{ $siswa->rank }}</span>

        <div class="size-11 rounded-full bg-white/40 border-2 border-white
                    flex items-center justify-center overflow-hidden shrink-0">
            @if ($siswa->foto)
                <img src="{{ asset('storage/' . $siswa->foto) }}" class="w-full h-full object-cover"/>
            @else
                <span class="font-extrabold text-amber-900 text-lg">
                    {{ strtoupper(substr($siswa->nama_panggilan, 0, 1)) }}
                </span>
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <p class="font-extrabold text-amber-900 truncate text-sm">
                {{ $siswa->nama_panggilan }}
                <span class="text-xs font-bold opacity-70">(Kamu)</span>
            </p>
            <p class="text-amber-800 text-[10px] font-semibold">
                {{ $siswa->kelas }} · 🏅 {{ $siswa->lencana_count }}
            </p>
        </div>

        <div class="text-right shrink-0">
            <p class="font-extrabold text-amber-950 text-sm">{{ number_format($siswa->total_poin) }}</p>
            <p class="text-amber-800 text-[10px] font-bold">poin</p>
        </div>
    </div>

    @else
    {{-- Siswa lain --}}
    <div class="rank-item bg-white dark:bg-slate-800
                p-4 rounded-2xl flex items-center gap-3
                shadow-sm border border-slate-100 dark:border-slate-700
                hover:shadow-md hover:border-primary/20 transition-all"
         style="animation-delay:{{ $delay }}">

        <span class="w-7 text-center font-extrabold shrink-0
                     {{ $siswa->rank <= 10 ? 'text-primary' : 'text-slate-400' }}">
            {{ $siswa->rank }}
        </span>

        <div class="size-11 rounded-full bg-primary/10 dark:bg-primary/20
                    flex items-center justify-center overflow-hidden shrink-0 border border-primary/20">
            @if ($siswa->foto)
                <img src="{{ asset('storage/' . $siswa->foto) }}" class="w-full h-full object-cover"/>
            @else
                <span class="font-extrabold text-primary text-lg">
                    {{ strtoupper(substr($siswa->nama_panggilan, 0, 1)) }}
                </span>
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <p class="font-semibold text-slate-800 dark:text-slate-100 truncate text-sm">
                {{ $siswa->nama_panggilan }}
            </p>
            <p class="text-slate-400 text-[10px] font-semibold">
                {{ $siswa->kelas }} · 🏅 {{ $siswa->lencana_count }}
                @if ($siswa->streak_sekarang > 0)
                · 🔥 {{ $siswa->streak_sekarang }}
                @endif
            </p>
        </div>

        <div class="text-right shrink-0">
            <p class="font-extrabold text-primary text-sm">{{ number_format($siswa->total_poin) }}</p>
            <p class="text-slate-400 text-[10px] font-bold">poin</p>
        </div>
    </div>
    @endif

    @endforeach

    @if ($peringkat->count() === 0)
    <div class="text-center py-12 text-slate-400">
        <span class="material-symbols-outlined text-5xl block mb-2">emoji_events</span>
        <p class="font-bold">Belum ada data peringkat.</p>
    </div>
    @endif
</div>

@endsection

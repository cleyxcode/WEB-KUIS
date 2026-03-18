@extends('siswa.layouts.app')
@section('title', ($pageTitle ?? 'Dashboard') . ' — GAS-IPAS')

@section('content')

@php
$navItems = [
    ['route' => 'siswa.dashboard',   'icon' => 'home',           'label' => 'Beranda'],
    ['route' => 'siswa.kuis.index',  'icon' => 'sports_esports', 'label' => 'Kuis'],
    ['route' => 'siswa.leaderboard', 'icon' => 'leaderboard',    'label' => 'Peringkat'],
    ['route' => 'siswa.profil',      'icon' => 'person',         'label' => 'Profil'],
];
$currentRoute = request()->route()->getName();
@endphp

<div class="min-h-screen bg-slate-100 dark:bg-slate-950 font-display md:flex">

    {{-- ════════════════════════════════════════
         DESKTOP SIDEBAR (hidden on mobile)
    ════════════════════════════════════════ --}}
    <aside class="hidden md:flex flex-col fixed left-0 top-0 h-full w-64
                  bg-primary text-white z-40 shadow-2xl">

        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-xl text-white" style="font-variation-settings:'FILL' 1">science</span>
                </div>
                <div>
                    <p class="font-black text-base leading-tight tracking-tight">GAS-IPAS</p>
                    <p class="text-white/50 text-[10px] font-bold uppercase tracking-widest">Belajar Seru!</p>
                </div>
            </div>
        </div>

        {{-- Siswa info --}}
        <div class="px-4 py-4 border-b border-white/10">
            <div class="flex items-center gap-3 bg-white/10 rounded-xl p-3">
                <div class="size-10 rounded-full bg-white/20 overflow-hidden shrink-0 flex items-center justify-center font-black text-sm">
                    @if ($currentSiswa->foto)
                        <img src="{{ asset('storage/' . $currentSiswa->foto) }}" alt="" class="w-full h-full object-cover"/>
                    @else
                        {{ strtoupper(substr($currentSiswa->nama_panggilan, 0, 1)) }}
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm truncate">{{ $currentSiswa->nama_panggilan }}</p>
                    <div class="flex items-center gap-1 mt-0.5">
                        <span class="material-symbols-outlined text-amber-300 text-sm" style="font-variation-settings:'FILL' 1">local_fire_department</span>
                        <span class="text-white/70 text-xs font-semibold">{{ $currentSiswa->streak_sekarang }} hari streak</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @foreach ($navItems as $item)
                @php $active = $currentRoute === $item['route']; @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200
                          {{ $active
                              ? 'bg-white text-primary font-black shadow-lg'
                              : 'text-white/70 hover:bg-white/10 hover:text-white font-bold' }}">
                    <span class="material-symbols-outlined text-xl"
                          style="{{ $active ? "font-variation-settings:'FILL' 1" : '' }}">
                        {{ $item['icon'] }}
                    </span>
                    <span class="text-sm">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        {{-- Logout --}}
        <div class="px-4 py-4 border-t border-white/10">
            <form method="POST" action="{{ route('siswa.logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl w-full
                               text-white/60 hover:text-white hover:bg-white/10 transition-all font-bold text-sm">
                    <span class="material-symbols-outlined text-xl">logout</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ════════════════════════════════════════
         MAIN AREA
    ════════════════════════════════════════ --}}
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">

        {{-- ── Mobile Header ── --}}
        <header class="md:hidden flex items-center justify-between px-5 pt-8 pb-4 bg-slate-100 dark:bg-slate-950">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-full border-2 border-primary bg-primary/10 overflow-hidden shrink-0">
                    @if ($currentSiswa->foto)
                        <img src="{{ asset('storage/' . $currentSiswa->foto) }}" alt="" class="w-full h-full object-cover"/>
                    @else
                        <div class="w-full h-full bg-primary/20 flex items-center justify-center">
                            <span class="text-primary font-extrabold text-sm">
                                {{ strtoupper(substr($currentSiswa->nama_panggilan, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-primary/60 text-[10px] font-bold uppercase tracking-widest">Selamat Datang</p>
                    <h2 class="text-sm font-bold leading-tight">Halo, {{ $currentSiswa->nama_panggilan }}! 👋</h2>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="bg-amber-400/20 px-2.5 py-1 rounded-full flex items-center gap-1 border border-amber-400/30">
                    <span class="material-symbols-outlined text-amber-500 text-base" style="font-variation-settings:'FILL' 1">local_fire_department</span>
                    <span class="text-amber-500 font-extrabold text-xs">{{ $currentSiswa->streak_sekarang }}</span>
                </div>
                <form method="POST" action="{{ route('siswa.logout') }}">
                    @csrf
                    <button type="submit" class="size-8 rounded-full bg-white flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors shadow-sm border border-slate-100">
                        <span class="material-symbols-outlined text-base">logout</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- ── Desktop Top Bar ── --}}
        <header class="hidden md:flex items-center justify-between px-8 py-4 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30">
            <div>
                <p class="text-primary/60 text-[10px] font-bold uppercase tracking-widest">Selamat Datang</p>
                <h2 class="text-base font-bold leading-tight text-slate-800 dark:text-white">
                    Halo, {{ $currentSiswa->nama_panggilan }}! 👋
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-amber-400/15 px-4 py-2 rounded-full flex items-center gap-2 border border-amber-400/30">
                    <span class="material-symbols-outlined text-amber-500 text-lg" style="font-variation-settings:'FILL' 1">local_fire_department</span>
                    <span class="text-amber-500 font-extrabold text-sm">{{ $currentSiswa->streak_sekarang }} hari</span>
                </div>
                <div class="size-9 rounded-full bg-primary/10 border-2 border-primary overflow-hidden flex items-center justify-center font-black text-primary text-sm">
                    @if ($currentSiswa->foto)
                        <img src="{{ asset('storage/' . $currentSiswa->foto) }}" alt="" class="w-full h-full object-cover"/>
                    @else
                        {{ strtoupper(substr($currentSiswa->nama_panggilan, 0, 1)) }}
                    @endif
                </div>
            </div>
        </header>

        {{-- ── Page Content ── --}}
        <main class="flex-1 py-4 md:py-6 pb-28 md:pb-10">
            <div class="max-w-5xl mx-auto px-4 md:px-8 space-y-5 md:space-y-6">
                @yield('page-content')
            </div>
        </main>
    </div>

    {{-- ════════════════════════════════════════
         MOBILE BOTTOM NAV
    ════════════════════════════════════════ --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50
                bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg
                border-t border-primary/10 px-4 pb-5 pt-2 shadow-2xl">
        <div class="flex justify-around items-center max-w-md mx-auto">
            @foreach ($navItems as $item)
                @php $active = $currentRoute === $item['route']; @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex flex-col items-center gap-0.5 group min-w-[56px]">
                    <div class="size-10 rounded-full flex items-center justify-center transition-all duration-200
                                {{ $active ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-slate-400 group-hover:bg-primary/10 group-hover:text-primary' }}">
                        <span class="material-symbols-outlined text-[22px]"
                              style="{{ $active ? "font-variation-settings:'FILL' 1" : '' }}">
                            {{ $item['icon'] }}
                        </span>
                    </div>
                    <span class="text-[9px] font-bold uppercase tracking-tight transition-colors
                                 {{ $active ? 'text-primary' : 'text-slate-400 group-hover:text-primary' }}">
                        {{ $item['label'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </nav>
</div>

@push('scripts')
<script>window.__siswaId = {{ session('siswa_id') ?? 'null' }};</script>
@endpush

@endsection

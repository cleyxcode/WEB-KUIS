@extends('siswa.layouts.app')
@section('title', $materi->judul . ' — GAS-IPAS')

@push('styles')
<style>
/* Konten materi dari RichEditor */
.konten-materi h2 { font-size: 1.25rem; font-weight: 800; color: #0d6e55; margin: 1.5rem 0 .75rem; }
.konten-materi h3 { font-size: 1.1rem;  font-weight: 700; color: #0d6e55; margin: 1.25rem 0 .5rem; }
.konten-materi p  { margin-bottom: .9rem; line-height: 1.75; }
.konten-materi ul { list-style: disc; padding-left: 1.5rem; margin-bottom: .9rem; }
.konten-materi ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: .9rem; }
.konten-materi li { margin-bottom: .3rem; }
.konten-materi blockquote {
    border-left: 4px solid #0d6e55;
    background: #f0faf6;
    padding: .75rem 1rem;
    border-radius: 0 .75rem .75rem 0;
    margin: 1rem 0;
    font-style: italic;
    color: #334155;
}
.konten-materi strong { color: #0f172a; }
.konten-materi a { color: #0d6e55; text-decoration: underline; }

/* Hero image */
.hero-img-wrap { height: 240px; }
@media (min-width: 768px) { .hero-img-wrap { height: 320px; } }
</style>
@endpush

@section('content')
@php
    $jenis = $materi->mataPelajaran?->jenis ?? 'IPA';
    $isIPA = $jenis === 'IPA';
    $bannerColors = $isIPA ? 'from-primary/80 to-emerald-600' : 'from-blue-500 to-indigo-600';
@endphp

<div class="relative flex flex-col min-h-screen max-w-2xl mx-auto bg-white overflow-x-hidden">

    {{-- ══ HERO BANNER ════════════════════════════════════════ --}}
    <div class="relative hero-img-wrap w-full overflow-hidden bg-gradient-to-br {{ $bannerColors }}">

        @if ($materi->gambar_sampul)
        <img src="{{ asset('storage/' . $materi->gambar_sampul) }}"
             alt="{{ $materi->judul }}"
             class="absolute inset-0 w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        @else
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_2px_2px,rgba(255,255,255,0.1)_1px,transparent_0)] bg-[length:20px_20px]"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <span class="material-symbols-outlined text-white/15" style="font-size:8rem">auto_stories</span>
        </div>
        @endif

        {{-- Top bar over hero --}}
        <div class="absolute top-0 left-0 right-0 flex items-center justify-between p-4 z-10">
            <a href="{{ route('siswa.materi.index') }}"
               class="size-10 flex items-center justify-center rounded-full bg-black/25 backdrop-blur-sm text-white
                      hover:bg-black/40 transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            @if ($materi->file_pdf)
            <a href="{{ asset('storage/' . $materi->file_pdf) }}" target="_blank"
               class="flex items-center gap-2 bg-black/25 backdrop-blur-sm text-white px-3 py-2 rounded-full text-xs font-bold
                      hover:bg-black/40 transition-colors">
                <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                Unduh PDF
            </a>
            @endif
        </div>

        {{-- Poin badge --}}
        @if (!$sudahDibaca)
        <div class="absolute bottom-4 right-4 z-10">
            <div class="flex items-center gap-1.5 bg-amber-400 text-white px-4 py-2 rounded-full shadow-lg border-2 border-white/30">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">stars</span>
                <span class="text-sm font-extrabold">+3 Poin</span>
            </div>
        </div>
        @else
        <div class="absolute bottom-4 right-4 z-10">
            <div class="flex items-center gap-1.5 bg-emerald-500 text-white px-4 py-2 rounded-full shadow-lg border-2 border-white/30">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">check_circle</span>
                <span class="text-sm font-extrabold">Sudah Dibaca</span>
            </div>
        </div>
        @endif
    </div>

    {{-- ══ KONTEN CARD ════════════════════════════════════════ --}}
    <div class="relative -mt-6 z-10 bg-white rounded-t-[28px] px-5 pt-7 pb-32 md:px-8">

        {{-- Meta badges --}}
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold
                         {{ $isIPA ? 'bg-primary/10 text-primary' : 'bg-blue-50 text-blue-700' }}">
                <span class="material-symbols-outlined text-sm">{{ $isIPA ? 'science' : 'public' }}</span>
                {{ $materi->mataPelajaran?->nama ?? $jenis }}
            </span>
            @if ($materi->bab)
            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                {{ $materi->bab }}
            </span>
            @endif
            <span class="flex items-center gap-1 text-slate-400 text-xs font-medium ml-auto">
                <span class="material-symbols-outlined text-sm text-primary">schedule</span>
                ~5 menit
            </span>
        </div>

        {{-- Judul --}}
        <h1 class="text-2xl font-extrabold text-slate-900 leading-tight mb-4">
            {{ $materi->judul }}
        </h1>

        {{-- Deskripsi --}}
        @if ($materi->deskripsi)
        <div class="mb-6 p-4 bg-primary/5 border-l-4 border-primary rounded-r-xl">
            <p class="text-slate-700 italic text-sm leading-relaxed">
                "{{ $materi->deskripsi }}"
            </p>
        </div>
        @endif

        {{-- Konten rich text --}}
        @if ($materi->konten)
        <div class="konten-materi text-slate-800 text-base mb-6">
            {!! $materi->konten !!}
        </div>
        @else
        <div class="py-8 text-center text-slate-400">
            <span class="material-symbols-outlined text-5xl mb-2">article</span>
            <p class="text-sm font-medium">Konten materi belum tersedia.</p>
        </div>
        @endif

        {{-- Download PDF --}}
        @if ($materi->file_pdf)
        <a href="{{ asset('storage/' . $materi->file_pdf) }}" target="_blank"
           class="flex items-center gap-3 p-4 bg-white rounded-2xl border-2 border-dashed border-primary/30
                  hover:bg-primary/5 transition-colors group mt-6">
            <div class="size-12 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-red-500 text-2xl">picture_as_pdf</span>
            </div>
            <div class="flex-1">
                <p class="font-bold text-primary text-sm">Unduh Materi PDF</p>
                <p class="text-xs text-slate-400">Pelajari secara offline</p>
            </div>
            <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors">download</span>
        </a>
        @endif
    </div>

    {{-- ══ STICKY BOTTOM — KLAIM POIN ════════════════════════ --}}
    <div class="fixed bottom-0 left-0 right-0 z-50 max-w-2xl mx-auto
                px-4 pb-6 pt-3 bg-white/95 backdrop-blur-md border-t border-slate-100 shadow-2xl">
        @if ($sudahDibaca)
        <div class="w-full h-14 bg-emerald-50 border-2 border-emerald-200 rounded-full
                    flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-emerald-500" style="font-variation-settings:'FILL' 1">check_circle</span>
            <span class="font-extrabold text-emerald-600">Sudah Dibaca — Poin Diklaim!</span>
        </div>
        @else
        <form method="POST" action="{{ route('siswa.materi.klaim', $materi->id) }}">
            @csrf
            <button type="submit"
                    class="w-full h-14 bg-primary text-white rounded-full font-extrabold text-base
                           shadow-[0_6px_0_0_#064e3b]
                           active:shadow-none active:translate-y-1.5
                           transition-all duration-150
                           flex items-center justify-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">check_circle</span>
                Sudah Baca! Klaim +3 Poin
                <span class="material-symbols-outlined text-amber-300" style="font-variation-settings:'FILL' 1">auto_awesome</span>
            </button>
        </form>
        @endif
        <a href="{{ route('siswa.materi.index') }}"
           class="mt-2 w-full flex items-center justify-center gap-1 text-xs text-slate-400 font-medium py-1 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Materi
        </a>
    </div>
</div>
@endsection

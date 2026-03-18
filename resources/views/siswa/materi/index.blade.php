@extends('siswa.layouts.siswa')
@php $pageTitle = 'Materi Belajar'; @endphp

@push('styles')
<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

/* Skeleton shimmer */
@keyframes shimmer {
    0%   { background-position: -600px 0; }
    100% { background-position: 600px 0; }
}
.skeleton {
    background: linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%);
    background-size: 1200px 100%;
    animation: shimmer 1.5s infinite;
    border-radius: 1rem;
}
</style>
@endpush

@section('page-content')

{{-- ══ PROGRESS BANNER ════════════════════════════════════════ --}}
<div class="relative overflow-hidden rounded-2xl bg-primary text-white p-5 shadow-lg">
    <div class="absolute right-3 top-3 opacity-10 pointer-events-none">
        <span class="material-symbols-outlined" style="font-size:6rem">auto_stories</span>
    </div>
    <div class="relative z-10">
        <p class="text-white/70 text-xs font-bold uppercase tracking-widest mb-1">📚 Progress Belajar</p>
        @if ($totalMateri > 0)
            <p class="text-xl font-extrabold leading-tight mb-3">
                Kamu sudah baca <span class="text-amber-300">{{ $jumlahDibaca }}</span> dari {{ $totalMateri }} materi
            </p>
            @php $pct = $totalMateri > 0 ? round(($jumlahDibaca / $totalMateri) * 100) : 0; @endphp
            <div class="w-full h-3 bg-white/20 rounded-full overflow-hidden mb-2">
                <div class="h-full bg-amber-300 rounded-full transition-all duration-700" style="width:{{ $pct }}%"></div>
            </div>
            <div class="flex items-center justify-between">
                <p class="text-white/70 text-xs font-semibold">⭐ +3 Poin untuk setiap materi baru!</p>
                <span class="text-white/80 text-xs font-extrabold">{{ $pct }}%</span>
            </div>
        @else
            <p class="text-xl font-extrabold">Belum ada materi tersedia</p>
            <p class="text-white/70 text-sm mt-1">Guru belum mempublikasikan materi apapun.</p>
        @endif
    </div>
</div>

{{-- ══ SEARCH ══════════════════════════════════════════════════ --}}
<div class="relative">
    <form id="form-cari" method="GET" action="{{ route('siswa.materi.index') }}" class="relative">
        @if ($jenisFilter)
        <input type="hidden" name="jenis" value="{{ $jenisFilter }}"/>
        @endif
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl pointer-events-none">search</span>
            <input type="text"
                   id="input-cari"
                   name="cari"
                   value="{{ $cari }}"
                   placeholder="Cari judul atau bab materi..."
                   autocomplete="off"
                   class="w-full h-12 pl-11 pr-10 rounded-2xl border border-slate-200 bg-white
                          text-sm font-semibold text-slate-700 placeholder-slate-400
                          focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20
                          transition-all"/>
            @if ($cari)
            <a href="{{ route('siswa.materi.index', $jenisFilter ? ['jenis' => $jenisFilter] : []) }}"
               class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-400 transition-colors">
                <span class="material-symbols-outlined text-xl">close</span>
            </a>
            @endif
        </div>
    </form>
</div>

{{-- ══ FILTER MAPEL ════════════════════════════════════════════ --}}
<div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar -mx-4 px-4">
    <button onclick="filterJenis('')"
            class="filter-btn shrink-0 h-9 flex items-center justify-center px-5 rounded-full text-sm font-bold transition-all
                   {{ !$jenisFilter && !$cari ? 'bg-primary text-white shadow-md shadow-primary/30' : 'bg-white border border-slate-200 text-slate-600 hover:border-primary/40' }}"
            data-jenis="">
        Semua
    </button>
    @foreach ($mapel as $mp)
    <button onclick="filterJenis('{{ $mp->jenis }}')"
            class="filter-btn shrink-0 h-9 flex items-center justify-center px-5 rounded-full text-sm font-bold transition-all
                   {{ $jenisFilter === $mp->jenis ? 'bg-primary text-white shadow-md shadow-primary/30' : 'bg-white border border-slate-200 text-slate-600 hover:border-primary/40' }}"
            data-jenis="{{ $mp->jenis }}">
        {{ $mp->ikon }} {{ $mp->jenis }}
    </button>
    @endforeach
</div>

{{-- ══ HASIL / DAFTAR MATERI ══════════════════════════════════ --}}
<div id="materi-container">
    @if ($cari)
    <p class="text-xs text-slate-500 font-semibold mb-3">
        {{ $materiList->count() }} hasil untuk "<span class="text-primary">{{ $cari }}</span>"
    </p>
    @endif

    @if ($materiList->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <span class="material-symbols-outlined text-slate-300 text-7xl mb-3">search_off</span>
        <p class="text-slate-500 font-bold text-lg">{{ $cari ? 'Tidak ditemukan' : 'Belum ada materi' }}</p>
        <p class="text-slate-400 text-sm mt-1">{{ $cari ? 'Coba kata kunci lain' : 'Coba pilih filter lain' }}</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach ($materiList as $materi)
        @php
            $sudahBacaItem = in_array($materi->id, $sudahBaca);
            $jenis    = $materi->mataPelajaran?->jenis ?? 'IPA';
            $isIPA    = $jenis === 'IPA';
            $grads    = $isIPA
                ? ['from-primary/80 to-emerald-600','from-emerald-500 to-teal-700','from-teal-500 to-primary']
                : ['from-blue-500 to-indigo-600','from-indigo-500 to-purple-600','from-sky-500 to-blue-700'];
            $bannerClass = $grads[$materi->id % 3];
            $bannerIcon  = $isIPA
                ? ['psychology','pulmonology','science','biotech'][$materi->id % 4]
                : ['map','diversity_2','language','public'][$materi->id % 4];
        @endphp

        <a href="{{ route('siswa.materi.show', $materi->id) }}"
           class="group flex flex-col bg-white rounded-2xl overflow-hidden shadow-sm border
                  {{ $sudahBacaItem
                      ? ($isIPA ? 'border-emerald-200' : 'border-blue-200')
                      : 'border-slate-100 hover:border-primary/20 hover:shadow-lg' }}
                  hover:-translate-y-0.5 active:scale-[.98] transition-all duration-200">

            {{-- Banner --}}
            <div class="relative h-36 bg-gradient-to-br {{ $bannerClass }} overflow-hidden">
                <span class="material-symbols-outlined text-white/15 absolute inset-0 m-auto flex items-center justify-center" style="font-size:5rem">{{ $bannerIcon }}</span>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_2px_2px,rgba(255,255,255,0.08)_1px,transparent_0)] bg-[length:16px_16px]"></div>

                @if ($materi->gambar_sampul)
                <img src="{{ asset('storage/'.$materi->gambar_sampul) }}"
                     alt="{{ $materi->judul }}"
                     class="absolute inset-0 w-full h-full object-cover"/>
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                @endif

                {{-- Mapel badge --}}
                <div class="absolute top-3 left-3">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase
                                 {{ $isIPA ? 'bg-white/90 text-emerald-700' : 'bg-white/90 text-blue-700' }}">
                        {{ $materi->mataPelajaran?->ikon }} {{ $jenis }}
                    </span>
                </div>

                {{-- Status badge --}}
                @if ($sudahBacaItem)
                <div class="absolute top-3 right-3 flex items-center gap-1
                            {{ $isIPA ? 'bg-emerald-500' : 'bg-blue-500' }} text-white px-2.5 py-1 rounded-full shadow">
                    <span class="material-symbols-outlined text-[11px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                    <span class="text-[10px] font-extrabold">Dibaca</span>
                </div>
                @else
                <div class="absolute top-3 right-3 bg-amber-400 text-white px-2.5 py-1 rounded-full flex items-center gap-1 shadow">
                    <span class="material-symbols-outlined text-[11px]" style="font-variation-settings:'FILL' 1">stars</span>
                    <span class="text-[10px] font-extrabold">+3 Poin</span>
                </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="p-4 flex flex-col gap-1.5 flex-1">
                @if ($materi->bab)
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">{{ $materi->bab }}</span>
                @endif
                <h3 class="font-extrabold text-slate-900 leading-snug group-hover:text-primary transition-colors line-clamp-2">
                    {{ $materi->judul }}
                </h3>
                @if ($materi->deskripsi)
                <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">{{ $materi->deskripsi }}</p>
                @endif

                <div class="flex items-center justify-between mt-auto pt-2 border-t border-slate-50">
                    <div class="flex items-center gap-2 text-xs text-slate-400">
                        @if ($materi->file_pdf)
                        <span class="flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-sm text-red-400">picture_as_pdf</span> PDF
                        </span>
                        @endif
                        @if ($materi->konten)
                        <span class="flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-sm text-primary">article</span> Konten
                        </span>
                        @endif
                    </div>
                    <span class="flex items-center gap-0.5 text-xs font-bold
                                 {{ $sudahBacaItem ? 'text-emerald-600' : 'text-primary' }}">
                        {{ $sudahBacaItem ? '✓ Selesai' : 'Baca →' }}
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
const ROUTE_MATERI = "{{ route('siswa.materi.index') }}";

// ── Filter tab ──────────────────────────────────────────────────
function filterJenis(jenis) {
    const cari = document.getElementById('input-cari')?.value ?? '';
    const params = new URLSearchParams();
    if (jenis) params.set('jenis', jenis);
    if (cari)  params.set('cari', cari);

    InlineLoader.show('Memuat materi...');
    const url = ROUTE_MATERI + (params.toString() ? '?' + params.toString() : '');
    setTimeout(() => { window.location.href = url; }, 150);
}

// ── Search debounce 450ms ───────────────────────────────────────
const inputCari = document.getElementById('input-cari');
let searchTimer = null;

if (inputCari) {
    inputCari.addEventListener('input', () => {
        clearTimeout(searchTimer);
        const val = inputCari.value.trim();

        searchTimer = setTimeout(() => {
            const jenis = new URLSearchParams(window.location.search).get('jenis') ?? '';
            const params = new URLSearchParams();
            if (jenis) params.set('jenis', jenis);
            if (val)   params.set('cari', val);

            InlineLoader.show('Mencari materi... 🔍');
            const url = ROUTE_MATERI + (params.toString() ? '?' + params.toString() : '');
            setTimeout(() => { window.location.href = url; }, 120);
        }, 450);
    });
}
</script>
@endpush

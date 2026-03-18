@extends('siswa.layouts.app')
@section('title', 'Soal {{ $nomor }}/{{ $total }} — {{ $kuis->judul }}')

@push('styles')
<style>
/* ── Timer SVG ── */
#timer-ring { transition: stroke-dashoffset .9s linear; }

/* ── Opsi animasi ── */
.opsi-btn {
    transition: transform .15s ease, box-shadow .15s ease, border-color .2s, background-color .2s;
    will-change: transform;
}
.opsi-btn:active { transform: scale(.97); }
.opsi-btn.selected  { border-color: #0d6e55; background: #f0faf6; }
.opsi-btn.correct   { border-color: #16a34a; background: #f0fdf4; animation: pop-correct .4s cubic-bezier(.34,1.56,.64,1) forwards; }
.opsi-btn.wrong     { border-color: #ef4444; background: #fff1f2; animation: shake-wrong .4s ease forwards; }

@keyframes pop-correct {
    0%   { transform: scale(1); }
    50%  { transform: scale(1.04); }
    100% { transform: scale(1); }
}
@keyframes shake-wrong {
    0%,100%{ transform: translateX(0); }
    20%    { transform: translateX(-6px); }
    40%    { transform: translateX(6px); }
    60%    { transform: translateX(-4px); }
    80%    { transform: translateX(4px); }
}

/* ── Timer urgent ── */
.timer-urgent #timer-ring { stroke: #ef4444 !important; }
.timer-urgent #timer-text { color: #ef4444; }
.timer-urgent { animation: pulse-red .6s ease infinite alternate; }
@keyframes pulse-red {
    from { filter: drop-shadow(0 0 0 rgba(239,68,68,0)); }
    to   { filter: drop-shadow(0 0 8px rgba(239,68,68,.5)); }
}

/* ── Penjelasan slide-up ── */
#penjelasan-panel {
    transform: translateY(100%);
    transition: transform .35s cubic-bezier(.32,0,.67,0);
}
#penjelasan-panel.open {
    transform: translateY(0);
    transition: transform .35s cubic-bezier(.33,1,.68,1);
}

/* ── Progress bar ── */
#progress-bar { transition: width .6s cubic-bezier(.4,0,.2,1); }

/* ── Confetti ── */
.confetti-piece {
    position: fixed; width: 8px; height: 8px;
    border-radius: 2px; pointer-events: none;
    animation: confetti-fall 1s ease-out forwards;
}
@keyframes confetti-fall {
    0%   { opacity: 1; transform: translateY(0) rotate(0deg) scale(1); }
    100% { opacity: 0; transform: translateY(180px) rotate(540deg) scale(.4); }
}

/* ── Zoom overlay ── */
#img-zoom-overlay {
    display: none; position: fixed; inset: 0; z-index: 200;
    background: rgba(0,0,0,.88); align-items: center; justify-content: center;
}
#img-zoom-overlay.show { display: flex; }
#img-zoom-overlay img { max-width: 95vw; max-height: 90vh; border-radius: 12px; object-fit: contain; }

.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
@php
    $opsiMap  = ['a' => $soal->opsi_a,        'b' => $soal->opsi_b,        'c' => $soal->opsi_c,        'd' => $soal->opsi_d];
    $imgMap   = ['a' => $soal->gambar_opsi_a,  'b' => $soal->gambar_opsi_b, 'c' => $soal->gambar_opsi_c, 'd' => $soal->gambar_opsi_d];
    $labels   = ['A','B','C','D'];
    $waktu    = $kuis->waktu_per_soal;
    $isLast   = $nomor >= $total;
    $sudahJawab = $jawabanSudah !== null;

    // Opsi aktif = yang punya teks atau gambar
    $opsiAktif   = collect($opsiUrutan)->filter(fn($k) => ($opsiMap[$k] ?? '') || ($imgMap[$k] ?? ''));
    // Jika SEMUA opsi aktif punya gambar → tampilkan grid 2x2
    $semuaGambar = $opsiAktif->every(fn($k) => !empty($imgMap[$k]));
@endphp

<div class="relative flex flex-col min-h-screen max-w-2xl mx-auto bg-white overflow-x-hidden">

    {{-- ══ HEADER ════════════════════════════════════════════ --}}
    <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-md
                   flex items-center gap-3 px-4 py-3 border-b border-slate-100 shadow-sm">

        <button onclick="konfirmasiKeluar()"
                class="size-10 flex items-center justify-center rounded-full shrink-0
                       bg-slate-100 text-slate-600
                       hover:bg-red-50 hover:text-red-500 transition-colors">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>

        <span class="text-sm font-extrabold text-slate-700 shrink-0">
            Soal <span class="text-primary">{{ $nomor }}</span>/{{ $total }}
        </span>

        <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
            <div id="progress-bar" class="h-full bg-primary rounded-full"
                 style="width: {{ round(($nomor / $total) * 100) }}%"></div>
        </div>

        <div id="poin-badge"
             class="hidden items-center gap-1 bg-amber-50 border border-amber-200
                    px-2.5 py-1 rounded-full text-xs font-extrabold text-amber-600 shrink-0">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">star</span>
            <span id="poin-text">+0</span>
        </div>
    </header>

    {{-- ══ META + TIMER ════════════════════════════════════════ --}}
    <div class="flex items-center justify-between px-5 py-4 gap-4">
        <div class="flex-1 min-w-0">
            <p class="text-[10px] font-bold uppercase tracking-widest text-primary/60 mb-0.5">
                {{ $kuis->mataPelajaran?->ikon ?? '📚' }} {{ $kuis->mataPelajaran?->nama ?? '' }}
            </p>
            <h2 class="text-base font-extrabold text-slate-800 truncate">{{ $kuis->judul }}</h2>
        </div>

        <div class="relative shrink-0" id="timer-wrapper">
            <svg class="size-20 -rotate-90" viewBox="0 0 96 96">
                <circle cx="48" cy="48" r="40" fill="transparent" stroke="#f1f5f9" stroke-width="8"/>
                <circle id="timer-ring" cx="48" cy="48" r="40" fill="transparent"
                        stroke="#f59e0b" stroke-width="8" stroke-linecap="round"
                        stroke-dasharray="{{ 2 * M_PI * 40 }}" stroke-dashoffset="0"/>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span id="timer-text" class="text-xl font-black leading-none text-slate-800 tabular-nums">{{ $waktu }}</span>
                <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">dtk</span>
            </div>
        </div>
    </div>

    {{-- ══ PERTANYAAN ══════════════════════════════════════════ --}}
    <div class="px-4 mb-4">
        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 shadow-sm">

            @php
                $diffColor = ['mudah'=>'bg-emerald-100 text-emerald-700','sedang'=>'bg-amber-100 text-amber-700','sulit'=>'bg-red-100 text-red-600'];
                $diffIcon  = ['mudah'=>'😊','sedang'=>'🤔','sulit'=>'🔥'];
            @endphp
            <div class="flex items-center gap-2 mb-3">
                <span class="text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded-full
                             {{ $diffColor[$soal->tingkat_kesulitan] ?? 'bg-slate-100 text-slate-500' }}">
                    {{ $diffIcon[$soal->tingkat_kesulitan] ?? '' }} {{ ucfirst($soal->tingkat_kesulitan) }}
                </span>
            </div>

            {{-- Teks soal --}}
            @if ($soal->teks_soal)
            <p class="text-base sm:text-lg font-bold text-slate-900 leading-snug">{{ $soal->teks_soal }}</p>
            @endif

            {{-- Gambar soal --}}
            @if ($soal->gambar_soal)
            <div class="mt-3 rounded-xl overflow-hidden border border-slate-200 bg-white">
                <img src="{{ asset('storage/' . $soal->gambar_soal) }}"
                     alt="Gambar soal"
                     class="w-full max-h-72 object-contain cursor-zoom-in"
                     onclick="zoomGambar(this.src)"/>
                <p class="text-center text-[10px] text-slate-400 py-1 font-medium">🔍 Ketuk gambar untuk perbesar</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ══ PILIHAN JAWABAN ════════════════════════════════════ --}}
    <div class="px-4 pb-4 flex-1" id="opsi-wrapper">

        {{-- ─ MODE GRID 2x2 (semua opsi bergambar) ─ --}}
        @if ($semuaGambar)
        <p class="text-xs text-slate-400 font-semibold text-center mb-3">👇 Pilih gambar yang benar</p>
        <div class="grid grid-cols-2 gap-3">
            @foreach ($opsiUrutan as $i => $key)
            @php $teks = $opsiMap[$key] ?? ''; $gambar = $imgMap[$key] ?? null; @endphp
            @if ($teks || $gambar)
            <button onclick="pilihJawaban('{{ $key }}', this)"
                    data-key="{{ $key }}"
                    @if($sudahJawab) disabled @endif
                    class="opsi-btn w-full rounded-2xl border-2 border-slate-200 bg-white
                           p-3 flex flex-col items-start gap-2
                           hover:border-primary/40 hover:shadow-md
                           focus:outline-none focus:border-primary disabled:cursor-not-allowed
                           {{ $sudahJawab && ($jawabanSudah->jawaban_dipilih ?? '') === $key ? ($jawabanSudah->benar ? 'correct' : 'wrong') : '' }}
                           {{ $sudahJawab && $key === $soal->jawaban_benar && !($jawabanSudah->benar ?? false) ? 'correct' : '' }}">

                <div class="flex items-center justify-between w-full">
                    <span class="size-7 rounded-lg flex items-center justify-center font-extrabold text-xs
                                 bg-slate-100 text-slate-500 label-badge shrink-0">
                        {{ $labels[$i] }}
                    </span>
                    <div class="size-5 rounded-full border-2 border-slate-200
                                flex items-center justify-center text-xs indikator transition-all">
                    </div>
                </div>

                @if ($gambar)
                <div class="w-full rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                    <img src="{{ asset('storage/' . $gambar) }}"
                         alt="Opsi {{ strtoupper($key) }}"
                         class="w-full object-cover"
                         style="height: 90px;"
                         onclick="event.stopPropagation(); zoomGambar(this.src)"/>
                </div>
                @endif

                @if ($teks)
                <span class="text-xs font-semibold text-slate-700 leading-tight opsi-text">{{ $teks }}</span>
                @else
                <span class="opsi-text sr-only">Opsi {{ $labels[$i] }}</span>
                @endif
            </button>
            @endif
            @endforeach
        </div>

        {{-- ─ MODE LIST (teks ± gambar per opsi) ─ --}}
        @else
        <div class="space-y-3">
            @foreach ($opsiUrutan as $i => $key)
            @php $teks = $opsiMap[$key] ?? ''; $gambar = $imgMap[$key] ?? null; @endphp
            @if ($teks || $gambar)
            <button onclick="pilihJawaban('{{ $key }}', this)"
                    data-key="{{ $key }}"
                    @if($sudahJawab) disabled @endif
                    class="opsi-btn w-full text-left rounded-2xl border-2 border-slate-200 bg-white
                           px-4 py-3 flex items-start gap-4
                           hover:border-primary/40 hover:shadow-md
                           focus:outline-none focus:border-primary disabled:cursor-not-allowed
                           {{ $sudahJawab && ($jawabanSudah->jawaban_dipilih ?? '') === $key ? ($jawabanSudah->benar ? 'correct' : 'wrong') : '' }}
                           {{ $sudahJawab && $key === $soal->jawaban_benar && !($jawabanSudah->benar ?? false) ? 'correct' : '' }}">

                <span class="shrink-0 size-9 rounded-xl flex items-center justify-center font-extrabold text-sm
                             bg-slate-100 text-slate-500 label-badge mt-0.5">
                    {{ $labels[$i] }}
                </span>

                <div class="flex-1 min-w-0">
                    {{-- Gambar opsi (jika ada) --}}
                    @if ($gambar)
                    <div class="mb-2 rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                        <img src="{{ asset('storage/' . $gambar) }}"
                             alt="Gambar opsi {{ strtoupper($key) }}"
                             class="w-full max-h-44 object-contain cursor-zoom-in"
                             onclick="event.stopPropagation(); zoomGambar(this.src)"/>
                    </div>
                    @endif
                    {{-- Teks opsi --}}
                    @if ($teks)
                    <span class="text-sm sm:text-base font-semibold text-slate-700 leading-snug opsi-text">{{ $teks }}</span>
                    @else
                    <span class="text-sm font-semibold text-slate-500 italic opsi-text">Lihat gambar di atas</span>
                    @endif
                </div>

                <div class="shrink-0 size-6 rounded-full border-2 border-slate-200
                            flex items-center justify-center indikator transition-all mt-1">
                </div>
            </button>
            @endif
            @endforeach
        </div>
        @endif
    </div>

    {{-- ══ TOMBOL LANJUT ══════════════════════════════════════ --}}
    <div class="sticky bottom-0 px-4 pb-6 pt-3 bg-white/90 backdrop-blur-md border-t border-slate-100">
        <button id="btn-lanjut" onclick="lanjutSoal()" disabled
                class="w-full h-14 bg-primary text-white rounded-full
                       font-extrabold text-lg
                       shadow-[0_6px_0_0_#064e3b]
                       active:shadow-none active:translate-y-1.5
                       transition-all duration-150
                       flex items-center justify-center gap-2
                       disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none">
            <span id="btn-lanjut-text">
                {{ $sudahJawab ? ($isLast ? 'Lihat Hasil 🎉' : 'Soal Berikutnya →') : 'Pilih jawaban dulu...' }}
            </span>
        </button>
    </div>
</div>

{{-- ══ PANEL PENJELASAN ════════════════════════════════════════ --}}
<div id="penjelasan-panel"
     class="fixed bottom-0 left-0 right-0 z-50 max-w-2xl mx-auto
            bg-white rounded-t-3xl shadow-2xl px-6 pt-5 pb-8 border-t-4">
    <div class="w-10 h-1.5 rounded-full bg-slate-200 mx-auto mb-4"></div>

    <div class="flex items-center gap-3 mb-3">
        <div id="penj-icon" class="size-10 rounded-full flex items-center justify-center text-xl shrink-0"></div>
        <div>
            <p id="penj-label" class="text-xs font-extrabold uppercase tracking-widest"></p>
            <p id="penj-jawaban" class="text-sm font-bold text-slate-700"></p>
        </div>
    </div>

    @if ($kuis->tampilkan_penjelasan && $soal->penjelasan)
    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 mb-3">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Penjelasan</p>
        <p class="text-sm text-slate-700 leading-relaxed font-medium">{{ $soal->penjelasan }}</p>
    </div>
    @endif

    <button onclick="lanjutSoal()"
            class="w-full py-3.5 bg-primary text-white rounded-full
                   font-extrabold text-base shadow-[0_5px_0_0_#064e3b]
                   active:shadow-none active:translate-y-1 transition-all
                   flex items-center justify-center gap-2">
        {{ $isLast ? 'Lihat Hasil 🎉' : 'Soal Berikutnya →' }}
    </button>
</div>

{{-- ══ OVERLAY KELUAR ══════════════════════════════════════════ --}}
<div id="overlay-keluar"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-6">
    <div class="bg-white rounded-3xl p-7 w-full max-w-sm shadow-2xl animate-pop-in">
        <div class="text-5xl text-center mb-3">😮</div>
        <h3 class="text-xl font-extrabold text-slate-900 text-center mb-2">Keluar dari kuis?</h3>
        <p class="text-sm text-slate-500 text-center mb-6 font-medium">
            Progress kuis ini akan hilang. Yakin ingin keluar?
        </p>
        <div class="flex gap-3">
            <button onclick="tutupOverlay()"
                    class="flex-1 h-12 rounded-full border-2 border-slate-200 font-bold text-slate-700
                           hover:bg-slate-50 transition-colors">
                Lanjut Kuis
            </button>
            <form method="POST" action="{{ route('siswa.kuis.selesai', $kuis->kode_kuis) }}" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full h-12 rounded-full bg-red-500 text-white font-bold hover:bg-red-600 transition-colors">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ══ ZOOM GAMBAR ═════════════════════════════════════════════ --}}
<div id="img-zoom-overlay" onclick="this.classList.remove('show')">
    <img id="img-zoom-src" src="" alt="Zoom"/>
    <p class="absolute top-4 right-5 text-white/60 text-sm font-medium">✕ Ketuk untuk tutup</p>
</div>

<div id="confetti-container" class="pointer-events-none fixed inset-0 z-[99] overflow-hidden"></div>
@endsection

@push('scripts')
<script>
const WAKTU_TOTAL   = {{ $waktu }};
const NOMOR         = {{ $nomor }};
const TOTAL         = {{ $total }};
const KODE_KUIS     = "{{ $kuis->kode_kuis }}";
const IS_LAST       = {{ $isLast ? 'true' : 'false' }};
const SUDAH_JAWAB   = {{ $sudahJawab ? 'true' : 'false' }};
const JAWABAN_BENAR = "{{ $soal->jawaban_benar }}";
const CSRF          = "{{ csrf_token() }}";

let waktuSisa     = WAKTU_TOTAL;
let timerInterval = null;
let sudahJawab    = SUDAH_JAWAB;
let mulaiPada     = Date.now();

// ── Timer ──────────────────────────────────────────────────────
const circumference = 2 * Math.PI * 40;
const ring  = document.getElementById('timer-ring');
const tText = document.getElementById('timer-text');
const wrap  = document.getElementById('timer-wrapper');

function updateRing(sisa) {
    const pct = sisa / WAKTU_TOTAL;
    ring.style.strokeDashoffset = circumference * (1 - pct);
    ring.style.stroke = pct > 0.5 ? '#0d6e55' : pct > 0.25 ? '#f59e0b' : '#ef4444';
    tText.textContent = sisa;
    sisa <= 5 ? wrap.classList.add('timer-urgent') : wrap.classList.remove('timer-urgent');
}

function startTimer() {
    if (sudahJawab) return;
    timerInterval = setInterval(() => {
        waktuSisa--;
        updateRing(waktuSisa);
        if (waktuSisa <= 0) { clearInterval(timerInterval); kirimJawaban(null, true); }
    }, 1000);
}

// ── Pilih Jawaban ──────────────────────────────────────────────
async function pilihJawaban(key, btnEl) {
    if (sudahJawab) return;
    clearInterval(timerInterval);
    sudahJawab = true;
    const waktuMenjawab = Math.round((Date.now() - mulaiPada) / 1000);
    document.querySelectorAll('.opsi-btn').forEach(b => b.disabled = true);
    btnEl.classList.add('selected');
    updateBadgeBtn(btnEl, '●', 'bg-primary/20 border-primary/60');
    await kirimJawaban(key, false, waktuMenjawab, btnEl);
}

async function kirimJawaban(key, habis, waktuMenjawab = WAKTU_TOTAL, btnEl = null) {
    try {
        const res = await fetch(`/siswa/kuis/${KODE_KUIS}/jawab/${NOMOR}`, {
            method:  'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body:    new URLSearchParams({ _token: CSRF, jawaban: key ?? '', waktu_menjawab: habis ? WAKTU_TOTAL : waktuMenjawab }),
        });
        const data = await res.json();
        tampilkanFeedback(key, data);
    } catch(e) {
        aktifkanLanjut();
    }
}

// ── Feedback ───────────────────────────────────────────────────
function tampilkanFeedback(pilihan, data) {
    document.querySelectorAll('.opsi-btn').forEach(b => {
        const k = b.dataset.key;
        if (k === data.jawaban_benar) {
            b.classList.add('correct');
            updateBadgeBtn(b, '✓', 'bg-green-500 text-white border-green-500', 'text-green-700');
        } else if (pilihan && k === pilihan && !data.benar) {
            b.classList.add('wrong');
            updateBadgeBtn(b, '✗', 'bg-red-500 text-white border-red-500', 'text-red-600');
        }
    });

    if (data.poin > 0) {
        document.getElementById('poin-text').textContent = `+${data.poin}`;
        const badge = document.getElementById('poin-badge');
        badge.classList.remove('hidden');
        badge.classList.add('flex');
    }

    if (data.benar) spawnConfetti();
    tampilkanPenjelasan(data);
    aktifkanLanjut();
}

function updateBadgeBtn(btn, icon, badgeClass, textClass = '') {
    const ind = btn.querySelector('.indikator');
    if (!ind) return;
    ind.innerHTML = icon;
    ind.className = `shrink-0 size-6 rounded-full flex items-center justify-center text-xs font-black indikator border-2 ${badgeClass}`;
    if (textClass) btn.querySelector('.label-badge')?.classList.add(...textClass.split(' '));
}

// ── Penjelasan ─────────────────────────────────────────────────
function tampilkanPenjelasan(data) {
    const panel   = document.getElementById('penjelasan-panel');
    const icon    = document.getElementById('penj-icon');
    const label   = document.getElementById('penj-label');
    const jawaban = document.getElementById('penj-jawaban');

    // Nama jawaban: ambil teks opsi, fallback ke "Opsi X"
    const btnBenar = document.querySelector(`[data-key="${data.jawaban_benar}"] .opsi-text`);
    const namaBenar = (btnBenar?.textContent?.trim() && !btnBenar.classList.contains('sr-only'))
        ? btnBenar.textContent.trim()
        : `Opsi ${data.jawaban_benar.toUpperCase()}`;

    if (data.benar) {
        panel.style.borderTopColor = '#16a34a';
        icon.textContent = '🎉'; icon.className = 'size-10 rounded-full flex items-center justify-center text-xl bg-green-100 shrink-0';
        label.textContent = 'Jawaban Benar!'; label.className = 'text-xs font-extrabold uppercase tracking-widest text-green-600';
    } else {
        panel.style.borderTopColor = '#ef4444';
        icon.textContent = '😅'; icon.className = 'size-10 rounded-full flex items-center justify-center text-xl bg-red-100 shrink-0';
        label.textContent = 'Jawaban Salah'; label.className = 'text-xs font-extrabold uppercase tracking-widest text-red-500';
    }
    jawaban.textContent = `Jawaban benar: ${namaBenar}`;
    setTimeout(() => panel.classList.add('open'), 100);
}

// ── Tombol Lanjut ──────────────────────────────────────────────
function aktifkanLanjut() {
    const btn = document.getElementById('btn-lanjut');
    btn.disabled = false;
    document.getElementById('btn-lanjut-text').textContent = IS_LAST ? 'Lihat Hasil 🎉' : 'Soal Berikutnya →';
}

function lanjutSoal() {
    const btn = document.getElementById('btn-lanjut');
    btn.disabled = true;
    btn.innerHTML = `<span class="material-symbols-outlined animate-spin text-xl">hourglass_empty</span>`;
    if (IS_LAST) {
        InlineLoader.show('Menghitung nilai kamu... 🎯');
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/siswa/kuis/${KODE_KUIS}/selesai`;
        form.innerHTML = `<input name="_token" value="${CSRF}"/>`;
        document.body.appendChild(form);
        form.submit();
    } else {
        InlineLoader.show(`Memuat soal ${NOMOR + 1}... 📖`);
        window.location.href = `/siswa/kuis/${KODE_KUIS}/kerjakan/${NOMOR + 1}`;
    }
}

// ── Overlay Keluar ─────────────────────────────────────────────
function konfirmasiKeluar() {
    const ov = document.getElementById('overlay-keluar');
    ov.classList.remove('hidden'); ov.classList.add('flex');
}
function tutupOverlay() {
    const ov = document.getElementById('overlay-keluar');
    ov.classList.add('hidden'); ov.classList.remove('flex');
}

// ── Zoom Gambar ────────────────────────────────────────────────
function zoomGambar(src) {
    document.getElementById('img-zoom-src').src = src;
    document.getElementById('img-zoom-overlay').classList.add('show');
}

// ── Confetti ───────────────────────────────────────────────────
function spawnConfetti() {
    const colors = ['#0d6e55','#f59e0b','#3b82f6','#ec4899','#10b981','#f97316'];
    const container = document.getElementById('confetti-container');
    for (let i = 0; i < 28; i++) {
        const el = document.createElement('div');
        el.className = 'confetti-piece';
        el.style.cssText = `left:${20+Math.random()*60}vw;top:${20+Math.random()*30}vh;background:${colors[Math.floor(Math.random()*colors.length)]};animation-delay:${Math.random()*.4}s;animation-duration:${.8+Math.random()*.6}s;transform:rotate(${Math.random()*360}deg)`;
        container.appendChild(el);
        setTimeout(() => el.remove(), 1400);
    }
}

// ── Init ───────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    updateRing(waktuSisa);
    if (SUDAH_JAWAB) {
        aktifkanLanjut();
        document.querySelectorAll('.opsi-btn').forEach(b => b.disabled = true);
    } else {
        startTimer();
    }
});
</script>
@endpush

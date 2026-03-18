@extends('siswa.layouts.app')
@section('title', 'Masukkan Kode Kuis — GAS-IPAS')

@push('styles')
<style>
    .code-box {
        border: 2.5px solid #e2e8f0;
        transition: border-color .15s, box-shadow .15s, background .15s, transform .1s;
        caret-color: transparent;
    }
    .code-box:focus {
        border-color: #F5A623;
        box-shadow: 0 0 0 4px rgba(245,166,35,.22), 0 0 16px rgba(245,166,35,.3);
        outline: none;
        transform: scale(1.06);
    }
    .code-box.filled {
        border-color: #0d6e55;
        background: #f0faf6;
        color: #0d6e55;
    }
    .code-box.shake {
        animation: shake .35s ease;
    }
    @keyframes shake {
        0%,100%{ transform: translateX(0); }
        25%    { transform: translateX(-6px); }
        75%    { transform: translateX(6px); }
    }
    .float-badge {
        animation: floatY 3s ease-in-out infinite;
    }
    @keyframes floatY {
        0%,100% { transform: translateY(0) rotate(var(--r,0deg)); }
        50%     { transform: translateY(-8px) rotate(var(--r,0deg)); }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0a4d3a] via-[#0d6e55] to-[#0a3d6b]
            flex flex-col relative overflow-hidden font-display">

    {{-- ── Dekorasi Latar ── --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
        <div class="absolute top-[-80px] right-[-80px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-60px] left-[-60px] w-72 h-72 bg-accent/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                    w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>

        {{-- Floating badges --}}
        <div class="float-badge absolute top-16 right-6 md:right-16 bg-white/10 backdrop-blur border border-white/20
                    px-3 py-1.5 rounded-full flex items-center gap-1.5" style="--r:10deg">
            <span class="text-accent font-black text-sm">+100</span>
            <span class="text-base">⭐</span>
        </div>
        <div class="float-badge absolute bottom-1/3 left-4 md:left-16 bg-white/10 backdrop-blur border border-white/20
                    px-3 py-1.5 rounded-full" style="--r:-8deg; animation-delay:.7s">
            <span class="text-white font-bold text-sm">SERU! 🔥</span>
        </div>
        <div class="float-badge absolute top-1/3 right-4 md:right-20 bg-white/10 backdrop-blur border border-white/20
                    p-2.5 rounded-full" style="--r:5deg; animation-delay:1.2s">
            <span class="text-xl">🏆</span>
        </div>
        <div class="float-badge absolute bottom-16 right-10 opacity-60" style="animation-delay:.4s">
            <span class="material-symbols-outlined text-6xl md:text-7xl text-white/30 rotate-12">rocket_launch</span>
        </div>
    </div>

    {{-- ── Main Layout ── --}}
    <div class="relative z-10 flex flex-col flex-1 items-center justify-center px-4 py-8 md:py-12">

        {{-- Top Bar --}}
        <div class="w-full max-w-lg flex items-center justify-between mb-6">
            <a href="{{ route('siswa.kuis.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/10
                      hover:bg-white/20 active:scale-95 transition-all border border-white/20">
                <span class="material-symbols-outlined text-white text-xl">arrow_back</span>
            </a>
            <div class="bg-white/10 border border-white/20 px-4 py-1.5 rounded-full backdrop-blur">
                <p class="text-white text-xs font-bold tracking-widest uppercase">Masuk Kuis 🎮</p>
            </div>
            <div class="w-10"></div>
        </div>

        {{-- ── Card ── --}}
        <div class="w-full max-w-lg bg-white/10 backdrop-blur-xl border border-white/20
                    rounded-3xl shadow-2xl p-6 sm:p-8 md:p-10 animate-fade-up">

            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-accent/20 border border-accent/30
                            flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="material-symbols-outlined text-accent text-3xl">key</span>
                </div>
                <h1 class="text-white text-2xl sm:text-3xl font-black leading-tight mb-2">
                    Masukkan Kode Kuis!
                </h1>
                <p class="text-white/60 text-sm sm:text-base font-medium leading-relaxed">
                    Minta kode 6 karakter dari gurumu<br class="hidden sm:block"> untuk bergabung ke kuis.
                </p>
            </div>

            {{-- ── Form Input 6 Kotak ── --}}
            <form method="POST" action="{{ route('siswa.kuis.masuk.post') }}"
                  id="kodeForm">
                @csrf
                <input type="hidden" name="kode_kuis" id="kode_kuis_hidden"/>

                {{-- Boxes --}}
                <div class="flex justify-center gap-2 sm:gap-3 mb-4" id="codeBoxes">
                    @for ($i = 0; $i < 6; $i++)
                    <input type="text"
                           maxlength="1"
                           inputmode="text"
                           autocomplete="off"
                           class="code-box
                                  w-12 h-14 sm:w-14 sm:h-16 md:w-16 md:h-18
                                  bg-white text-slate-800
                                  text-xl sm:text-2xl font-black text-center uppercase
                                  rounded-2xl shadow-lg
                                  {{ $errors->any() ? 'shake' : '' }}"
                           data-index="{{ $i }}"/>
                    @endfor
                </div>

                {{-- Dot indicators --}}
                <div class="flex justify-center gap-2 sm:gap-3 mb-6">
                    @for ($i = 0; $i < 6; $i++)
                    <div class="w-12 sm:w-14 md:w-16 flex justify-center">
                        <div class="w-1.5 h-1.5 rounded-full bg-white/20 transition-colors duration-200 dot-indicator"
                             data-dot="{{ $i }}"></div>
                    </div>
                    @endfor
                </div>

                {{-- Info hints --}}
                <div class="grid grid-cols-3 gap-2 mb-8">
                    <div class="bg-white/5 border border-white/10 rounded-xl p-3 text-center">
                        <span class="text-lg block mb-0.5">⌨️</span>
                        <p class="text-white/50 text-[10px] font-medium">Ketik huruf<br>atau angka</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-3 text-center">
                        <span class="text-lg block mb-0.5">📋</span>
                        <p class="text-white/50 text-[10px] font-medium">Paste otomatis<br>terisi semua</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-3 text-center">
                        <span class="text-lg block mb-0.5">🔤</span>
                        <p class="text-white/50 text-[10px] font-medium">Huruf besar/<br>kecil sama saja</p>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="button"
                        id="submitBtn"
                        onclick="submitKode()"
                        class="w-full bg-accent hover:bg-amber-400 active:bg-amber-600
                               text-gray-900 text-base sm:text-lg font-extrabold
                               py-4 sm:py-5 rounded-2xl
                               shadow-[0_6px_0_0_#b4791a] active:shadow-none active:translate-y-1.5
                               transition-all duration-150
                               flex items-center justify-center gap-2
                               disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none"
                        disabled>
                    <span id="btnText">MASUK KE KUIS!</span>
                    <span class="material-symbols-outlined font-bold text-xl" id="btnIcon">rocket_launch</span>
                </button>
            </form>

            {{-- Footer note --}}
            <p class="text-white/30 text-center mt-5 text-xs font-medium">
                Belum punya kode? Tanya gurumu 🙋
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const boxes       = document.querySelectorAll('.code-box');
const dots        = document.querySelectorAll('.dot-indicator');
const hiddenInput = document.getElementById('kode_kuis_hidden');
const submitBtn   = document.getElementById('submitBtn');

boxes[0].focus();

boxes.forEach((box, idx) => {
    box.addEventListener('input', e => {
        const val = e.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
        box.value = val ? val[val.length - 1] : '';
        updateState();
        if (val && idx < boxes.length - 1) boxes[idx + 1].focus();
    });

    box.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !box.value && idx > 0) {
            boxes[idx - 1].focus();
            boxes[idx - 1].value = '';
            updateState();
        }
        if (e.key === 'ArrowLeft'  && idx > 0) boxes[idx - 1].focus();
        if (e.key === 'ArrowRight' && idx < boxes.length - 1) boxes[idx + 1].focus();
        if (e.key === 'Enter') submitKode();
    });

    box.addEventListener('paste', e => {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData)
            .getData('text')
            .replace(/[^a-zA-Z0-9]/g, '')
            .toUpperCase()
            .slice(0, 6);
        pasted.split('').forEach((ch, i) => { if (boxes[i]) boxes[i].value = ch; });
        boxes[Math.min(pasted.length, boxes.length - 1)].focus();
        updateState();
    });

    // Tap to re-focus on mobile
    box.addEventListener('click', () => box.select());
});

function updateState() {
    const code = Array.from(boxes).map(b => b.value).join('');
    hiddenInput.value = code;
    boxes.forEach((b, i) => {
        dots[i].classList.toggle('bg-accent',   !!b.value);
        dots[i].classList.toggle('bg-white/20', !b.value);
        b.classList.toggle('filled', !!b.value);
    });
    submitBtn.disabled = code.length < 6;
}

function submitKode() {
    const code = Array.from(boxes).map(b => b.value).join('');
    if (code.length < 6) { boxes[code.length]?.focus(); return; }

    const btn  = document.getElementById('submitBtn');
    const text = document.getElementById('btnText');
    const icon = document.getElementById('btnIcon');
    btn.disabled     = true;
    text.textContent = 'Mencari kuis...';
    icon.textContent = 'hourglass_empty';
    icon.classList.add('animate-spin');

    if (window.InlineLoader) InlineLoader.show('Mencari kuis kamu... 🔍');
    document.getElementById('kodeForm').submit();
}

updateState();
</script>
@endpush

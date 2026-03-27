@extends('siswa.layouts.app')
@section('title', 'Masuk — GAS-IPAS')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ══════════════════════════════════════════════════════
         KIRI / ATAS — Branding & Maskot
    ══════════════════════════════════════════════════════ --}}
    <div class="relative lg:w-1/2 lg:min-h-screen flex flex-col items-center justify-center
                overflow-hidden bg-soft-mint dark:bg-primary/10
                h-[300px] sm:h-[360px] lg:h-auto">

        {{-- Polkadot pattern --}}
        <div class="absolute inset-0 science-pattern opacity-40 pointer-events-none"></div>

        {{-- Floating shapes dekorasi --}}
        <div class="absolute top-6 left-6 w-16 h-16 bg-accent/20 rounded-full blur-xl animate-pulse"></div>
        <div class="absolute bottom-10 right-8 w-24 h-24 bg-primary/20 rounded-full blur-2xl animate-pulse delay-700"></div>
        <div class="absolute top-1/3 right-6 w-10 h-10 bg-primary/30 rounded-xl rotate-12 animate-bounce-slow delay-300"></div>

        {{-- Konten Maskot --}}
        <div class="relative z-10 flex flex-col items-center animate-bounce-slow">

            {{-- Avatar maskot --}}
            <div class="relative">
                <div class="w-36 h-36 sm:w-44 sm:h-44 lg:w-52 lg:h-52
                            bg-white/70 dark:bg-white/10
                            rounded-full flex items-center justify-center
                            shadow-[0_8px_40px_rgba(13,110,85,.25)]
                            animate-pulse-ring">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDDSUNFRoge3dP3JrEJ0XTj64QAr4jP-gont2W9oR8F69Ldw5W3vWR2bVvClCv7Mn3HMRpLFAtb5oDDLBSz89V_j4ijH-jxUaWyFrxisfS1MHJx9M7QlNziDw1j75dqNZYU_cEeTj9oIcAElFzSXpnCdMGUaFWUtYFJorEgxOk9SpgBeO1Eia3mymQmEAYjKvQAsxUNYLrX_NH-F2zPR7tOgxEsKTvzzVnviE1eyBi8d3AbIBLtAzgh3usuHZXW8UhfMVylS6K8GJo"
                         alt="Maskot GAS-IPAS"
                         class="w-28 h-28 sm:w-36 sm:h-36 lg:w-44 lg:h-44 object-cover rounded-full drop-shadow-xl"/>
                </div>
                <div class="absolute -top-2 -right-2 bg-accent text-white p-2 rounded-full shadow-lg animate-pop-in">
                    <span class="material-symbols-outlined text-2xl">rocket_launch</span>
                </div>
            </div>

            {{-- Brand name --}}
            <div class="mt-5 bg-primary px-8 py-2.5 rounded-xl shadow-xl transform -rotate-2
                        animate-pop-in" style="animation-delay:.1s">
                <h1 class="text-white text-3xl sm:text-4xl font-extrabold tracking-tight">GAS-IPAS</h1>
            </div>

            <p class="mt-4 text-primary dark:text-emerald-300 font-bold text-base sm:text-lg
                       bg-white/80 dark:bg-white/10 px-5 py-1.5 rounded-full backdrop-blur-sm
                       animate-fade-up shadow" style="animation-delay:.2s">
                Belajar IPAS Jadi Seru! 🚀
            </p>

            {{-- Icon row — hanya tampil di lg --}}
            <div class="hidden lg:flex mt-10 gap-6 opacity-30">
                <span class="material-symbols-outlined text-4xl text-primary">eco</span>
                <span class="material-symbols-outlined text-4xl text-primary">science</span>
                <span class="material-symbols-outlined text-4xl text-primary">public</span>
                <span class="material-symbols-outlined text-4xl text-primary">auto_stories</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         KANAN / BAWAH — Form Login
    ══════════════════════════════════════════════════════ --}}
    <div class="relative flex-1 bg-white dark:bg-slate-900
                rounded-t-[2rem] lg:rounded-none lg:rounded-l-[2rem]
                -mt-8 lg:mt-0
                shadow-2xl lg:shadow-[-20px_0_60px_rgba(0,0,0,.08)]
                flex items-center justify-center
                px-6 py-10 sm:px-10 lg:px-16">

        <div class="w-full max-w-md animate-fade-up">

            {{-- Heading --}}
            <div class="text-center mb-8 lg:mb-10">
                <h2 class="text-slate-900 dark:text-slate-100 text-3xl sm:text-4xl font-extrabold">
                    Hai! Siapa kamu? 👋
                </h2>
                <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">
                    Siap menjelajahi dunia sains hari ini?
                </p>
            </div>

            {{-- Error ditangani oleh global error popup di layout --}}

            {{-- Form --}}
            <form method="POST" action="{{ route('siswa.login.post') }}" class="space-y-5" id="loginForm">
                @csrf

                {{-- Nama Panggilan --}}
                <div class="flex flex-col gap-1.5">
                    <label for="nama_panggilan"
                           class="text-primary font-bold text-xs uppercase tracking-widest ml-4">
                        Nama Panggilanmu
                    </label>
                    <div class="relative flex items-center group">
                        <span class="material-symbols-outlined absolute left-4
                                     text-primary/50 group-focus-within:text-primary transition-colors">
                            sentiment_satisfied
                        </span>
                        <input id="nama_panggilan"
                               name="nama_panggilan"
                               type="text"
                               value="{{ old('nama_panggilan') }}"
                               placeholder="Ketik namamu di sini"
                               autocomplete="username"
                               required
                               class="w-full h-14 sm:h-16 pl-12 pr-5
                                      bg-background-light dark:bg-slate-800
                                      border-2 border-transparent
                                      focus:border-primary focus:ring-0
                                      rounded-full
                                      text-slate-900 dark:text-slate-100 font-bold
                                      placeholder:text-slate-400 placeholder:font-normal
                                      transition-all duration-200
                                      @error('nama_panggilan') border-red-400 @enderror"/>
                    </div>
                </div>

                {{-- Kode Siswa --}}
                <div class="flex flex-col gap-1.5">
                    <label for="kode_siswa"
                           class="text-primary font-bold text-xs uppercase tracking-widest ml-4">
                        Kode Rahasiamu
                    </label>
                    <div class="relative flex items-center group">
                        <span class="material-symbols-outlined absolute left-4
                                     text-primary/50 group-focus-within:text-primary transition-colors">
                            grade
                        </span>
                        <input id="kode_siswa"
                               name="kode_siswa"
                               type="password"
                               placeholder="••••••"
                               autocomplete="current-password"
                               required
                               class="w-full h-14 sm:h-16 pl-12 pr-14
                                      bg-background-light dark:bg-slate-800
                                      border-2 border-transparent
                                      focus:border-primary focus:ring-0
                                      rounded-full
                                      text-slate-900 dark:text-slate-100 font-bold tracking-widest
                                      placeholder:text-slate-400 placeholder:tracking-normal
                                      transition-all duration-200
                                      @error('kode_siswa') border-red-400 @enderror"/>
                        {{-- Toggle show/hide --}}
                        <button type="button"
                                onclick="toggleKode()"
                                class="absolute right-4 text-slate-400 hover:text-primary transition-colors"
                                id="toggleBtn"
                                aria-label="Tampilkan kode">
                            <span class="material-symbols-outlined" id="eyeIcon">visibility</span>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="pt-4 space-y-4">
                    <button type="submit"
                            id="submitBtn"
                            class="w-full h-14 sm:h-16
                                   bg-primary hover:bg-primary/90 active:bg-primary-dark
                                   text-white rounded-full
                                   text-lg sm:text-xl font-extrabold
                                   shadow-[0_8px_0_0_#064e3b]
                                   active:shadow-none active:translate-y-2
                                   transition-all duration-150
                                   flex items-center justify-center gap-3
                                   focus:outline-none focus:ring-4 focus:ring-primary/40">
                        <span id="btnText">AYO MASUK!</span>
                        <span class="material-symbols-outlined font-bold" id="btnIcon">arrow_forward</span>
                    </button>

                    {{-- Hint --}}
                    <div class="flex items-center justify-center gap-2
                                bg-accent/10 dark:bg-accent/5
                                border border-accent/20 rounded-2xl px-4 py-3">
                        <span class="material-symbols-outlined text-accent">lightbulb</span>
                        <p class="text-slate-700 dark:text-slate-300 text-sm font-semibold">
                            Kode dikasih sama gurumu ya!
                        </p>
                    </div>

                    {{-- Link ke panel guru --}}
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-px bg-slate-200 dark:bg-slate-700"></div>
                        <span class="text-slate-400 text-xs font-medium">atau</span>
                        <div class="flex-1 h-px bg-slate-200 dark:bg-slate-700"></div>
                    </div>

                    <a href="{{ url('/admin') }}"
                       class="w-full h-12 sm:h-14
                              bg-white dark:bg-slate-800
                              border-2 border-slate-200 dark:border-slate-600
                              hover:border-primary hover:bg-primary/5 dark:hover:border-primary
                              text-slate-700 dark:text-slate-300 hover:text-primary
                              rounded-full
                              text-sm sm:text-base font-bold
                              shadow-sm hover:shadow-md
                              transition-all duration-200
                              flex items-center justify-center gap-2.5">
                        <span class="material-symbols-outlined text-xl">school</span>
                        Masuk sebagai Guru
                    </a>
                </div>
            </form>

            {{-- Footer icons — mobile only --}}
            <div class="mt-10 flex justify-center gap-6 opacity-20 dark:opacity-10 lg:hidden">
                <span class="material-symbols-outlined text-3xl text-primary">eco</span>
                <span class="material-symbols-outlined text-3xl text-primary">science</span>
                <span class="material-symbols-outlined text-3xl text-primary">public</span>
                <span class="material-symbols-outlined text-3xl text-primary">auto_stories</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle show/hide kode
    function toggleKode() {
        const input   = document.getElementById('kode_siswa');
        const icon    = document.getElementById('eyeIcon');
        const isHide  = input.type === 'password';
        input.type    = isHide ? 'text' : 'password';
        icon.textContent = isHide ? 'visibility_off' : 'visibility';
    }

    // Loading state saat submit
    document.getElementById('loginForm').addEventListener('submit', function () {
        const btn  = document.getElementById('submitBtn');
        const text = document.getElementById('btnText');
        const icon = document.getElementById('btnIcon');
        btn.disabled         = true;
        btn.classList.add('opacity-80');
        text.textContent     = 'Tunggu...';
        icon.textContent     = 'hourglass_empty';
        icon.classList.add('animate-spin');
    });
</script>
@endpush

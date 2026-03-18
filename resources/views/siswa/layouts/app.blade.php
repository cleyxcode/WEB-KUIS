<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'GAS-IPAS')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1" rel="stylesheet"/>

    {{-- Lottie Web Component --}}
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.9.3/dist/dotlottie-wc.js" type="module"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary:            '#0d6e55',
                        'primary-dark':     '#064e3b',
                        accent:             '#F5A623',
                        'background-light': '#f6f8f7',
                        'background-dark':  '#11211d',
                        'soft-mint':        '#E8F8F3',
                    },
                    fontFamily: {
                        display: ['"Be Vietnam Pro"', 'sans-serif'],
                    },
                    borderRadius: {
                        DEFAULT: '1rem',
                        lg:      '2rem',
                        xl:      '3rem',
                        full:    '9999px',
                    },
                    keyframes: {
                        'bounce-slow': {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%':      { transform: 'translateY(-10px)' },
                        },
                        'fade-up': {
                            '0%':   { opacity: '0', transform: 'translateY(24px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        'pop-in': {
                            '0%':   { opacity: '0', transform: 'scale(0.85)' },
                            '70%':  { transform: 'scale(1.05)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        'pulse-ring': {
                            '0%':   { boxShadow: '0 0 0 0 rgba(13,110,85,.35)' },
                            '70%':  { boxShadow: '0 0 0 12px rgba(13,110,85,0)' },
                            '100%': { boxShadow: '0 0 0 0 rgba(13,110,85,0)' },
                        },
                        shimmer: {
                            '0%':   { backgroundPosition: '-200% center' },
                            '100%': { backgroundPosition: '200% center' },
                        },
                        'loader-fade-in': {
                            '0%':   { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        'loader-fade-out': {
                            '0%':   { opacity: '1', transform: 'scale(1)' },
                            '100%': { opacity: '0', transform: 'scale(1.04)' },
                        },
                        'lottie-pop': {
                            '0%':   { transform: 'scale(.7)', opacity: '0' },
                            '60%':  { transform: 'scale(1.08)' },
                            '100%': { transform: 'scale(1)',   opacity: '1' },
                        },
                    },
                    animation: {
                        'bounce-slow':    'bounce-slow 3s ease-in-out infinite',
                        'fade-up':        'fade-up .5s ease both',
                        'pop-in':         'pop-in .45s cubic-bezier(.34,1.56,.64,1) both',
                        'pulse-ring':     'pulse-ring 2s ease-out infinite',
                        shimmer:          'shimmer 2.5s linear infinite',
                        'loader-fade-in': 'loader-fade-in .25s ease forwards',
                        'lottie-pop':     'lottie-pop .5s cubic-bezier(.34,1.56,.64,1) .1s both',
                    },
                },
            },
        }
    </script>

    <style>
        *, body { font-family: 'Be Vietnam Pro', sans-serif; }

        .science-pattern {
            background-color: #E8F8F3;
            background-image:
                radial-gradient(#0d6e55 0.5px, transparent 0.5px),
                radial-gradient(#0d6e55 0.5px, #E8F8F3 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
        }

        ::-webkit-scrollbar       { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #0d6e5566; border-radius: 99px; }

        /* ── Inline Loader (Lottie ke-2) ───────────────────── */
        #inline-loader {
            position: fixed; inset: 0; z-index: 9990;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            background: rgba(246,248,247,.88);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            opacity: 0; pointer-events: none;
            transition: opacity .2s ease;
        }
        .dark #inline-loader { background: rgba(17,33,29,.88); }
        #inline-loader.visible { opacity: 1; pointer-events: all; }

        #inline-loader dotlottie-wc {
            width:  clamp(140px, 38vw, 220px);
            height: clamp(140px, 38vw, 220px);
        }
        #inline-loader-text {
            font-size: clamp(.7rem, 2.2vw, .85rem);
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #0d6e55;
            margin-top: -10px;
            opacity: 0;
            transform: translateY(6px);
            transition: opacity .25s ease .15s, transform .25s ease .15s;
        }
        #inline-loader.visible #inline-loader-text {
            opacity: 1; transform: translateY(0);
        }
        @media (min-width: 768px) {
            #inline-loader dotlottie-wc { width: 240px; height: 240px; }
        }

        /* ── Empty State Lottie ─────────────────────────────── */
        .empty-lottie dotlottie-wc {
            width:  clamp(120px, 36vw, 200px);
            height: clamp(120px, 36vw, 200px);
        }

        /* ── Success Popup ──────────────────────────────────── */
        #success-popup {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 9997;
            display: flex;
            justify-content: center;
            padding: 0 1rem 1.5rem;
            pointer-events: none;
        }
        #success-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 -4px 40px rgba(13,110,85,.18), 0 8px 32px rgba(0,0,0,.1);
            overflow: hidden;
            transform: translateY(120%);
            opacity: 0;
            transition: transform .45s cubic-bezier(.34,1.56,.64,1),
                        opacity .3s ease;
            pointer-events: all;
        }
        .dark #success-card { background: #1e293b; }
        #success-card.show {
            transform: translateY(0);
            opacity: 1;
        }
        #success-card.hide {
            transform: translateY(30px);
            opacity: 0;
            transition: transform .3s ease, opacity .25s ease;
        }
        #success-card dotlottie-wc {
            width:  clamp(100px, 30vw, 150px);
            height: clamp(100px, 30vw, 150px);
        }
        /* Progress bar auto-close */
        #success-progress {
            height: 3px;
            background: #0d6e55;
            width: 100%;
            transform-origin: left;
            animation: progress-shrink 4s linear forwards;
        }
        @keyframes progress-shrink {
            from { transform: scaleX(1); }
            to   { transform: scaleX(0); }
        }

        /* ── Page Loader Overlay ────────────────────────────── */
        #page-loader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(246, 248, 247, .92);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            opacity: 0;
            pointer-events: none;
            transition: opacity .22s ease;
        }
        #page-loader.visible {
            opacity: 1;
            pointer-events: all;
        }
        #page-loader.hiding {
            opacity: 0;
            transition: opacity .3s ease;
        }

        /* Dark mode overlay */
        .dark #page-loader {
            background: rgba(17, 33, 29, .92);
        }

        /* Lottie sizing — fluid & responsive */
        #page-loader dotlottie-wc {
            width:  clamp(160px, 40vw, 260px);
            height: clamp(160px, 40vw, 260px);
            animation: lottie-pop .5s cubic-bezier(.34,1.56,.64,1) .05s both;
        }

        /* Label teks di bawah lottie */
        #loader-label {
            font-size: clamp(.75rem, 2.5vw, .875rem);
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #0d6e55;
            margin-top: -8px;
            opacity: 0;
            transform: translateY(8px);
            transition: opacity .3s ease .25s, transform .3s ease .25s;
        }
        #page-loader.visible #loader-label {
            opacity: 1;
            transform: translateY(0);
        }

        /* Titik-titik animasi di bawah label */
        #loader-dots span {
            display: inline-block;
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #0d6e55;
            margin: 0 3px;
            animation: dot-bounce .9s ease-in-out infinite both;
        }
        #loader-dots span:nth-child(2) { animation-delay: .18s; }
        #loader-dots span:nth-child(3) { animation-delay: .36s; }
        @keyframes dot-bounce {
            0%, 80%, 100% { transform: scale(.6); opacity: .4; }
            40%            { transform: scale(1);  opacity: 1; }
        }

        /* Tablet & Desktop: sedikit lebih besar */
        @media (min-width: 768px) {
            #page-loader dotlottie-wc {
                width:  280px;
                height: 280px;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark min-h-screen font-display antialiased">

    {{-- ══════════════════════════════════════════════════════
         PAGE LOADER — tampil saat navigasi antar halaman
    ══════════════════════════════════════════════════════ --}}
    <div id="page-loader" role="status" aria-label="Memuat halaman">
        <dotlottie-wc
            src="https://lottie.host/72aab949-c144-4775-a510-3ee604f4c7df/zrZYpHEjcS.lottie"
            autoplay
            loop>
        </dotlottie-wc>

        <p id="loader-label">Memuat...</p>

        <div id="loader-dots" class="mt-2">
            <span></span><span></span><span></span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         ERROR POPUP — tampil otomatis jika ada error validasi
    ══════════════════════════════════════════════════════ --}}
    @if ($errors->any() || session('error') || session('warning'))
    <div id="error-overlay"
         role="alertdialog"
         aria-modal="true"
         aria-label="Pesan error"
         class="fixed inset-0 z-[9998] flex items-end sm:items-center justify-center
                px-4 pb-6 sm:pb-0
                bg-black/40 backdrop-blur-sm"
         onclick="closeErrorPopup(event)">

        <div id="error-card"
             class="relative w-full max-w-sm
                    bg-white dark:bg-slate-900
                    rounded-3xl shadow-2xl
                    overflow-hidden
                    translate-y-8 opacity-0
                    transition-all duration-300 ease-out">

            {{-- Strip warna atas --}}
            <div class="h-1.5 w-full
                        {{ session('warning') ? 'bg-amber-400' : 'bg-red-500' }}">
            </div>

            <div class="flex flex-col items-center px-6 pt-5 pb-7 text-center">

                {{-- Lottie animasi error --}}
                <div class="w-full flex justify-center mb-1" style="height: clamp(120px, 35vw, 180px);">
                    <dotlottie-wc
                        src="https://lottie.host/ce4dbcb0-526b-4797-a010-036ec0417dbc/hXofeh3HOO.lottie"
                        autoplay
                        loop
                        style="width: clamp(120px, 35vw, 180px); height: clamp(120px, 35vw, 180px);">
                    </dotlottie-wc>
                </div>

                {{-- Judul --}}
                <h3 class="text-lg font-extrabold leading-tight mb-1
                           {{ session('warning') ? 'text-amber-600' : 'text-red-500' }}">
                    @if (session('warning'))
                        Perhatian! ⚠️
                    @else
                        Oops! Ada yang Salah 😅
                    @endif
                </h3>

                {{-- Pesan error --}}
                <div class="mt-1 space-y-1.5 w-full">
                    @if (session('error'))
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300
                                  bg-red-50 dark:bg-red-900/20 border border-red-100
                                  rounded-xl px-4 py-2.5 leading-snug">
                            {{ session('error') }}
                        </p>
                    @elseif (session('warning'))
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300
                                  bg-amber-50 dark:bg-amber-900/20 border border-amber-100
                                  rounded-xl px-4 py-2.5 leading-snug">
                            {{ session('warning') }}
                        </p>
                    @else
                        @foreach ($errors->all() as $error)
                        <div class="flex items-start gap-2.5 text-left
                                    bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800
                                    rounded-xl px-4 py-2.5">
                            <span class="material-symbols-outlined text-red-400 text-base shrink-0 mt-px">
                                error
                            </span>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 leading-snug">
                                {{ $error }}
                            </p>
                        </div>
                        @endforeach
                    @endif
                </div>

                {{-- Tombol tutup --}}
                <button onclick="closeErrorPopup()"
                        class="mt-5 w-full h-12
                               {{ session('warning') ? 'bg-amber-400 shadow-[0_5px_0_0_#b4791a]' : 'bg-red-500 shadow-[0_5px_0_0_#b91c1c]' }}
                               text-white font-extrabold rounded-full text-sm
                               active:shadow-none active:translate-y-1
                               transition-all duration-150
                               flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                    Oke, Saya Mengerti
                </button>
            </div>
        </div>
    </div>

    <script>
    // Animasi masuk popup
    (function () {
        const card = document.getElementById('error-card');
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                card.style.transform  = 'translateY(0)';
                card.style.opacity    = '1';
            });
        });
    })();

    function closeErrorPopup(e) {
        // Jika klik di luar card (di overlay), tutup
        if (e && e.target !== document.getElementById('error-overlay')) return;

        const overlay = document.getElementById('error-overlay');
        const card    = document.getElementById('error-card');

        card.style.transition = 'transform .25s ease, opacity .25s ease';
        card.style.transform  = 'translateY(20px)';
        card.style.opacity    = '0';
        overlay.style.transition = 'opacity .3s ease';
        overlay.style.opacity    = '0';

        setTimeout(() => overlay.remove(), 320);
    }

    // Tutup dengan tombol Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeErrorPopup();
    });
    </script>
    @endif

    {{-- ══ SUCCESS POPUP — slide-up card ketika data sukses ══ --}}
    @if (session('success'))
    <div id="success-popup" role="status" aria-live="polite">
        <div id="success-card">
            {{-- Progress bar auto-close --}}
            <div id="success-progress"></div>

            <div class="flex items-center gap-4 px-5 py-4">
                {{-- Lottie sukses --}}
                <div class="shrink-0">
                    <dotlottie-wc
                        src="https://lottie.host/c5bed76d-96f9-4e35-87bb-f376a1edccf2/LscyMIy3LO.lottie"
                        autoplay loop>
                    </dotlottie-wc>
                </div>

                {{-- Teks --}}
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-extrabold uppercase tracking-widest text-primary mb-0.5">
                        Berhasil! ✅
                    </p>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200 leading-snug">
                        {{ session('success') }}
                    </p>
                </div>

                {{-- Tombol tutup --}}
                <button onclick="closeSuccess()"
                        class="shrink-0 size-8 rounded-full bg-slate-100 dark:bg-slate-700
                               flex items-center justify-center
                               text-slate-400 hover:text-slate-600 hover:bg-slate-200
                               transition-colors">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
        </div>
    </div>

    <script>
    (function () {
        const card = document.getElementById('success-card');
        let autoTimer = null;

        // Animasi masuk setelah DOM siap
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                card.classList.add('show');
            });
        });

        // Auto close setelah 4 detik
        autoTimer = setTimeout(closeSuccess, 4200);

        window.closeSuccess = function () {
            clearTimeout(autoTimer);
            card.classList.remove('show');
            card.classList.add('hide');
            setTimeout(() => {
                document.getElementById('success-popup')?.remove();
            }, 320);
        };

        // Tutup dengan swipe ke bawah (touch)
        let startY = 0;
        card.addEventListener('touchstart', e => { startY = e.touches[0].clientY; }, { passive: true });
        card.addEventListener('touchend', e => {
            if (e.changedTouches[0].clientY - startY > 60) closeSuccess();
        });
    })();
    </script>
    @endif

    {{-- ══ INLINE LOADER (Lottie ke-2) — untuk transisi dalam halaman ══ --}}
    <div id="inline-loader" role="status" aria-live="polite" aria-label="Memuat">
        <dotlottie-wc
            src="https://lottie.host/f58cb72b-0b3b-4d49-a3d0-59eb12699a64/tTowtuMPwA.lottie"
            autoplay loop>
        </dotlottie-wc>
        <p id="inline-loader-text">Memuat...</p>
    </div>

    {{-- Konten halaman --}}
    @yield('content')

    @stack('scripts')

    {{-- ══ Script Global Loader ══════════════════════════════ --}}
    <script>
    (function () {
        const loader = document.getElementById('page-loader');
        const label  = document.getElementById('loader-label');

        // Teks pesan per path
        const msgMap = {
            'login':      'Menyiapkan login...',
            'dashboard':  'Menuju beranda...',
            'kuis/masuk': 'Membuka kuis...',
            'kerjakan':   'Memuat soal...',
            'hasil':      'Menghitung nilai...',
            'leaderboard':'Memuat peringkat...',
            'profil':     'Membuka profil...',
        };

        function getMsg(url) {
            const path = url || window.location.href;
            for (const [key, msg] of Object.entries(msgMap)) {
                if (path.includes(key)) return msg;
            }
            return 'Memuat...';
        }

        function show(msg) {
            label.textContent = msg || getMsg();
            loader.classList.remove('hiding');
            loader.classList.add('visible');
        }

        function hide() {
            loader.classList.add('hiding');
            loader.classList.remove('visible');
            setTimeout(() => loader.classList.remove('hiding'), 320);
        }

        // Sembunyikan loader saat halaman siap
        function onReady() {
            // Delay kecil agar Lottie sempat render sebelum hilang
            setTimeout(hide, 380);
        }

        if (document.readyState === 'complete') {
            onReady();
        } else {
            window.addEventListener('load', onReady);
            // Fallback: jika load terlalu lama, hide paksa setelah 5 detik
            setTimeout(hide, 5000);
        }

        // Tampilkan loader saat klik link navigasi
        document.addEventListener('click', function (e) {
            const anchor = e.target.closest('a[href]');
            if (!anchor) return;

            const href = anchor.getAttribute('href');

            // Abaikan: link eksternal, anchor (#), javascript:, target _blank, download
            if (!href
                || href.startsWith('#')
                || href.startsWith('javascript')
                || href.startsWith('http') && !href.includes(window.location.hostname)
                || anchor.target === '_blank'
                || anchor.hasAttribute('download')
                || anchor.getAttribute('data-no-loader') !== null) {
                return;
            }

            show(getMsg(href));
        });

        // Tampilkan loader saat form submit (navigasi)
        document.addEventListener('submit', function (e) {
            const form = e.target;
            // Abaikan form AJAX (XMLHttpRequest)
            if (form.getAttribute('data-no-loader') !== null) return;
            // Abaikan form method selain GET/POST biasa
            const method = (form.method || 'get').toLowerCase();
            if (method !== 'get' && method !== 'post') return;

            // Jangan tampilkan loader untuk form yang punya target
            if (form.target && form.target !== '_self') return;

            // Cek apakah ada tombol submit yang sudah ditandai no-loader
            const activeBtn = document.activeElement;
            if (activeBtn && activeBtn.getAttribute('data-no-loader') !== null) return;

            show(getMsg(form.action));
        });

        // Back/Forward browser
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) hide();   // halaman dari BFCache
        });

        // Expose global untuk dipakai manual
        window.PageLoader = { show, hide };
    })();

    // ── Inline Loader API (Lottie ke-2) ─────────────────────
    window.InlineLoader = (function () {
        const el   = document.getElementById('inline-loader');
        const txt  = document.getElementById('inline-loader-text');
        let timer  = null;

        function show(msg, timeoutMs) {
            if (txt) txt.textContent = msg || 'Memuat...';
            el.classList.add('visible');
            if (timeoutMs) {
                clearTimeout(timer);
                timer = setTimeout(hide, timeoutMs);
            }
        }
        function hide() {
            clearTimeout(timer);
            el.classList.remove('visible');
        }
        return { show, hide };
    })();
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'SIPANDU') }} — Sistem Informasi Posyandu Terpadu</title>

        @fonts

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .font-display { font-family: 'Fraunces', serif; }
            .font-mono { font-family: 'JetBrains Mono', monospace; }
            .dot-grid {
                background-image: radial-gradient(#16413A22 1px, transparent 1px);
                background-size: 22px 22px;
            }
            [x-cloak] { display: none !important; }

            /* ===== Animasi Grafik Tumbuh Kembang ===== */
            @keyframes drawLine {
                to { stroke-dashoffset: 0; }
            }
            @keyframes popIn {
                0% { transform: scale(0); opacity: 0; }
                60% { transform: scale(1.25); opacity: 1; }
                100% { transform: scale(1); opacity: 1; }
            }
            @keyframes growBar {
                from { transform: scaleY(0); }
                to { transform: scaleY(1); }
            }
            @keyframes floatUp {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-6px); }
            }
            .tk-line {
                stroke-dasharray: 900;
                stroke-dashoffset: 900;
                animation: drawLine 2.2s ease-out forwards;
                animation-play-state: paused;
            }
            .tk-dot {
                transform-origin: center;
                animation: popIn 0.5s ease-out forwards;
                animation-play-state: paused;
                opacity: 0;
            }
            .tk-bar {
                transform-origin: bottom;
                animation: growBar 1s cubic-bezier(.22,1,.36,1) forwards;
                animation-play-state: paused;
                transform: scaleY(0);
            }
            .tk-badge {
                animation: floatUp 3s ease-in-out infinite;
            }
            .tk-visible .tk-line,
            .tk-visible .tk-dot,
            .tk-visible .tk-bar {
                animation-play-state: running;
            }
        </style>
    </head>
    <body class="bg-[#FBF6EF] text-[#223330] antialiased">

        <div x-data="{
                mobileOpen: false,
                openFaq: 1,
                tkVisible: false,
                counted: false,
                bb: 0, tb: 0, ll: 0,
                initTumbuhKembang() {
                    const el = this.$refs.tkSection;
                    const obs = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting && !this.tkVisible) {
                                this.tkVisible = true;
                                this.runCounters();
                            }
                        });
                    }, { threshold: 0.35 });
                    obs.observe(el);
                },
                runCounters() {
                    if (this.counted) return;
                    this.counted = true;
                    const targets = { bb: 12.4, tb: 85, ll: 15.2 };
                    const duration = 1400;
                    const start = performance.now();
                    const step = (now) => {
                        const p = Math.min((now - start) / duration, 1);
                        this.bb = (targets.bb * p).toFixed(1);
                        this.tb = Math.round(targets.tb * p);
                        this.ll = (targets.ll * p).toFixed(1);
                        if (p < 1) requestAnimationFrame(step);
                    };
                    requestAnimationFrame(step);
                }
            }"
        >

            {{-- ============ HEADER ============ --}}
            <header class="sticky top-0 z-50 bg-[#0F766E]">
                <div class="max-w-7xl mx-auto px-6 lg:px-10">
                    <div class="flex items-center justify-between h-20">

                        <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16 object-contain">

                        </a>

                        <nav class="hidden lg:flex items-center gap-8 text-sm font-medium text-[#DCEAE5]">
                            <a href="#alur" class="hover:text-white transition-colors">Alur Layanan</a>
                            <a href="#peran" class="hover:text-white transition-colors">Peran</a>
                            <a href="#fitur" class="hover:text-white transition-colors">Fitur</a>
                            <a href="#tumbuh-kembang" class="hover:text-white transition-colors">Tumbuh Kembang</a>
                            <a href="#edukasi" class="hover:text-white transition-colors">Edukasi</a>
                            <a href="#faq" class="hover:text-white transition-colors">FAQ</a>
                        </nav>

                       <!-- @if (Route::has('login'))
                    

                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center rounded-full bg-[#D9A441] px-5 py-2.5 text-sm font-semibold text-[#16413A] hover:bg-[#EAB65C] transition-colors">
                                        Login
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-sm font-medium text-[#DCEAE5] hover:text-white transition-colors">Masuk</a>
                                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-full bg-[#D9A441] px-5 py-2.5 text-sm font-semibold text-[#16413A] hover:bg-[#EAB65C] transition-colors">
                                        Login
                                    </a>
                                @endauth
                            </div>
                        @endif -->

                        <button @click="mobileOpen = !mobileOpen" class="lg:hidden h-10 w-10 flex items-center justify-center text-white">
                            <svg x-show="!mobileOpen" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                            <svg x-show="mobileOpen" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
                        </button>
                    </div>
                </div>

                <div x-show="mobileOpen" x-cloak class="lg:hidden bg-[#16413A] border-t border-[#2C6459] px-6 py-5 space-y-4">
                    <a href="#alur" class="block text-[#DCEAE5] font-medium">Alur Layanan</a>
                    <a href="#peran" class="block text-[#DCEAE5] font-medium">Peran</a>
                    <a href="#fitur" class="block text-[#DCEAE5] font-medium">Fitur</a>
                    <a href="#tumbuh-kembang" class="block text-[#DCEAE5] font-medium">Tumbuh Kembang</a>
                    <a href="#edukasi" class="block text-[#DCEAE5] font-medium">Edukasi</a>
                    <a href="#faq" class="block text-[#DCEAE5] font-medium">FAQ</a>
                    @if (Route::has('login'))
                        <div class="flex gap-3 pt-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="flex-1 text-center rounded-full bg-[#D9A441] py-2.5 text-sm font-semibold text-[#16413A]">Dashboard</a>
                            @else
                                <!-- <a href="{{ route('login') }}" class="flex-1 text-center rounded-full border border-[#2C6459] py-2.5 text-sm font-medium text-white">Masuk</a>
                                <a href="{{ route('login') }}" class="flex-1 text-center rounded-full bg-[#D9A441] py-2.5 text-sm font-semibold text-[#16413A]">Login</a> -->
                            @endauth
                        </div>
                    @endif
                </div>
            </header>

            {{-- ============ HERO ============ --}}
            <section class="relative overflow-hidden dot-grid">
                <div class="max-w-7xl mx-auto px-6 lg:px-10 py-16 lg:py-24">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">

                        <div>
                            <div class="inline-flex items-center gap-2 text-xs font-mono tracking-[0.18em] uppercase text-[#1F5D53]">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#1F5D53] opacity-60"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#1F5D53]"></span>
                                </span>
                                Sistem Informasi Posyandu Terpadu
                            </div>

                            <h1 class="font-display font-semibold text-[#0F766E] text-4xl sm:text-5xl lg:text-[3.4rem] leading-[1.08] mt-6">
                                Satu data posyandu digital untuk Kader, Admin, dan
                                <span class="text-[#0F766E]/35">Balita.</span>
                            </h1>

                            <p class="mt-6 text-[#3E5650] text-lg leading-relaxed max-w-xl">
                                Dari pendaftaran balita, penimbangan tiap bulan, pencatatan imunisasi, sampai laporan gizi ke Puskesmas — semua tercatat rapi, tidak lagi tercecer di buku KIA kertas.
                            </p>

                            <div class="mt-8 flex flex-wrap gap-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center rounded-full bg-[#16413A] px-7 py-3.5 text-sm font-semibold text-white hover:bg-[#1E5147] transition-colors">
                                        Buka Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-full bg-[#0F766E] px-7 py-3.5 text-sm font-semibold text-white hover:bg-[#0F766E] transition-colors">
                                        Login 
                                    </a>
                                @endauth
                                <a href="#alur" class="inline-flex items-center rounded-full border border-[#16413A]/25 px-7 py-3.5 text-sm font-semibold text-[#0F766E] hover:bg-white transition-colors">
                                    Lihat Alur Layanan
                                </a>
                            </div>

                            <div class="mt-10 grid grid-cols-3 gap-3 max-w-lg">
                                <div class="bg-white rounded-2xl border border-[#E7DFD2] px-4 py-4">
                                    <div class="h-9 w-9 rounded-xl bg-[#EFE3C8] flex items-center justify-center text-base">👩‍⚕️</div>
                                    <p class="mt-2 font-display font-semibold text-lg text-[#16413A]">2 peran</p>
                                    <p class="text-xs text-[#6B7C77]">pengguna sistem</p>
                                </div>
                                <div class="bg-white rounded-2xl border border-[#E7DFD2] px-4 py-4">
                                    <div class="h-9 w-9 rounded-xl bg-[#DDEBE6] flex items-center justify-center text-base">🔒</div>
                                    <p class="mt-2 font-display font-semibold text-lg text-[#16413A]">2x</p>
                                    <p class="text-xs text-[#6B7C77]">verifikasi berjenjang</p>
                                </div>
                                <div class="bg-white rounded-2xl border border-[#E7DFD2] px-4 py-4">
                                    <div class="h-9 w-9 rounded-xl bg-[#F3DCD6] flex items-center justify-center text-base">📄</div>
                                    <p class="mt-2 font-display font-semibold text-lg text-[#16413A]">PDF/XLS</p>
                                    <p class="text-xs text-[#6B7C77]">ekspor laporan</p>
                                </div>
                            </div>
                        </div>

                        <div class="relative mt-4 lg:mt-0">
                            <div class="bg-white rounded-3xl border border-[#E7DFD2] shadow-[0_30px_60px_-25px_rgba(22,65,58,0.35)] p-7">
                                <div class="flex items-center justify-between border-b border-dashed border-[#E7DFD2] pb-4">
                                    <p class="font-mono text-xs tracking-[0.14em] uppercase text-[#6B7C77]">Kartu KMS · SIPANDU</p>
                                    <p class="font-mono text-xs text-[#6B7C77]">No. 0123/BALITA</p>
                                </div>

                                <ul class="mt-4 divide-y divide-[#F0EBE0]">
                                    <li class="flex items-center justify-between py-3.5">
                                        <span class="flex items-center gap-3">
                                            <span class="font-mono text-xs text-[#1F5D53]">01</span>
                                            <span class="text-sm text-[#223330]">Pendaftaran balita baru</span>
                                        </span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3F8F5F" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                                    </li>
                                    <li class="flex items-center justify-between py-3.5">
                                        <span class="flex items-center gap-3">
                                            <span class="font-mono text-xs text-[#1F5D53]">02</span>
                                            <span class="text-sm text-[#223330]">Verifikasi data oleh kader</span>
                                        </span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3F8F5F" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                                    </li>
                                    <li class="flex items-center justify-between py-3.5">
                                        <span class="flex items-center gap-3">
                                            <span class="font-mono text-xs text-[#1F5D53]">03</span>
                                            <span class="text-sm text-[#223330]">Penimbangan — BB 12.4 kg, TB 85 cm</span>
                                        </span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3F8F5F" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                                    </li>
                                    <li class="flex items-center justify-between py-3.5">
                                        <span class="flex items-center gap-3">
                                            <span class="font-mono text-xs text-[#1F5D53]">04</span>
                                            <span class="text-sm text-[#223330]">Pencatatan imunisasi</span>
                                        </span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3F8F5F" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                                    </li>
                                    <li class="flex items-center justify-between py-3.5">
                                        <span class="flex items-center gap-3">
                                            <span class="font-mono text-xs text-[#1F5D53]">05</span>
                                            <span class="text-sm text-[#223330]">Rekomendasi gizi dari kader</span>
                                        </span>
                                        <span class="text-xs font-mono text-[#B79A50]">…</span>
                                    </li>
                                </ul>

                                <div class="mt-2 pt-3 border-t border-dashed border-[#E7DFD2]">
                                    <p class="text-xs text-[#A9B4AF]">Posyandu Melati · RW 05</p>
                                </div>
                            </div>

                            <div class="absolute -left-6 -bottom-8 bg-[#0F766E] rounded-2xl px-5 py-4 w-44 shadow-xl hidden sm:block">
                                <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-[#A9C9C0]">Status Gizi</p>
                                <p class="font-display font-semibold text-xl text-white mt-1">Baik</p>
                                <svg class="mt-2" width="140" height="28" viewBox="0 0 140 28" fill="none">
                                    <path d="M2 22 L30 16 L55 20 L80 8 L105 12 L138 3" stroke="#D9A441" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <div class="absolute -right-5 -bottom-6 h-24 w-24 rounded-full bg-[#FBF6EF] border-2 border-[#3F8F5F] flex items-center justify-center hidden sm:flex">
                                <p class="font-mono text-[9px] font-medium uppercase tracking-tight text-[#3F8F5F] text-center leading-tight px-2">Terverifikasi<br>Kader</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ============ PERAN ============ --}}
            <section id="peran" class="bg-white border-y border-[#E7DFD2]">
                <div class="max-w-7xl mx-auto px-6 lg:px-10 py-20">
                    <div class="max-w-2xl">
                        <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#1F5D53]">Peran Pengguna</p>
                        <h2 class="font-display font-semibold text-3xl lg:text-4xl text-[#0F766E] mt-3">Dibangun untuk dua peran yang bekerja tiap posyandu.</h2>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mt-12">
                        <div class="rounded-3xl border border-[#E7DFD2] p-8 bg-[#FBF6EF]">
                            <span class="inline-flex h-12 w-12 rounded-2xl bg-[#DDEBE6] items-center justify-center text-xl">🗂️</span>
                            <h3 class="font-display font-semibold text-xl text-[#0F766E] mt-5">Admin</h3>
                            <p class="text-sm text-[#3E5650] mt-2 leading-relaxed">Mengelola data seluruh posyandu di wilayahnya: memverifikasi kader, memantau rekap laporan bulanan, dan mengunduh laporan gizi untuk Puskesmas.</p>
                            <ul class="mt-5 space-y-2.5 text-sm text-[#223330]">
                                <li class="flex gap-2"><span class="text-[#1F5D53]">›</span> Verifikasi akun kader baru</li>
                                <li class="flex gap-2"><span class="text-[#1F5D53]">›</span> Rekap data seluruh posyandu</li>
                                <li class="flex gap-2"><span class="text-[#1F5D53]">›</span> Ekspor laporan PDF/Excel</li>
                            </ul>
                        </div>

                        <div class="rounded-3xl border border-[#E7DFD2] p-8 bg-[#FBF6EF]">
                            <span class="inline-flex h-12 w-12 rounded-2xl bg-[#F3DCD6] items-center justify-center text-xl">👩‍⚕️</span>
                            <h3 class="font-display font-semibold text-xl text-[#0F766E] mt-5">Kader</h3>
                            <p class="text-sm text-[#3E5650] mt-2 leading-relaxed">Mencatat kegiatan posyandu langsung dari lapangan: pendaftaran balita, hasil penimbangan bulanan, dan status imunisasi tiap anak.</p>
                            <ul class="mt-5 space-y-2.5 text-sm text-[#223330]">
                                <li class="flex gap-2"><span class="text-[#1F5D53]">›</span> Daftarkan balita & ibu hamil</li>
                                <li class="flex gap-2"><span class="text-[#1F5D53]">›</span> Input penimbangan & pengukuran</li>
                                <li class="flex gap-2"><span class="text-[#1F5D53]">›</span> Catat imunisasi & penyuluhan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ============ FITUR ============ --}}
            <section id="fitur" class="max-w-7xl mx-auto px-6 lg:px-10 py-20">
                <div class="max-w-2xl">
                    <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#1F5D53]">Fitur</p>
                    <h2 class="font-display font-semibold text-3xl lg:text-4xl text-[#0F766E] mt-3">Semua kebutuhan pencatatan posyandu, dalam satu tempat.</h2>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-12">
                    @foreach([
                        ['icon' => '👶', 'title' => 'Pendaftaran Balita & Bumil', 'desc' => 'Data anak dan ibu hamil tersimpan lengkap dengan riwayat kunjungan.'],
                        ['icon' => '⚖️', 'title' => 'Penimbangan & Pengukuran', 'desc' => 'Catat berat badan, tinggi badan, dan lingkar lengan tiap bulan.'],
                        ['icon' => '📈', 'title' => 'Grafik Tumbuh Kembang', 'desc' => 'Pantau kurva pertumbuhan otomatis berdasarkan standar KMS.'],
                        ['icon' => '💉', 'title' => 'Jadwal & Riwayat Imunisasi', 'desc' => 'Pengingat jadwal imunisasi dan status per anak, tidak ada yang terlewat.'],
                        ['icon' => '📋', 'title' => 'Laporan PDF & Excel', 'desc' => 'Unduh rekap bulanan siap kirim ke Puskesmas dalam sekali klik.'],
                        ['icon' => '📚', 'title' => 'Materi Edukasi Gizi', 'desc' => 'Bagikan materi penyuluhan gizi dan kesehatan ke warga binaan.'],
                    ] as $f)
                    <div class="rounded-2xl border border-[#E7DFD2] p-6 hover:border-[#1F5D53]/40 transition-colors">
                        <span class="text-2xl">{{ $f['icon'] }}</span>
                        <h3 class="font-display font-semibold text-[#0F766E] mt-4">{{ $f['title'] }}</h3>
                        <p class="text-sm text-[#3E5650] mt-2 leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- ============ TUMBUH KEMBANG (Animasi Grafik) ============ --}}
            <section id="tumbuh-kembang" class="bg-white border-y border-[#E7DFD2]">
                <div class="max-w-7xl mx-auto px-6 lg:px-10 py-20">
                    <div class="max-w-2xl">
                        <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#1F5D53]">Tumbuh Kembang</p>
                        <h2 class="font-display font-semibold text-3xl lg:text-4xl text-[#0F766E] mt-3">Kurva pertumbuhan balita, hidup dan mudah dipahami.</h2>
                        <p class="mt-4 text-[#3E5650] leading-relaxed">Setiap hasil penimbangan otomatis digambarkan menjadi grafik KMS. Kader dan orang tua bisa langsung melihat tren berat badan, tinggi badan, dan lingkar lengan dari bulan ke bulan.</p>
                    </div>

                    <div x-ref="tkSection" x-init="initTumbuhKembang()" :class="tkVisible ? 'tk-visible' : ''" class="grid lg:grid-cols-3 gap-6 mt-12">

                        {{-- Grafik garis animasi --}}
                        <div class="lg:col-span-2 rounded-3xl border border-[#E7DFD2] bg-[#FBF6EF] p-6 sm:p-8 relative overflow-hidden">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="font-display font-semibold text-[#16413A]">Kurva Berat Badan</p>
                                    <p class="text-xs text-[#6B7C77] font-mono">6 bulan terakhir · Ananda Putri</p>
                                </div>
                                <span class="tk-badge inline-flex items-center gap-1.5 rounded-full bg-[#DDEBE6] px-3 py-1.5 text-xs font-semibold text-[#1F5D53]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#3F8F5F]"></span> Sesuai Standar
                                </span>
                            </div>

                            <svg viewBox="0 0 600 280" class="w-full h-auto">
                                <!-- grid lines -->
                                <line x1="40" y1="30"  x2="580" y2="30"  stroke="#E7DFD2" stroke-width="1"/>
                                <line x1="40" y1="90"  x2="580" y2="90"  stroke="#E7DFD2" stroke-width="1"/>
                                <line x1="40" y1="150" x2="580" y2="150" stroke="#E7DFD2" stroke-width="1"/>
                                <line x1="40" y1="210" x2="580" y2="210" stroke="#E7DFD2" stroke-width="1"/>
                                <line x1="40" y1="30" x2="40" y2="240" stroke="#D8CFC0" stroke-width="1.5"/>
                                <line x1="40" y1="240" x2="580" y2="240" stroke="#D8CFC0" stroke-width="1.5"/>

                                <!-- zona standar (band hijau) -->
                                <path d="M40,200 C150,190 250,170 340,150 C420,135 500,120 580,100 L580,230 L40,230 Z" fill="#3F8F5F" opacity="0.08"/>

                                <!-- garis pertumbuhan -->
                                <path class="tk-line" d="M60,205 C130,195 190,178 250,165 C320,150 380,140 440,118 C490,100 530,90 560,72"
                                      fill="none" stroke="#0F766E" stroke-width="4" stroke-linecap="round"/>

                                <!-- titik data -->
                                <circle class="tk-dot" style="animation-delay:0.3s"  cx="60"  cy="205" r="7" fill="#0F766E"/>
                                <circle class="tk-dot" style="animation-delay:0.7s"  cx="190" cy="178" r="7" fill="#0F766E"/>
                                <circle class="tk-dot" style="animation-delay:1.1s"  cx="320" cy="150" r="7" fill="#0F766E"/>
                                <circle class="tk-dot" style="animation-delay:1.5s"  cx="440" cy="118" r="7" fill="#0F766E"/>
                                <circle class="tk-dot" style="animation-delay:1.9s"  cx="560" cy="72"  r="9" fill="#D9A441" stroke="#fff" stroke-width="2"/>

                                <!-- label bulan -->
                                <text x="60"  y="260" font-size="12" fill="#6B7C77" text-anchor="middle" font-family="JetBrains Mono, monospace">Jan</text>
                                <text x="190" y="260" font-size="12" fill="#6B7C77" text-anchor="middle" font-family="JetBrains Mono, monospace">Feb</text>
                                <text x="320" y="260" font-size="12" fill="#6B7C77" text-anchor="middle" font-family="JetBrains Mono, monospace">Mar</text>
                                <text x="440" y="260" font-size="12" fill="#6B7C77" text-anchor="middle" font-family="JetBrains Mono, monospace">Apr</text>
                                <text x="560" y="260" font-size="12" fill="#6B7C77" text-anchor="middle" font-family="JetBrains Mono, monospace">Mei</text>
                            </svg>
                        </div>

                        {{-- Panel statistik dengan bar animasi + counter --}}
                        <div class="rounded-3xl border border-[#E7DFD2] bg-[#16413A] p-6 sm:p-8 flex flex-col justify-between">
                            <div>
                                <p class="font-display font-semibold text-white">Ringkasan Bulan Ini</p>
                                <p class="text-xs text-[#A9C9C0] font-mono mt-1">Update otomatis dari kader</p>
                            </div>

                            <div class="mt-6 space-y-5">
                                <div>
                                    <div class="flex items-end justify-between">
                                        <span class="text-xs text-[#A9C9C0]">Berat Badan</span>
                                        <span class="font-mono text-lg text-white" x-text="bb + ' kg'"></span>
                                    </div>
                                    <div class="h-2 w-full bg-white/10 rounded-full mt-1.5 overflow-hidden">
                                        <div class="tk-bar h-full w-[78%] bg-[#D9A441] rounded-full" style="animation-delay:0.2s"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-end justify-between">
                                        <span class="text-xs text-[#A9C9C0]">Tinggi Badan</span>
                                        <span class="font-mono text-lg text-white" x-text="tb + ' cm'"></span>
                                    </div>
                                    <div class="h-2 w-full bg-white/10 rounded-full mt-1.5 overflow-hidden">
                                        <div class="tk-bar h-full w-[85%] bg-[#3F8F5F] rounded-full" style="animation-delay:0.5s"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-end justify-between">
                                        <span class="text-xs text-[#A9C9C0]">Lingkar Lengan</span>
                                        <span class="font-mono text-lg text-white" x-text="ll + ' cm'"></span>
                                    </div>
                                    <div class="h-2 w-full bg-white/10 rounded-full mt-1.5 overflow-hidden">
                                        <div class="tk-bar h-full w-[64%] bg-[#7FB6AC] rounded-full" style="animation-delay:0.8s"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-7 pt-5 border-t border-white/10">
                                <p class="text-xs text-[#A9C9C0] leading-relaxed">Grafik diperbarui otomatis setiap kader menginput hasil penimbangan baru di posyandu.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ============ EDUKASI (Video YouTube) ============ --}}
            <section id="edukasi" class="max-w-7xl mx-auto px-6 lg:px-10 py-20">
                <div class="max-w-2xl">
                    <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#1F5D53]">Edukasi</p>
                    <h2 class="font-display font-semibold text-3xl lg:text-4xl text-[#0F766E] mt-3">Video edukasi untuk ibu hamil dan balita.</h2>
                    <p class="mt-4 text-[#3E5650] leading-relaxed">Materi penyuluhan yang biasa dibagikan kader saat posyandu, bisa ditonton ulang kapan saja oleh warga binaan.</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-12">
                    @foreach([
                        [
                            'id' => 'F3eMXkkpv_A',
                            'title' => 'Kelas Ibu Hamil',
                            'desc' => 'Panduan pelaksanaan kelas ibu hamil: materi kehamilan, persalinan, dan perawatan bayi baru lahir.',
                            'tag' => 'Ibu Hamil',
                        ],
                        [
                            'id' => 'pVnvQW9MIGI',
                            'title' => 'Kesehatan dan Gizi Ibu Hamil',
                            'desc' => 'Gizi yang perlu dipenuhi selama kehamilan agar ibu dan janin sehat serta terhindar dari risiko stunting.',
                            'tag' => 'Gizi Bumil',
                        ],
                        [
                            'id' => '35tLfucTmWI',
                            'title' => 'Pentingnya Gizi Ibu Hamil dan Balita',
                            'desc' => 'Video edukasi gizi seputar #TanyaKaderPRIMA tentang mengapa gizi ibu hamil dan balita sangat penting.',
                            'tag' => 'Gizi Balita',
                        ],
                    ] as $v)
                    <div class="rounded-2xl border border-[#E7DFD2] bg-white overflow-hidden hover:border-[#1F5D53]/40 transition-colors">
                        <div class="aspect-video bg-[#16413A]">
                            <iframe
                                class="w-full h-full"
                                src="https://www.youtube-nocookie.com/embed/{{ $v['id'] }}"
                                title="{{ $v['title'] }}"
                                loading="lazy"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <div class="p-5">
                            <span class="inline-flex text-[10px] font-mono uppercase tracking-[0.14em] text-[#1F5D53] bg-[#DDEBE6] rounded-full px-2.5 py-1">{{ $v['tag'] }}</span>
                            <h3 class="font-display font-semibold text-[#0F766E] mt-3">{{ $v['title'] }}</h3>
                            <p class="text-sm text-[#3E5650] mt-2 leading-relaxed">{{ $v['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- ============ ALUR LAYANAN ============ --}}
            <section id="alur" class="bg-[#0F766E]">
                <div class="max-w-7xl mx-auto px-6 lg:px-10 py-20">
                    <div class="max-w-2xl">
                        <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#A9C9C0]">Alur Layanan</p>
                        <h2 class="font-display font-semibold text-3xl lg:text-4xl text-white mt-3">Dari pendaftaran sampai laporan, alurnya jelas.</h2>
                    </div>

                    <div class="grid md:grid-cols-5 gap-6 mt-14">
                        @foreach([
                            ['no' => '01', 'title' => 'Pendaftaran', 'desc' => 'Kader mendaftarkan balita atau ibu hamil baru.'],
                            ['no' => '02', 'title' => 'Verifikasi', 'desc' => 'Admin memverifikasi kelengkapan data.'],
                            ['no' => '03', 'title' => 'Pemeriksaan', 'desc' => 'Kader mencatat hasil penimbangan bulanan.'],
                            ['no' => '04', 'title' => 'Tindak Lanjut', 'desc' => 'Imunisasi, rujukan, atau penyuluhan gizi.'],
                            ['no' => '05', 'title' => 'Pelaporan', 'desc' => 'Admin mengekspor rekap ke Puskesmas.'],
                        ] as $step)
                        <div>
                            <p class="font-mono text-sm text-[#D9A441]">{{ $step['no'] }}</p>
                            <h3 class="font-display font-semibold text-white mt-3">{{ $step['title'] }}</h3>
                            <p class="text-sm text-[#A9C9C0] mt-2 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- ============ FAQ ============ --}}
            <section id="faq" class="max-w-4xl mx-auto px-6 lg:px-10 py-20">
                <div class="text-center max-w-xl mx-auto">
                    <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#1F5D53]">FAQ</p>
                    <h2 class="font-display font-semibold text-3xl text-[#0F766E] mt-3">Pertanyaan yang sering diajukan</h2>
                </div>

                <div class="mt-10 space-y-3">
                    @foreach([
                        ['q' => 'Siapa saja yang bisa mengakses sistem ini?', 'a' => 'Hanya Admin dan Kader posyandu yang terdaftar. Akun dibuat oleh Admin, bukan pendaftaran umum.'],
                        ['q' => 'Apakah data balita aman?', 'a' => 'Ya. Setiap data melalui verifikasi berjenjang dan hanya bisa diakses oleh peran yang berwenang.'],
                        ['q' => 'Bisakah laporan diunduh untuk Puskesmas?', 'a' => 'Bisa. Admin dapat mengekspor rekap bulanan dalam format PDF maupun Excel.'],
                    ] as $i => $item)
                    <div class="rounded-2xl border border-[#E7DFD2] overflow-hidden">
                        <button @click="openFaq = openFaq === {{ $i }} ? null : {{ $i }}" class="w-full flex items-center justify-between px-6 py-4 text-left">
                            <span class="font-medium text-[#0F766E]">{{ $item['q'] }}</span>
                            <span x-text="openFaq === {{ $i }} ? '−' : '+'" class="text-[#1F5D53] text-lg"></span>
                        </button>
                        <div x-show="openFaq === {{ $i }}" x-cloak class="px-6 pb-4 text-sm text-[#0F766E] leading-relaxed">
                            {{ $item['a'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- ============ FOOTER CTA ============ --}}
            <section class="bg-[#FBF6EF] border-t border-[#E7DFD2]">
                <div class="max-w-7xl mx-auto px-6 lg:px-10 py-16 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h3 class="font-display font-semibold text-2xl text-[#0F766E]">Siap digitalisasi posyandu Anda?</h3>
                        <p class="text-sm text-[#3E5650] mt-1">Masuk sebagai Admin atau Kader untuk mulai mencatat.</p>
                    </div>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center rounded-full bg-[#16413A] px-7 py-3.5 text-sm font-semibold text-white hover:bg-[#1E5147] transition-colors shrink-0">
                            Buka Dashboard
                        </a>
                    @else
                        
                    @endauth
                </div>
                <div class="border-t border-[#E7DFD2]">
                    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-6 text-xs text-[#0F766E]">
                        © {{ date('Y') }} SIPANDU — Sistem Informasi Posyandu Terpadu.
                    </div>
                </div>
            </section>

        </div>
    </body>
</html>
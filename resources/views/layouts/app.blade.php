<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Monitoring Stunting' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-white text-gray-800 font-sans antialiased"
      x-data="{ sidebarOpen: false }">

    <div class="flex h-full">

        {{-- Sidebar --}}
        <aside class="w-64 bg-[#0F766E] border-r border-[#0C5C55] flex flex-col flex-shrink-0
                       hidden lg:flex">
            {{-- Logo --}}
            <div class="px-6 py-5 border-b border-[#0C5C55]">
                <span class="text-sm font-semibold tracking-widest uppercase text-white">
                    Stunting Monitor
                </span>
                <p class="text-xs text-teal-100/70 mt-0.5">
                    {{ auth()->user()->posyandu?->nama ?? 'Puskesmas' }}
                </p>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                @if(auth()->user()->isAdmin())
                    <x-nav-link route="admin.dashboard"  icon="squares-2x2" label="Dashboard" />
                    <x-nav-link route="admin.posyandu.index" icon="building-office" label="Posyandu" />
                    <x-nav-link route="admin.users.index" icon="users"        label="Kader" />
                    <x-nav-link route="admin.laporan.index" icon="document-chart-bar" label="Laporan" />
                @else
                    <x-nav-link route="kader.dashboard"       icon="squares-2x2" label="Dashboard" />
                    <x-nav-link route="kader.balita.index"    icon="user-group"   label="Data Balita" />
                @endif
            </nav>

            {{-- User info --}}
            <div class="px-4 py-4 border-t border-[#0C5C55]">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-white/15 flex items-center
                                justify-content-center text-white text-xs font-semibold
                                shrink-0 flex justify-center items-center">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-white truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs text-teal-100/70 capitalize">
                            {{ auth()->user()->role }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-teal-100/70 hover:text-rose-200 transition-colors"
                                title="Logout">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-white">

            {{-- Topbar mobile --}}
            <header class="lg:hidden flex items-center justify-between
                           px-4 py-3 bg-[#0F766E] border-b border-[#0C5C55]">
                <span class="text-sm font-semibold text-white tracking-widest uppercase">
                    Stunting Monitor
                </span>
                <button @click="sidebarOpen = true" class="text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </header>

            {{-- Flash message --}}
            @if(session('success'))
            <div class="mx-6 mt-4 px-4 py-3 rounded-lg bg-teal-50 border border-teal-200
                        text-teal-700 text-sm flex items-center gap-2"
                 x-data x-init="setTimeout(() => $el.remove(), 4000)">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto p-6 bg-white">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
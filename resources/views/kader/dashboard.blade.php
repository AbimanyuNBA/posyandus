<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 tracking-wide">
                {{ __('Dashboard Kader') }} <span class="text-gray-300 mx-2">|</span> <span class="text-teal-600">{{ $posyandu?->nama ?? 'Posyandu' }}</span>
            </h2>
            <div class="mt-2 sm:mt-0 text-sm text-gray-500 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    @php
        // Dynamic Greeting
        $hour = now()->format('H');
        if ($hour < 12) $greeting = 'Selamat pagi';
        elseif ($hour < 15) $greeting = 'Selamat siang';
        elseif ($hour < 18) $greeting = 'Selamat sore';
        else $greeting = 'Selamat malam';

        // Calculate Progress
        $percentage = $stats['total_balita'] > 0 
            ? round(($stats['diukur_bulan'] / $stats['total_balita']) * 100) 
            : 0;
    @endphp

    <div class="py-12 bg-gray-50">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
            
            <div x-data="{ show: true }" x-show="show" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-4"
                 class="flex items-start justify-between p-5 border border-teal-100 rounded-2xl bg-teal-50 shadow-sm shadow-teal-100/50">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-teal-100 text-teal-600 rounded-xl relative">
                        <div class="absolute inset-0 bg-teal-400 animate-ping opacity-20 rounded-xl"></div>
                        <svg class="w-6 h-6 text-teal-600 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-teal-900">{{ $greeting }}, Kader!</h3>
                        <p class="text-sm text-teal-800/80 mt-1">Pantau terus pertumbuhan balita di wilayah Anda. Pastikan data pengukuran bulan ini segera diinput untuk pelaporan yang akurat.</p>
                    </div>
                </div>
                <button @click="show = false" class="p-1 text-teal-400 hover:text-teal-600 transition-colors rounded-lg hover:bg-teal-100/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="p-6 border bg-white border-gray-200 rounded-2xl flex flex-col justify-between hover:-translate-y-1 hover:border-gray-300 transition-all duration-300 shadow-sm">
                    <div class="flex justify-between items-start">
                        <p class="text-sm font-medium text-gray-500">Total Balita Terdaftar</p>
                        <div class="p-2.5 bg-gray-100 rounded-xl">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-800 mt-6">{{ $stats['total_balita'] }}</h3>
                </div>

                <div class="p-6 border bg-white border-gray-200 rounded-2xl flex flex-col justify-between relative overflow-hidden hover:-translate-y-1 hover:border-teal-300 transition-all duration-300 shadow-sm group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-teal-50 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2 group-hover:bg-teal-100 transition-all duration-500"></div>
                    
                    <div class="flex justify-between items-start relative z-10">
                        <p class="text-sm font-medium text-gray-500">Diukur Bulan Ini</p>
                        <div class="p-2.5 bg-teal-50 rounded-xl border border-teal-100">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                    </div>
                    
                    <div class="relative z-10 mt-6">
                        <div class="flex items-baseline gap-2 mb-3">
                            <h3 class="text-4xl font-bold text-teal-600">{{ $stats['diukur_bulan'] }}</h3>
                            <span class="text-sm text-gray-400">/ {{ $stats['total_balita'] }} anak</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 text-right font-medium">{{ $percentage }}% Tercapai</p>
                    </div>
                </div>

                <div class="p-6 border rounded-2xl flex flex-col justify-between relative overflow-hidden hover:-translate-y-1 transition-all duration-300 shadow-sm 
                    {{ $stats['stunting'] > 0 ? 'bg-rose-50 border-rose-200 shadow-rose-100/30' : 'bg-white border-gray-200 hover:border-gray-300' }}">
                    
                    @if($stats['stunting'] > 0)
                        <div class="absolute top-0 right-0 w-32 h-32 bg-rose-200/30 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                    @endif

                    <div class="flex justify-between items-start relative z-10">
                        <p class="text-sm font-medium {{ $stats['stunting'] > 0 ? 'text-rose-800' : 'text-gray-500' }}">Indikasi Stunting (Bulan Ini)</p>
                        <div class="p-2.5 rounded-xl border {{ $stats['stunting'] > 0 ? 'bg-rose-100 border-rose-200' : 'bg-gray-100 border-transparent' }}">
                            <svg class="w-5 h-5 {{ $stats['stunting'] > 0 ? 'text-rose-600 animate-pulse' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                    <div class="relative z-10 mt-6">
                        <h3 class="text-4xl font-bold {{ $stats['stunting'] > 0 ? 'text-rose-600' : 'text-gray-800' }}">
                            {{ $stats['stunting'] }}
                        </h3>
                        @if($stats['stunting'] > 0)
                            <p class="text-xs text-rose-700/80 mt-2 font-medium">Butuh perhatian khusus & validasi Puskesmas</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                
                <div class="lg:col-span-2 border bg-white border-gray-200 rounded-2xl overflow-hidden flex flex-col shadow-sm">
                    <div class="p-5 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <h3 class="text-base font-semibold text-gray-800">Data Balita Terakhir Diinput</h3>
                        <a href="{{ route('kader.balita.index') }}" class="text-sm font-medium text-teal-600 hover:text-teal-500 hover:underline underline-offset-4 transition-all">Lihat Semua Data</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Nama Balita</th>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Usia</th>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Ukur Terakhir</th>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($balita_terbaru as $balita)
                                    <tr class="hover:bg-gray-50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-800 group-hover:text-teal-600 transition-colors">{{ $balita->nama }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">NIK: {{ $balita->nik }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-gray-700 text-xs font-medium">
                                                {{ \Carbon\Carbon::parse($balita->tanggal_lahir)->diffInMonths(now()) }} Bulan
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($balita->pengukuranTerakhir)
                                                <span class="text-gray-700">
                                                    {{ $balita->pengukuranTerakhir->tanggal_ukur->format('d M Y') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic text-xs">Belum ada data</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('kader.balita.show', $balita->id) }}" class="inline-flex items-center justify-center p-2 text-teal-600 hover:text-teal-500 hover:bg-teal-50 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="p-3 bg-gray-100 rounded-full mb-3">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                                </div>
                                                <p class="text-sm font-medium text-gray-500">Belum ada data balita terdaftar.</p>
                                                <p class="text-xs text-gray-400 mt-1">Silakan tambah balita baru untuk memulai.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="border bg-white border-gray-200 rounded-2xl p-5 h-fit shadow-sm">
                    <h3 class="text-base font-semibold text-gray-800 mb-5">Aksi Cepat</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('kader.balita.index') }}" class="group flex items-center p-4 border border-teal-100 bg-teal-50 rounded-xl hover:bg-teal-100/50 hover:border-teal-200 transition-all duration-300">
                            <div class="p-3 bg-teal-100 text-teal-600 rounded-xl group-hover:scale-105 group-hover:bg-teal-600 group-hover:text-white transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-bold text-teal-900 transition-colors">Input Pengukuran</p>
                                <p class="text-xs text-teal-700/80 mt-0.5">Pilih balita dari daftar data</p>
                            </div>
                        </a>

                        <a href="{{ route('kader.balita.create') }}" class="group flex items-center p-4 border border-gray-200 bg-gray-50 rounded-xl hover:bg-gray-100 hover:border-gray-300 transition-all duration-300">
                            <div class="p-3 bg-white border border-gray-200 text-gray-600 rounded-xl group-hover:scale-105 group-hover:bg-gray-700 group-hover:text-white group-hover:border-transparent transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-bold text-gray-700 group-hover:text-gray-900 transition-colors">Tambah Balita</p>
                                <p class="text-xs text-gray-400 mt-0.5">Registrasi data anak baru</p>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
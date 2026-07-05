<x-app-layout>
<x-slot name="title">Manajemen Posyandu</x-slot>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-semibold text-[#0F766E]">Manajemen Posyandu</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $posyandu->total() }} posyandu terdaftar</p>
    </div>
    <a href="{{ route('admin.posyandu.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-[#0F766E] hover:bg-[#0C5C55]
              text-white text-sm font-medium rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Posyandu
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Kelurahan</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Kecamatan</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Balita</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($posyandu as $p)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center
                                    justify-center shrink-0">
                            <svg class="w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-800 font-medium">{{ $p->nama }}</p>
                            @if($p->kontak)
                                <p class="text-xs text-gray-500">{{ $p->kontak }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3.5 text-gray-500 hidden md:table-cell">{{ $p->kelurahan }}</td>
                <td class="px-5 py-3.5 text-gray-500 hidden lg:table-cell">{{ $p->kecamatan }}</td>
                <td class="px-5 py-3.5 text-center">
                    <span class="text-sm font-medium text-gray-700">{{ $p->balita_count }}</span>
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.posyandu.edit', $p) }}"
                           class="text-xs px-3 py-1.5 bg-gray-100 hover:bg-gray-200
                                  text-gray-600 hover:text-gray-800 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.posyandu.destroy', $p) }}"
                              onsubmit="return confirm('Hapus posyandu {{ $p->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs px-3 py-1.5 bg-red-50 hover:bg-red-100
                                           text-red-600 rounded-lg transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-gray-500 text-sm">
                    Belum ada posyandu.
                    <a href="{{ route('admin.posyandu.create') }}" class="text-teal-600 hover:underline">
                        Tambah sekarang
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($posyandu->hasPages())
    <div class="px-5 py-3 border-t border-gray-200">
        {{ $posyandu->links() }}
    </div>
    @endif
</div>
</x-app-layout>
<x-app-layout>
<x-slot name="title">Manajemen Kader</x-slot>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-semibold text-gray-800">Manajemen Kader</h1>
        <p class="text-sm text-gray-500 mt-0.5">Kelola akun kader per posyandu</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-500
              text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kader
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200 bg-gray-50">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">Email</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">Posyandu</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">Bergabung</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 border border-teal-100 flex
                                    items-center justify-center text-xs font-semibold shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="text-gray-800 font-medium">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-5 py-3.5 text-gray-600 hidden md:table-cell">{{ $user->email }}</td>
                <td class="px-5 py-3.5 hidden lg:table-cell">
                    @if($user->posyandu)
                        <span class="text-xs px-2.5 py-1 rounded-full bg-blue-50 text-blue-600 border border-blue-100 font-medium">
                            {{ $user->posyandu->nama }}
                        </span>
                    @else
                        <span class="text-gray-400 text-xs italic">Belum di-assign</span>
                    @endif
                </td>
                <td class="px-5 py-3.5 text-gray-500 text-xs hidden lg:table-cell">
                    {{ $user->created_at->format('d M Y') }}
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="text-xs px-3 py-1.5 bg-gray-100 hover:bg-gray-200
                                  text-gray-700 rounded-lg transition-colors border border-gray-200">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Hapus akun kader {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs px-3 py-1.5 bg-red-50 hover:bg-red-100
                                           text-red-600 rounded-lg transition-colors border border-red-100">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-gray-400 text-sm">
                    Belum ada kader terdaftar.
                    <a href="{{ route('admin.users.create') }}" class="text-teal-600 hover:underline ml-1 font-medium">
                        Tambah sekarang
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="px-5 py-3 border-t border-gray-200 bg-gray-50">
        {{ $users->links() }}
    </div>
    @endif
</div>
</x-app-layout>
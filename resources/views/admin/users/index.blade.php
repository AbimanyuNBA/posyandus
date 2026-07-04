<x-app-layout>
<x-slot name="title">Manajemen Kader</x-slot>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-semibold text-white">Manajemen Kader</h1>
        <p class="text-sm text-gray-500 mt-0.5">Kelola akun kader per posyandu</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 hover:bg-teal-400
              text-gray-950 text-sm font-medium rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kader
    </a>
</div>

<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-800">
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Email</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Posyandu</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Bergabung</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($users as $user)
            <tr class="hover:bg-gray-800/40 transition-colors">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-teal-500/10 text-teal-400 flex
                                    items-center justify-center text-xs font-semibold shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="text-gray-200 font-medium">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-5 py-3.5 text-gray-400 hidden md:table-cell">{{ $user->email }}</td>
                <td class="px-5 py-3.5 hidden lg:table-cell">
                    @if($user->posyandu)
                        <span class="text-xs px-2.5 py-1 rounded-full bg-blue-500/10 text-blue-400">
                            {{ $user->posyandu->nama }}
                        </span>
                    @else
                        <span class="text-gray-600 text-xs">Belum di-assign</span>
                    @endif
                </td>
                <td class="px-5 py-3.5 text-gray-500 text-xs hidden lg:table-cell">
                    {{ $user->created_at->format('d M Y') }}
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="text-xs px-3 py-1.5 bg-gray-800 hover:bg-gray-700
                                  text-gray-400 hover:text-gray-200 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Hapus akun kader {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20
                                           text-red-400 rounded-lg transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-gray-500 text-sm">
                    Belum ada kader terdaftar.
                    <a href="{{ route('admin.users.create') }}" class="text-teal-400 hover:underline">
                        Tambah sekarang
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="px-5 py-3 border-t border-gray-800">
        {{ $users->links() }}
    </div>
    @endif
</div>
</x-app-layout>
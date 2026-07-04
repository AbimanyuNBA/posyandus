<x-app-layout>
<x-slot name="title">Edit Kader — {{ $user->name }}</x-slot>

<div class="max-w-xl">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="text-xs text-gray-500 hover:text-gray-300 transition-colors">← Kembali</a>
        <h1 class="text-xl font-semibold text-white mt-2">Edit Akun Kader</h1>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5">Nama lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5
                              text-sm text-gray-200 focus:outline-none focus:border-teal-500
                              transition-colors @error('name') border-red-500 @enderror">
                @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5
                              text-sm text-gray-200 focus:outline-none focus:border-teal-500
                              transition-colors @error('email') border-red-500 @enderror">
                @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5">Posyandu</label>
                <select name="posyandu_id"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5
                               text-sm text-gray-200 focus:outline-none focus:border-teal-500 transition-colors">
                    <option value="">Pilih posyandu</option>
                    @foreach($posyandu as $p)
                        <option value="{{ $p->id }}"
                            {{ old('posyandu_id', $user->posyandu_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} — {{ $p->kelurahan }}
                        </option>
                    @endforeach
                </select>
                @error('posyandu_id') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            {{-- Password opsional --}}
            <div class="pt-2 border-t border-gray-800">
                <p class="text-xs text-gray-500 mb-3">
                    Kosongkan jika tidak ingin mengubah password
                </p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Password baru</label>
                        <input type="password" name="password"
                               placeholder="Min. 8 karakter"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5
                                      text-sm text-gray-200 placeholder-gray-600 focus:outline-none
                                      focus:border-teal-500 transition-colors
                                      @error('password') border-red-500 @enderror">
                        @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Konfirmasi</label>
                        <input type="password" name="password_confirmation"
                               placeholder="Ulangi password"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5
                                      text-sm text-gray-200 placeholder-gray-600 focus:outline-none
                                      focus:border-teal-500 transition-colors">
                    </div>
                </div>
            </div>

        </div>

        <div class="flex gap-3 mt-4">
            <button type="submit"
                    class="flex-1 py-2.5 bg-teal-500 hover:bg-teal-400 text-gray-950
                           text-sm font-medium rounded-lg transition-colors">
                Simpan perubahan
            </button>
            <a href="{{ route('admin.users.index') }}"
               class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-400
                      text-sm rounded-lg transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
</x-app-layout>
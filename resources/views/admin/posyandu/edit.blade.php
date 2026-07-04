<x-app-layout>
<x-slot name="title">Edit Posyandu</x-slot>

<div class="max-w-xl">
    <div class="mb-6">
        <a href="{{ route('admin.posyandu.index') }}"
           class="text-xs text-gray-500 hover:text-gray-300 transition-colors">← Kembali</a>
        <h1 class="text-xl font-semibold text-white mt-2">Edit Posyandu</h1>
    </div>

    <form method="POST" action="{{ route('admin.posyandu.update', $posyandu) }}">
        @csrf @method('PUT')
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5">Nama posyandu</label>
                <input type="text" name="nama" value="{{ old('nama', $posyandu->nama) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5
                              text-sm text-gray-200 focus:outline-none focus:border-teal-500
                              transition-colors @error('nama') border-red-500 @enderror">
                @error('nama') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5">Alamat</label>
                <input type="text" name="alamat" value="{{ old('alamat', $posyandu->alamat) }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5
                              text-sm text-gray-200 focus:outline-none focus:border-teal-500 transition-colors">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Kelurahan</label>
                    <input type="text" name="kelurahan" value="{{ old('kelurahan', $posyandu->kelurahan) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5
                                  text-sm text-gray-200 focus:outline-none focus:border-teal-500
                                  transition-colors @error('kelurahan') border-red-500 @enderror">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $posyandu->kecamatan) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5
                                  text-sm text-gray-200 focus:outline-none focus:border-teal-500
                                  transition-colors @error('kecamatan') border-red-500 @enderror">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Kota</label>
                    <input type="text" name="kota" value="{{ old('kota', $posyandu->kota) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5
                                  text-sm text-gray-200 focus:outline-none focus:border-teal-500
                                  transition-colors @error('kota') border-red-500 @enderror">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">No. kontak</label>
                    <input type="text" name="kontak" value="{{ old('kontak', $posyandu->kontak) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5
                                  text-sm text-gray-200 focus:outline-none focus:border-teal-500 transition-colors">
                </div>
            </div>

        </div>

        <div class="flex gap-3 mt-4">
            <button type="submit"
                    class="flex-1 py-2.5 bg-teal-500 hover:bg-teal-400 text-gray-950
                           text-sm font-medium rounded-lg transition-colors">
                Simpan perubahan
            </button>
            <a href="{{ route('admin.posyandu.index') }}"
               class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-400
                      text-sm rounded-lg transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
</x-app-layout>
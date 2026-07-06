<x-app-layout>
<x-slot name="title">Edit Balita — {{ $balita->nama }}</x-slot>

<div class="max-w-xl">
    <div class="mb-6">
        <a href="{{ route('kader.balita.show', $balita) }}"
           class="text-xs text-gray-500 hover:text-gray-700 transition-colors">
            ← Kembali
        </a>
        <h1 class="text-xl font-semibold text-gray-800 mt-2">Edit Data Balita</h1>
    </div>

    <form method="POST" action="{{ route('kader.balita.update', $balita) }}">
        @csrf
        @method('PUT')

        <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-4 shadow-sm">

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                    Nama lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama"
                       value="{{ old('nama', $balita->nama) }}"
                       placeholder="Nama balita"
                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5
                              text-sm text-gray-800 placeholder-gray-400 focus:outline-none
                              focus:border-teal-500 focus:bg-white transition-colors
                              @error('nama') border-red-500 @enderror">
                @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">NIK (opsional)</label>
                <input type="text" name="nik"
                       value="{{ old('nik', $balita->nik) }}"
                       placeholder="16 digit NIK" maxlength="16"
                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5
                              text-sm text-gray-800 placeholder-gray-400 focus:outline-none
                              focus:border-teal-500 focus:bg-white transition-colors
                              @error('nik') border-red-500 @enderror">
                @error('nik') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">
                        Tanggal lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $balita->tanggal_lahir->format('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}"
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5
                                  text-sm text-gray-800 focus:outline-none focus:border-teal-500
                                  focus:bg-white transition-colors @error('tanggal_lahir') border-red-500 @enderror">
                    @error('tanggal_lahir') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">
                        Jenis kelamin <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_kelamin"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5
                                   text-sm text-gray-800 focus:outline-none focus:border-teal-500
                                   focus:bg-white transition-colors @error('jenis_kelamin') border-red-500 @enderror">
                        <option value="">Pilih</option>
                        <option value="L" {{ old('jenis_kelamin', $balita->jenis_kelamin) === 'L' ? 'selected' : '' }}>
                            Laki-laki
                        </option>
                        <option value="P" {{ old('jenis_kelamin', $balita->jenis_kelamin) === 'P' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>
                    @error('jenis_kelamin') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">
                    Nama orang tua <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_ortu"
                       value="{{ old('nama_ortu', $balita->nama_ortu) }}"
                       placeholder="Nama ayah / ibu"
                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5
                              text-sm text-gray-800 placeholder-gray-400 focus:outline-none
                              focus:border-teal-500 focus:bg-white transition-colors
                              @error('nama_ortu') border-red-500 @enderror">
                @error('nama_ortu') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">No. HP orang tua</label>
                <input type="text" name="no_hp_ortu"
                       value="{{ old('no_hp_ortu', $balita->no_hp_ortu) }}"
                       placeholder="08xxxxxxxxxx"
                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5
                              text-sm text-gray-800 placeholder-gray-400 focus:outline-none
                              focus:border-teal-500 focus:bg-white transition-colors">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Alamat</label>
                <textarea name="alamat" rows="2" placeholder="Alamat lengkap"
                          class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5
                                 text-sm text-gray-800 placeholder-gray-400 focus:outline-none
                                 focus:border-teal-500 focus:bg-white transition-colors resize-none">{{ old('alamat', $balita->alamat) }}</textarea>
            </div>

        </div>

        <div class="flex gap-3 mt-4">
            <button type="submit"
                    class="flex-1 py-2.5 bg-teal-600 hover:bg-teal-500 text-white
                           text-sm font-medium rounded-lg transition-colors shadow-sm">
                Simpan perubahan
            </button>
            <a href="{{ route('kader.balita.show', $balita) }}"
               class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700
                      text-sm rounded-lg transition-colors border border-gray-200">
                Batal
            </a>
        </div>
    </form>
</div>
</x-app-layout>
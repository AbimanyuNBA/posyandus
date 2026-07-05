<x-app-layout>
<x-slot name="title">Input Pengukuran — {{ $balita->nama }}</x-slot>

<div class="max-w-xl">
    <div class="mb-6">
        <a href="{{ route('kader.balita.show', $balita) }}"
           class="text-xs text-gray-500 hover:text-teal-600 transition-colors">
            ← Kembali ke detail balita
        </a>
        <h1 class="text-xl font-semibold text-gray-800 mt-2">Input Pengukuran</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            {{ $balita->nama }} · {{ $balita->usiaBulanPada() }} bulan
        </p>
    </div>

    {{-- Info balita --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 mb-5 grid grid-cols-3 gap-4 shadow-sm">
        <div>
            <p class="text-xs text-gray-500 font-medium">Tanggal Lahir</p>
            <p class="text-sm text-gray-800 mt-0.5 font-medium">{{ $balita->tanggal_lahir->format('d M Y') }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Jenis Kelamin</p>
            <p class="text-sm text-gray-800 mt-0.5 font-medium">
                {{ $balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Orang Tua</p>
            <p class="text-sm text-gray-800 mt-0.5 font-medium">{{ $balita->nama_ortu }}</p>
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('kader.balita.pengukuran.store', $balita) }}"
          x-data="pengukuranForm()">
        @csrf

        <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-5 shadow-sm">

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                    Tanggal Pengukuran <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_ukur"
                       value="{{ old('tanggal_ukur', date('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}"
                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5
                              text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:bg-white
                              transition-colors @error('tanggal_ukur') border-red-500 bg-red-50/30 @enderror">
                @error('tanggal_ukur')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Berat Badan (kg) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="berat_badan" step="0.01"
                           placeholder="Contoh: 8.50" x-model="bb"
                           value="{{ old('berat_badan') }}"
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5
                                  text-sm text-gray-800 placeholder-gray-400 focus:outline-none
                                  focus:border-teal-500 focus:bg-white transition-colors
                                  @error('berat_badan') border-red-500 bg-red-50/30 @enderror">
                    @error('berat_badan')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Tinggi Badan (cm) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="tinggi_badan" step="0.1"
                           placeholder="Contoh: 72.0" x-model="tb"
                           value="{{ old('tinggi_badan') }}"
                           class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5
                                  text-sm text-gray-800 placeholder-gray-400 focus:outline-none
                                  focus:border-teal-500 focus:bg-white transition-colors
                                  @error('tinggi_badan') border-red-500 bg-red-50/30 @enderror">
                    @error('tinggi_badan')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Warning validasi range Alpine.js Theme Terang --}}
            <div x-show="showWarning" x-transition
                 class="p-3 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 text-xs font-medium">
                <span x-text="warningMsg"></span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                    Catatan (opsional)
                </label>
                <textarea name="catatan" rows="2"
                          placeholder="Kondisi saat pengukuran, keterangan tambahan..."
                          class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5
                                 text-sm text-gray-800 placeholder-gray-400 focus:outline-none
                                 focus:border-teal-500 focus:bg-white transition-colors resize-none">{{ old('catatan') }}</textarea>
            </div>
        </div>

        <div class="flex gap-3 mt-4">
            <button type="submit"
                    class="flex-1 py-2.5 bg-teal-600 hover:bg-teal-500 text-white
                           text-sm font-semibold rounded-lg transition-colors shadow-sm">
                Simpan & Hitung Z-Score
            </button>
            <a href="{{ route('kader.balita.show', $balita) }}"
               class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700
                      text-sm font-medium rounded-lg transition-colors shadow-sm">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
function pengukuranForm() {
    return {
        bb: '', tb: '',
        get showWarning() {
            const b = parseFloat(this.bb), t = parseFloat(this.tb);
            if (!b && !t) return false;
            return (b && (b < 0.5 || b > 50)) || (t && (t < 30 || t > 150));
        },
        get warningMsg() {
            const b = parseFloat(this.bb), t = parseFloat(this.tb);
            if (b && (b < 0.5 || b > 50)) return 'Berat badan di luar rentang normal (0.5 – 50 kg). Periksa kembali.';
            if (t && (t < 30 || t > 150)) return 'Tinggi badan di luar rentang normal (30 – 150 cm). Periksa kembali.';
            return '';
        }
    }
}
</script>
</x-app-layout>
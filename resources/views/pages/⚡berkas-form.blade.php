<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Berkas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    use WithFileUploads;

    public ?int $berkasId = null;
    public string $no_rm = '';
    public string $nama_pasien = '';
    public ?string $tgl_lahir = null;
    public string $nama_berkas = '';
    public string $status = 'Aktif';
    public ?string $tgl_retensi = null;
    public string $keterangan = '';
    public $file_pdf;
    public ?string $existingFilePdf = null;

    // Search patient lookup
    public string $searchPatient = '';
    public array $patientSuggestions = [];

    public function mount($id = null)
    {
        if ($id) {
            $berkas = Berkas::findOrFail($id);
            $this->berkasId = $berkas->id;
            $this->no_rm = $berkas->no_rm;
            $this->nama_pasien = $berkas->nama_pasien;
            $this->tgl_lahir = $berkas->tgl_lahir ? $berkas->tgl_lahir->format('Y-m-d') : null;
            $this->nama_berkas = $berkas->nama_berkas ?? '';
            $this->status = $berkas->status;
            $this->tgl_retensi = $berkas->tgl_retensi ? $berkas->tgl_retensi->format('Y-m-d') : null;
            $this->keterangan = $berkas->keterangan ?? '';
            $this->existingFilePdf = $berkas->file_pdf;
        }
    }

    public function updatedSearchPatient($value)
    {
        if (empty($value)) {
            $this->patientSuggestions = [];
            return;
        }

        $this->patientSuggestions = Berkas::where('nama_pasien', 'like', "%{$value}%")
            ->orWhere('no_rm', 'like', "%{$value}%")
            ->select('no_rm', 'nama_pasien', 'tgl_lahir')
            ->distinct()
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'no_rm' => $item->no_rm,
                    'nama_pasien' => $item->nama_pasien,
                    'tgl_lahir' => $item->tgl_lahir ? $item->tgl_lahir->format('Y-m-d') : null,
                ];
            })
            ->toArray();
    }

    public function selectPatient($no_rm, $nama_pasien, $tgl_lahir)
    {
        $this->no_rm = $no_rm;
        $this->nama_pasien = $nama_pasien;
        $this->tgl_lahir = $tgl_lahir;
        $this->patientSuggestions = [];
        $this->searchPatient = '';
    }

    public function save()
    {
        $rules = [
            'no_rm' => 'required|string|max:20',
            'nama_pasien' => 'required|string|max:100',
            'tgl_lahir' => 'nullable|date',
            'nama_berkas' => 'nullable|string|max:100',
            'status' => 'required|in:Aktif,Inaktif,Musnah',
            'tgl_retensi' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'file_pdf' => 'nullable|file|mimes:pdf|max:5120', // Max 5MB
        ];

        $this->validate($rules);

        $berkas = $this->berkasId ? Berkas::findOrFail($this->berkasId) : new Berkas();

        // Handle upload
        $filename = $this->existingFilePdf;
        if ($this->file_pdf) {
            // Delete old file if exists
            if ($this->existingFilePdf) {
                Storage::disk('public')->delete('berkas/' . $this->existingFilePdf);
            }

            // Save new file
            $filename = time() . '_' . $this->file_pdf->getClientOriginalName();
            $this->file_pdf->storeAs('berkas', $filename, 'public');
        }

        $berkas->no_rm = $this->no_rm;
        $berkas->nama_pasien = $this->nama_pasien;
        $berkas->tgl_lahir = $this->tgl_lahir ? $this->tgl_lahir : null;
        $berkas->nama_berkas = $this->nama_berkas;
        $berkas->file_pdf = $filename;
        $berkas->status = $this->status;
        $berkas->tgl_retensi = $this->tgl_retensi ? $this->tgl_retensi : null;
        $berkas->keterangan = $this->keterangan;
        
        if (!$this->berkasId) {
            $berkas->created_by = Auth::id();
        }

        $berkas->save();

        session()->flash('success', $this->berkasId ? 'Data rekam medis berhasil diperbarui!' : 'Data rekam medis berhasil ditambahkan!');

        return redirect()->to('/dashboard');
    }
};
?>

<div class="min-h-screen flex items-center justify-center p-6 bg-slate-100 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-2xl border border-slate-100">
        <!-- Card Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xl font-bold text-slate-800 tracking-tight">
                {{ $berkasId ? 'Edit Data Rekam Medis' : 'Tambah Data Rekam Medis' }}
            </h3>
            <a href="/dashboard" class="w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
        
        <!-- Live Form -->
        <form wire:submit="save" class="p-6 space-y-6">
            
            <!-- Patient Search Lookup (Autofill feature) -->
            @if (!$berkasId)
            <div class="relative bg-blue-50/50 p-4 rounded-2xl border border-blue-100">
                <label class="block text-xs font-bold text-blue-800 uppercase tracking-wider mb-2">Pencarian Pasien Terdaftar (Autofill)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="searchPatient"
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-blue-200 focus:border-blue-500 rounded-xl focus:ring-4 focus:ring-blue-100 outline-none transition-all text-sm font-medium placeholder-blue-300 text-blue-900"
                           placeholder="Cari No RM atau nama pasien untuk autocomplete...">
                </div>

                @if (!empty($patientSuggestions))
                    <div class="absolute z-20 left-4 right-4 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden divide-y divide-slate-100">
                        @foreach ($patientSuggestions as $patient)
                            <button type="button" 
                                    wire:click="selectPatient('{{ $patient['no_rm'] }}', '{{ $patient['nama_pasien'] }}', '{{ $patient['tgl_lahir'] }}')"
                                    class="w-full px-4 py-3 text-left hover:bg-blue-50/70 flex justify-between items-center transition-colors">
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">{{ $patient['nama_pasien'] }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Tgl Lahir: {{ $patient['tgl_lahir'] ? date('d-m-Y', strtotime($patient['tgl_lahir'])) : '-' }}</p>
                                </div>
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                                    RM: {{ $patient['no_rm'] }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            @endif

            <!-- Main Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">No Rekam Medis *</label>
                    <input type="text" wire:model="no_rm" required 
                           class="w-full px-4 py-3 border-2 border-slate-100 bg-slate-50 focus:bg-white rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all text-slate-800 font-semibold"
                           placeholder="Contoh: 00123">
                    @error('no_rm') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Pasien *</label>
                    <input type="text" wire:model="nama_pasien" required 
                           class="w-full px-4 py-3 border-2 border-slate-100 bg-slate-50 focus:bg-white rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all text-slate-800 font-semibold"
                           placeholder="Nama lengkap pasien">
                    @error('nama_pasien') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Lahir</label>
                    <input type="date" wire:model="tgl_lahir" 
                           class="w-full px-4 py-3 border-2 border-slate-100 bg-slate-50 focus:bg-white rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all text-slate-800">
                    @error('tgl_lahir') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status *</label>
                    <select wire:model="status" required 
                            class="w-full px-4 py-3 border-2 border-slate-100 bg-slate-50 focus:bg-white rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all bg-white text-slate-800 font-semibold">
                        <option value="Aktif">Aktif</option>
                        <option value="Inaktif">Inaktif</option>
                        <option value="Musnah">Musnah</option>
                    </select>
                    @error('status') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Berkas / Kode RM</label>
                    <input type="text" wire:model="nama_berkas"
                           class="w-full px-4 py-3 border-2 border-slate-100 bg-slate-50 focus:bg-white rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all text-slate-800"
                           placeholder="Contoh: Berkas RJ 2025">
                    @error('nama_berkas') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Retensi (Rencana Pemusnahan)</label>
                    <input type="date" wire:model="tgl_retensi" 
                           class="w-full px-4 py-3 border-2 border-slate-100 bg-slate-50 focus:bg-white rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all text-slate-800">
                    @error('tgl_retensi') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan / Catatan Medis</label>
                <textarea wire:model="keterangan" rows="3" 
                          class="w-full px-4 py-3 border-2 border-slate-100 bg-slate-50 focus:bg-white rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all resize-none text-slate-800"
                          placeholder="Masukkan keterangan tambahan (misal: penyakit, unit pelayanan, riwayat)"></textarea>
                @error('keterangan') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <!-- File PDF Upload -->
            <div class="border-2 border-dashed border-slate-200 rounded-3xl p-6 bg-slate-50/50">
                <label class="block text-sm font-bold text-slate-700 mb-4">Unggah Berkas PDF (Maksimal 5MB)</label>
                
                @if ($existingFilePdf && !$file_pdf)
                    <div class="flex items-center justify-between p-4 bg-emerald-50/50 border border-emerald-100 rounded-2xl">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="overflow-hidden max-w-sm">
                                <p class="font-bold text-slate-800 text-sm truncate">{{ $existingFilePdf }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">Berkas tersimpan di server</p>
                            </div>
                        </div>
                        <label class="cursor-pointer text-xs font-bold text-blue-600 hover:text-blue-800 uppercase tracking-wider bg-white px-4 py-2 border border-slate-200 rounded-xl shadow-sm">
                            <span>Ganti File</span>
                            <input type="file" wire:model="file_pdf" accept=".pdf" class="hidden">
                        </label>
                    </div>
                @else
                    <div class="text-center relative">
                        <input type="file" id="file-upload" wire:model="file_pdf" accept=".pdf" class="hidden">
                        
                        <div onclick="document.getElementById('file-upload').click()" class="cursor-pointer group">
                            <div class="w-16 h-16 bg-blue-50 rounded-full mx-auto flex items-center justify-center mb-4 group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <p class="text-slate-600 font-bold text-sm">Klik untuk mengunggah dokumen PDF</p>
                            <p class="text-slate-400 text-xs mt-1">Dokumen rekam medis digital (.pdf)</p>
                        </div>

                        <!-- Progress Bar for Uploads -->
                        <div wire:loading wire:target="file_pdf" class="mt-4 w-full">
                            <div class="flex justify-between mb-1 text-xs text-blue-600 font-semibold">
                                <span>Mengunggah file...</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-1.5">
                                <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 100%"></div>
                            </div>
                        </div>

                        @if ($file_pdf)
                            <div class="mt-4 p-3 bg-blue-50 text-blue-700 rounded-xl text-xs font-semibold flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Siap Diunggah: {{ $file_pdf->getClientOriginalName() }} ({{ round($file_pdf->getSize() / 1024) }} KB)</span>
                            </div>
                        @endif
                    </div>
                @endif
                @error('file_pdf') <span class="text-xs text-red-500 mt-2 block text-center">{{ $message }}</span> @enderror
            </div>
            
            <!-- Actions -->
            <div class="flex gap-4 pt-4 border-t border-slate-100">
                <a href="/dashboard" 
                   class="flex-1 px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold transition-all text-center text-sm">
                    Batal
                </a>
                <button type="submit" wire:loading.attr="disabled"
                        class="flex-1 px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-2xl font-bold shadow-lg hover:shadow-blue-500/20 hover:shadow-xl transition-all duration-300 transform active:scale-95 text-sm">
                    <span wire:loading.remove wire:target="save">{{ $berkasId ? 'Perbarui Data' : 'Simpan Data' }}</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
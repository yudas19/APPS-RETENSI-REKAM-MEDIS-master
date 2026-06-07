<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Berkas;
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    use WithPagination;

    #[Url(keep: true)]
    public string $filter = '';

    #[Url(keep: true)]
    public string $search = '';

    // Reset pagination when search or filter changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    // Set filter directly
    public function setFilter(string $status)
    {
        $this->filter = $this->filter === $status ? '' : $status;
        $this->resetPage();
    }

    // Reset both filter and search
    public function resetFilters()
    {
        $this->reset(['filter', 'search']);
        $this->resetPage();
    }

    // Delete berkas
    public function deleteBerkas(int $id)
    {
        $berkas = Berkas::findOrFail($id);
        
        if ($berkas->file_pdf) {
            Storage::disk('public')->delete('berkas/' . $berkas->file_pdf);
        }

        $berkas->delete();
        session()->flash('success', 'Data rekam medis berhasil dihapus.');
    }

    // Helper to get stats
    public function getStatsProperty()
    {
        return [
            'Aktif' => Berkas::where('status', 'Aktif')->count(),
            'Inaktif' => Berkas::where('status', 'Inaktif')->count(),
            'Musnah' => Berkas::where('status', 'Musnah')->count(),
        ];
    }

    // Render data
    public function with(): array
    {
        $query = Berkas::query();

        if ($this->filter) {
            $query->where('status', $this->filter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('no_rm', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_pasien', 'like', '%' . $this->search . '%')
                  ->orWhere('alamat', 'like', '%' . $this->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $this->search . '%');
            });
        }

        $berkas = $query->orderBy('created_at', 'desc')->paginate(10);

        return [
            'berkas' => $berkas,
            'stats' => $this->getStatsProperty(),
        ];
    }
};
?>

<div class="p-8 flex-1 overflow-auto bg-slate-50">
    <!-- Session Messages -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 font-medium flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">
                {{ $filter ? 'Data ' . $filter : 'Dashboard Utama' }}
            </h1>
            <p class="text-slate-500 mt-1 text-sm font-medium">
                Kelola status retensi berkas rekam medis Klinik Kolbu secara realtime.
            </p>
        </div>
        
        <div class="flex items-center gap-3 self-start md:self-auto">
            <div class="bg-white px-4 py-2.5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-2 text-slate-600 font-semibold text-sm">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Active Stats Card -->
        <button wire:click="setFilter('Aktif')" class="text-left focus:outline-none group relative overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-6 text-white shadow-xl hover:shadow-emerald-500/20 hover:scale-[1.01] transition-all duration-300 {{ $filter === 'Aktif' ? 'ring-4 ring-emerald-300' : '' }}">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-xl transition-all duration-300 group-hover:scale-110"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Berkas Aktif</p>
                    <p class="text-4xl font-extrabold mt-2">{{ $stats['Aktif'] }}</p>
                </div>
                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </button>
        
        <!-- Inactive Stats Card -->
        <button wire:click="setFilter('Inaktif')" class="text-left focus:outline-none group relative overflow-hidden bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl p-6 text-white shadow-xl hover:shadow-amber-500/20 hover:scale-[1.01] transition-all duration-300 {{ $filter === 'Inaktif' ? 'ring-4 ring-amber-300' : '' }}">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-xl transition-all duration-300 group-hover:scale-110"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-amber-100 text-xs font-bold uppercase tracking-wider">Berkas Inaktif</p>
                    <p class="text-4xl font-extrabold mt-2">{{ $stats['Inaktif'] }}</p>
                </div>
                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </button>
        
        <!-- Musnah Stats Card -->
        <button wire:click="setFilter('Musnah')" class="text-left focus:outline-none group relative overflow-hidden bg-gradient-to-br from-rose-500 to-red-600 rounded-3xl p-6 text-white shadow-xl hover:shadow-rose-500/20 hover:scale-[1.01] transition-all duration-300 {{ $filter === 'Musnah' ? 'ring-4 ring-rose-300' : '' }}">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-xl transition-all duration-300 group-hover:scale-110"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-rose-100 text-xs font-bold uppercase tracking-wider">Berkas Dimusnahkan</p>
                    <p class="text-4xl font-extrabold mt-2">{{ $stats['Musnah'] }}</p>
                </div>
                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
            </div>
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Table Toolbar -->
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center gap-4 flex-1">
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">Data Rekam Medis</h2>
                @if ($filter)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold border border-blue-100 uppercase tracking-wider">
                        Filter: {{ $filter }}
                        <button wire:click="$set('filter', '')" class="hover:text-blue-800">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </span>
                @endif
            </div>

            <!-- Search and Action -->
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <!-- Search Input -->
                <div class="relative w-full sm:w-72">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all duration-200 text-sm font-medium text-slate-800 placeholder-slate-400"
                           placeholder="Cari No RM atau nama pasien...">
                    @if ($search)
                        <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>

                @if ($filter || $search)
                    <button wire:click="resetFilters" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-600 hover:text-slate-800 rounded-2xl text-sm font-bold transition-all w-full sm:w-auto">
                        Reset
                    </button>
                @endif
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-16">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No RM</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Pasien</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tgl Lahir</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Terakhir Kunjungan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">File PDF</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($berkas as $index => $row)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-500">
                                {{ $berkas->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-800">
                                {{ $row->no_rm }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-700">
                                <div class="font-bold text-slate-800">{{ $row->nama_pasien }}</div>
                                @if ($row->alamat)
                                    <div class="text-xs text-slate-400 font-normal mt-0.5"><span class="font-semibold text-slate-500">Alamat:</span> {{ $row->alamat }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-750 font-semibold">
                                @if($row->tgl_lahir)
                                    <div>{{ $row->tgl_lahir->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-400 font-normal mt-0.5">{{ $row->usia }} Tahun</div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        'Aktif' => 'bg-green-50 text-green-700 border-green-100',
                                        'Inaktif' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'Musnah' => 'bg-rose-50 text-rose-700 border-rose-100',
                                    ];
                                    $class = $colors[$row->status] ?? 'bg-slate-50 text-slate-750 border-slate-100';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $class }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 font-medium">
                                {{ $row->tgl_retensi ? $row->tgl_retensi->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($row->file_pdf)
                                    <a href="{{ asset('storage/berkas/' . $row->file_pdf) }}" target="_blank" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 hover:bg-red-100 text-red-650 rounded-xl text-xs font-bold border border-red-100 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2zM12 3v6h6"/>
                                        </svg>
                                        <span>Lihat PDF</span>
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-3">
                                    <a href="/berkas/{{ $row->id }}/edit" 
                                       class="text-blue-600 hover:text-blue-800 font-bold transition-all text-xs uppercase tracking-wider">
                                        Edit
                                    </a>
                                    <button wire:click="deleteBerkas({{ $row->id }})" 
                                            wire:confirm="Yakin ingin menghapus berkas RM ini?"
                                            class="text-red-650 hover:text-red-800 font-bold transition-all text-xs uppercase tracking-wider">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center py-6">
                                    <svg class="w-16 h-16 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="font-bold text-slate-650 text-base">Belum Ada Data Rekam Medis</p>
                                    <p class="text-sm text-slate-400 mt-1 max-w-sm">
                                        Tidak ada berkas rekam medis yang ditemukan. Klik "Tambah Data" untuk mengunggah berkas baru.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination -->
        @if ($berkas->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $berkas->links() }}
            </div>
        @endif
    </div>
</div>
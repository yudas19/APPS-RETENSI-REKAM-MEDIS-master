<?php

use Livewire\Component;
use App\Models\Berkas;

new class extends Component
{
    public function with(): array
    {
        $aktifCount = Berkas::where('status', 'Aktif')->count();
        $inaktifCount = Berkas::where('status', 'Inaktif')->count();
        $musnahCount = Berkas::where('status', 'Musnah')->count();
        
        return [
            'stats' => [
                'Aktif' => $aktifCount,
                'Inaktif' => $inaktifCount,
                'Musnah' => $musnahCount,
                'Total' => $aktifCount + $inaktifCount + $musnahCount
            ],
        ];
    }
};
?>

<div class="p-8 flex-1 overflow-auto bg-slate-50 flex flex-col justify-between min-h-screen">
    <div class="max-w-7xl mx-auto w-full space-y-8">
        <!-- Header & Greetings Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    Beranda Utama
                </h1>
                <p class="text-slate-500 mt-1.5 text-sm font-medium">
                    Pantau statistik ringkasan dan status retensi berkas rekam medis Klinik Kolbu.
                </p>
            </div>
            
            <div class="flex items-center gap-3 self-start md:self-auto">
                <div class="bg-white px-4.5 py-3 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-2.5 text-slate-600 font-semibold text-sm">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
                </div>
            </div>
        </div>

        <!-- Welcome Banner Card -->
        <div class="relative overflow-hidden bg-slate-900 rounded-[2rem] p-8 md:p-10 text-white shadow-xl border border-slate-800">
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-blue-600/20 rounded-full blur-3xl"></div>
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-3 max-w-2xl">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-500/10 text-blue-400 rounded-full text-xs font-bold border border-blue-500/20 uppercase tracking-wider">
                        Selamat Datang Kembali
                    </span>
                    <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                        {{ Auth::user()->nama_lengkap }}
                    </h2>
                    <p class="text-slate-400 text-sm md:text-base leading-relaxed">
                        Anda masuk sebagai <strong class="text-white">{{ Auth::user()->role }}</strong>. Kelola masa retensi berkas rekam medis dengan mudah dan pastikan standar penyimpanan data tetap terpenuhi secara akurat.
                    </p>
                </div>
                
                <div class="flex-shrink-0 bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-6 flex flex-col items-center justify-center min-w-[180px] text-center">
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Berkas</span>
                    <span class="text-4xl font-extrabold mt-1 text-white tracking-tight">{{ $stats['Total'] }}</span>
                    <span class="text-slate-500 text-[10px] mt-1.5 font-medium">Berkas Terarsip</span>
                </div>
            </div>
        </div>

        <!-- 3 Cards Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Active Stats Card -->
            <a href="/dashboard?filter=Aktif" class="group relative overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-650 rounded-[2rem] p-8 text-white shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/20 hover:scale-[1.02] transition-all duration-300 flex flex-col justify-between min-h-[220px]">
                <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-white/10 rounded-full blur-2xl transition-all duration-500 group-hover:scale-125"></div>
                
                <div class="flex items-start justify-between relative z-10">
                    <div class="space-y-1">
                        <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Berkas Aktif</p>
                        <p class="text-5xl font-black mt-2 tracking-tight">{{ $stats['Aktif'] }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner group-hover:rotate-6 transition-transform duration-300">
                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="relative z-10 pt-6 flex items-center gap-1.5 text-emerald-100 font-bold text-xs uppercase tracking-wider group-hover:translate-x-1.5 transition-transform duration-300">
                    <span>Lihat Rincian</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            
            <!-- Inactive Stats Card -->
            <a href="/dashboard?filter=Inaktif" class="group relative overflow-hidden bg-gradient-to-br from-amber-500 to-orange-600 rounded-[2rem] p-8 text-white shadow-lg shadow-amber-500/10 hover:shadow-amber-500/20 hover:scale-[1.02] transition-all duration-300 flex flex-col justify-between min-h-[220px]">
                <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-white/10 rounded-full blur-2xl transition-all duration-500 group-hover:scale-125"></div>
                
                <div class="flex items-start justify-between relative z-10">
                    <div class="space-y-1">
                        <p class="text-amber-100 text-xs font-bold uppercase tracking-wider">Berkas Inaktif</p>
                        <p class="text-5xl font-black mt-2 tracking-tight">{{ $stats['Inaktif'] }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner group-hover:rotate-6 transition-transform duration-300">
                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="relative z-10 pt-6 flex items-center gap-1.5 text-amber-100 font-bold text-xs uppercase tracking-wider group-hover:translate-x-1.5 transition-transform duration-300">
                    <span>Lihat Rincian</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            
            <!-- Musnah Stats Card -->
            <a href="/dashboard?filter=Musnah" class="group relative overflow-hidden bg-gradient-to-br from-rose-500 to-red-650 rounded-[2rem] p-8 text-white shadow-lg shadow-rose-500/10 hover:shadow-rose-500/20 hover:scale-[1.02] transition-all duration-300 flex flex-col justify-between min-h-[220px]">
                <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-white/10 rounded-full blur-2xl transition-all duration-500 group-hover:scale-125"></div>
                
                <div class="flex items-start justify-between relative z-10">
                    <div class="space-y-1">
                        <p class="text-rose-100 text-xs font-bold uppercase tracking-wider">Berkas Dimusnahkan</p>
                        <p class="text-5xl font-black mt-2 tracking-tight">{{ $stats['Musnah'] }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner group-hover:rotate-6 transition-transform duration-300">
                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                </div>

                <div class="relative z-10 pt-6 flex items-center gap-1.5 text-rose-100 font-bold text-xs uppercase tracking-wider group-hover:translate-x-1.5 transition-transform duration-300">
                    <span>Lihat Rincian</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <!-- Footer Copyright -->
    <div class="text-center text-slate-400 text-xs mt-12 py-4 max-w-7xl mx-auto w-full border-t border-slate-200/60">
        &copy; {{ date('Y') }} Klinik Kolbu. Hak Cipta Dilindungi Undang-Undang.
    </div>
</div>

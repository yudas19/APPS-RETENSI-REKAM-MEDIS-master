<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Retensi Rekam Medis' }} - Klinik Kolbu</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full overflow-hidden text-slate-800">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col border-r border-slate-800">
            <!-- Brand Logo and Header -->
            <div class="p-6 border-b border-slate-800">
                <a href="/dashboard" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center p-1 shadow-md group-hover:scale-105 transition-all duration-300">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-sm leading-none tracking-tight">Klinik Kolbu</span>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5 tracking-wider">Retensi RM</span>
                    </div>
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
                <a href="/dashboard" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('dashboard') && !request()->has('filter') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'hover:bg-slate-800/60 text-slate-400 hover:text-white' }}">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>
                
                <a href="/dashboard?filter=Aktif" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->get('filter') == 'Aktif' ? 'bg-slate-800 text-white border-l-4 border-green-500 pl-3' : 'hover:bg-slate-800/60 text-slate-400 hover:text-white' }}">
                    <svg class="w-5 h-5 text-green-400 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium text-sm">Data Aktif</span>
                </a>
                
                <a href="/dashboard?filter=Inaktif" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->get('filter') == 'Inaktif' ? 'bg-slate-800 text-white border-l-4 border-amber-500 pl-3' : 'hover:bg-slate-800/60 text-slate-400 hover:text-white' }}">
                    <svg class="w-5 h-5 text-amber-400 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium text-sm">Data Inaktif</span>
                </a>
                
                <a href="/dashboard?filter=Musnah" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->get('filter') == 'Musnah' ? 'bg-slate-800 text-white border-l-4 border-red-500 pl-3' : 'hover:bg-slate-800/60 text-slate-400 hover:text-white' }}">
                    <svg class="w-5 h-5 text-red-400 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span class="font-medium text-sm">Data Musnah</span>
                </a>

                <a href="/change-password" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('change-password') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'hover:bg-slate-800/60 text-slate-400 hover:text-white' }}">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-2 4a2 2 0 012 2m3 4H4a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2zM12 11a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                    <span class="font-medium text-sm">Ganti Password</span>
                </a>
                
                <div class="pt-6 border-t border-slate-800 mt-6">
                    <a href="/berkas/create" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold text-sm transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/20 hover:scale-[1.02]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Tambah Data</span>
                    </a>
                </div>
            </nav>
            
            <!-- User Info and Logout -->
            @auth
            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                <div class="px-4 py-3 rounded-xl bg-slate-850 mb-3 border border-slate-800">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->nama_lengkap }}</p>
                    <span class="inline-block text-[10px] bg-blue-500/10 text-blue-400 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider mt-1 border border-blue-500/20">
                        {{ Auth::user()->role }}
                    </span>
                </div>
                
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="w-full group flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-rose-500/10 text-slate-400 hover:text-rose-400 transition-all duration-200">
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="font-semibold text-sm">Keluar</span>
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        <!-- Main Content Panel -->
        <main class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50">
            {{ $slot }}
        </main>
    </div>
</body>
</html>

<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public string $username = '';
    public string $password = '';
    public ?string $error = null;

    public function login()
    {
        $this->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            session()->regenerate();
            return redirect()->to('/dashboard');
        }

        $this->error = 'Username atau password salah!';
    }
};
?>

<div class="min-h-screen flex items-center justify-center bg-slate-900 px-4 relative overflow-hidden">
    <!-- Abstract glowing blobs for premium feel -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/30 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-600/30 rounded-full blur-3xl"></div>

    <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-2xl p-8 w-full max-w-md border border-white/20 relative z-10">
        <div class="text-center mb-8">
            <div class="mb-4">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Klinik Kolbu Logo" class="h-20 object-contain mx-auto">
            </div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">RETENSI RM</h1>
            <p class="text-slate-500 mt-2 text-sm font-medium">Silakan masuk untuk mengelola rekam medis</p>
        </div>
        
        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-2xl text-sm font-medium mb-5">
                {{ session('success') }}
            </div>
        @endif
        
        <form wire:submit="login" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Username</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    <input type="text" wire:model="username" required 
                           class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition-all duration-200 text-slate-800 font-medium"
                           placeholder="Masukkan username">
                </div>
                @error('username') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-semibold text-slate-700">Password</label>
                    <a href="/forgot-password" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">Lupa Password?</a>
                </div>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input type="password" wire:model="password" required 
                           class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition-all duration-200 text-slate-800"
                           placeholder="Masukkan password">
                </div>
                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            @if ($error)
                <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-2xl text-sm font-medium animate-pulse">
                    {{ $error }}
                </div>
            @endif
            
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3.5 rounded-2xl font-bold shadow-lg hover:shadow-blue-500/20 hover:shadow-xl transition-all duration-300 transform active:scale-95">
                MASUK
            </button>
        </form>
        
        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Demo Credentials</p>
            <p class="text-slate-600 text-sm mt-1 font-medium bg-slate-50 rounded-xl py-2 px-4 inline-block">
                admin <span class="mx-1 text-slate-300">|</span> admin123
            </p>
        </div>
    </div>
</div>
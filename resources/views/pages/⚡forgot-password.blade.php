<?php

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Password;

new class extends Component
{
    public string $email = '';
    public ?string $statusMessage = null;
    public ?string $errorMessage = null;
    public ?string $demoResetLink = null;

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->errorMessage = 'Email tidak ditemukan dalam sistem kami.';
            $this->statusMessage = null;
            $this->demoResetLink = null;
            return;
        }

        // Generate reset token using Laravel's password broker
        $token = Password::broker()->createToken($user);

        // Send reset notification (which writes to laravel.log because of MAIL_MAILER=log)
        $user->sendPasswordResetNotification($token);

        $this->errorMessage = null;
        $this->statusMessage = 'Link reset password telah dikirim ke email Anda (dan dicatat di system log).';

        // Provide a direct helper link for local/demo environments
        if (app()->environment('local')) {
            $this->demoResetLink = route('password.reset', [
                'token' => $token,
                'email' => $this->email
            ]);
        }
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
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">LUPA PASSWORD</h1>
            <p class="text-slate-500 mt-2 text-sm font-medium">Masukkan email terdaftar untuk mengatur ulang password Anda</p>
        </div>
        
        @if ($statusMessage)
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-5 py-4 rounded-2xl text-sm font-medium mb-6 space-y-3">
                <div class="flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $statusMessage }}</span>
                </div>

                @if ($demoResetLink)
                    <div class="mt-3 pt-3 border-t border-emerald-200/50">
                        <p class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider mb-2">Demo Helper (Lingkungan Lokal)</p>
                        <a href="{{ $demoResetLink }}" 
                           class="inline-flex w-full justify-center items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition-all shadow-md">
                            <span>Buka Halaman Reset Password</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        @else
            <form wire:submit="sendResetLink" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email" wire:model="email" required 
                               class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition-all duration-200 text-slate-800 font-medium"
                               placeholder="admin@example.com">
                    </div>
                    @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                @if ($errorMessage)
                    <div class="bg-red-50 border border-red-100 text-red-650 px-4 py-3 rounded-2xl text-sm font-medium">
                        {{ $errorMessage }}
                    </div>
                @endif
                
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3.5 rounded-2xl font-bold shadow-lg hover:shadow-blue-500/20 hover:shadow-xl transition-all duration-300 transform active:scale-95">
                    KIRIM LINK RESET
                </button>
            </form>
        @endif
        
        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <a href="/login" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Login</span>
            </a>
        </div>
    </div>
</div>

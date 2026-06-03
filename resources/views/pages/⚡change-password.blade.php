<?php

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini salah.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password)
        ]);

        session()->flash('success', 'Password berhasil diperbarui!');
        return redirect()->to('/dashboard');
    }
};
?>

<div class="min-h-screen flex items-center justify-center p-6 bg-slate-100">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md border border-slate-100">
        <!-- Card Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xl font-bold text-slate-800 tracking-tight">Ganti Password</h3>
            <a href="/dashboard" class="w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-605 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
        
        <!-- Change Password Form -->
        <form wire:submit="changePassword" class="p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Password Saat Ini</label>
                <input type="password" wire:model="current_password" required 
                       class="w-full px-4 py-3 border-2 border-slate-100 bg-slate-50 focus:bg-white rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all text-slate-800">
                @error('current_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
                <input type="password" wire:model="new_password" required 
                       class="w-full px-4 py-3 border-2 border-slate-100 bg-slate-50 focus:bg-white rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all text-slate-800">
                @error('new_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                <input type="password" wire:model="new_password_confirmation" required 
                       class="w-full px-4 py-3 border-2 border-slate-100 bg-slate-50 focus:bg-white rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition-all text-slate-800">
            </div>

            <div class="flex gap-4 pt-4 border-t border-slate-100">
                <a href="/dashboard" 
                   class="flex-1 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-750 rounded-2xl font-bold transition-all text-center text-sm">
                    Batal
                </a>
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-2xl font-bold shadow-lg hover:shadow-blue-500/20 hover:shadow-xl transition-all duration-300 transform active:scale-95 text-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
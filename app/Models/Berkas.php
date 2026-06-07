<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['no_rm', 'nama_pasien', 'tgl_lahir', 'nama_berkas', 'file_pdf', 'status', 'tgl_retensi', 'keterangan', 'created_by'])]
class Berkas extends Model
{
    use HasFactory;

    protected $table = 'berkas';

    /**
     * Get the user who created/uploaded this record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the patient's age in years.
     */
    public function getUsiaAttribute(): ?int
    {
        if (!$this->tgl_lahir) {
            return null;
        }
        return $this->tgl_lahir->age;
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tgl_lahir' => 'date',
            'tgl_retensi' => 'date',
        ];
    }
}

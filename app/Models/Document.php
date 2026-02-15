<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'idea_id',
        'original_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        
        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }
        
        return round($bytes, 2) . ' ' . $units[$unitIndex];
    }

    public function getIsImageAttribute()
    {
        return strpos($this->file_type, 'image/') === 0;
    }

    public function getIsPdfAttribute()
    {
        return $this->file_type === 'application/pdf';
    }

    public function getIconClassAttribute()
    {
        if ($this->is_image) {
            return 'fa-image';
        } elseif ($this->is_pdf) {
            return 'fa-file-pdf';
        } elseif (strpos($this->file_type, 'word') !== false || $this->file_type === 'application/msword') {
            return 'fa-file-word';
        } elseif (strpos($this->file_type, 'excel') !== false || $this->file_type === 'application/vnd.ms-excel') {
            return 'fa-file-excel';
        } else {
            return 'fa-file';
        }
    }
}

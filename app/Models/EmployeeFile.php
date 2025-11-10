<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFile extends Model
{
    protected $fillable = [
        'employee_id','path',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFileIcon()
    {
        return $iconMap[strtolower($this->path)] ?? 'file';
    }

    public function getFileUrlAttribute()
    {
        return $this->path ? asset('storage/'.$this->path) : null;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSize()
    {
        if ($this->file_size == 0) return '0 Bytes';

        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($this->file_size) / log($k));

        return round($this->file_size / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}

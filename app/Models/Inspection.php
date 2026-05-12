<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'inspected_by',
        'inspected_at',
        'remarks',
        'image_url',
        'condition_status',
    ];

    protected $casts = [
        'inspected_at' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class)->withTrashed();
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }
}

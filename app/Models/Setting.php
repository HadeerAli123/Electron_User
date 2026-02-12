<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'setting';

    protected $fillable = [
        'phone',
        'whatsapp',
        'email',
        'address',
        'decription',
    ];

    // Helper method to get settings
    public static function getSettings()
    {
        return self::first();
    }
}
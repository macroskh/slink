<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string|null origin
 * @property string shortcut
 * @property int|null expired_at
 * @property int|null max_follows
 */
class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin',
        'shortcut',
        'expired_at',
        'max_follows',
    ];
}

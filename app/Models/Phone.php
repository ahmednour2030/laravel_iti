<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method find(int $id)
 */
class Phone extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'phone',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

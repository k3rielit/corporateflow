<?php

namespace Modules\Clubcard\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Clubcard\Database\Factories\ClubcardFactory;

class Clubcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'email',
        'password',
        'user_id',
        'name',
        'points',
        'auth_token',
        'refresh_token',
    ];

    public static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return ClubcardFactory::new();
    }

    protected function casts(): array
    {
        return [
            'password' => 'encrypted',
            'auth_token' => 'encrypted',
            'refresh_token' => 'encrypted',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}

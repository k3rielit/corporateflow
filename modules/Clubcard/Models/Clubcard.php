<?php

namespace Modules\Clubcard\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Clubcard\Api\ClubcardApi;
use Modules\Clubcard\Database\Factories\ClubcardFactory;
use Modules\Clubcard\Dto\ClubcardAuthorizationDto;

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

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // API

    /**
     * Try to log in, and update the stored API credentials.
     * @return ClubcardAuthorizationDto Dto containing the API keys.
     */
    public function login(): ClubcardAuthorizationDto
    {
        $dto = ClubcardApi::make()->login($this->number, $this->email, $this->password);
        $this->update([
            'auth_token' => $dto->accessToken,
            'refresh_token' => $dto->refreshToken,
        ]);
        return $dto;
    }

}

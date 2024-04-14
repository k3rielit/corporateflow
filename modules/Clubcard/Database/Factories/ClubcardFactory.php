<?php

namespace Modules\Clubcard\Database\Factories;

use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Clubcard\Api\ClubcardApi;
use Modules\Clubcard\Dto\ClubcardPersonalDataDto;
use Modules\Clubcard\Models\Clubcard;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Clubcard\Models\Clubcard>
 */
class ClubcardFactory extends Factory
{
    protected $model = Clubcard::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'password' => 'CorporateFlow2077',
            'user_id' => auth()->id(),
        ];
    }

    public function randomPassword(): static
    {
        return $this->state(function () {
            return [
                'password' => Str::password(12),
            ];
        });
    }

    public function randomEmail(): static
    {
        return $this->state(function () {
            return [
                'email' => fake()->freeEmail(),
            ];
        });
    }

    public function email(string $email): static
    {
        return $this->state(function () use ($email) {
            return [
                'email' => $email,
            ];
        });
    }

    public function generate(): static
    {
        return $this->state(function (array $attributes) {
            // Retrieve an email or fail, and retrieve or generate the account password
            $email = $attributes['email'] ?? null;
            $password = $attributes['password'] ?? null;
            if (!$email) {
                throw new Exception("Cannot generate an account, since the email attribute isn't defined.");
            } else if (!$password) {
                $password = Str::password(12);
            }
            // Go through the generation procedure
            $api = ClubcardApi::make();

            $ighsCheck = $api->registrationIghsCheck($email, $password);
            if ($ighsCheck !== 'new') {
                throw new Exception("Registration IGHS marked this account as {$ighsCheck}, it can't be registered.");
            }

            $remoteStatus = $api->registrationMcaRemoteStatus($email, $password);
            if ($remoteStatus !== 'eligible') {
                throw new Exception("Registration MCA remote status check marked this account as {$remoteStatus}, it can't be registered.");
            }

            $registration = $api->registration($email, $password);
            if (!$registration) {
                throw new Exception("Initial registration failed, the progress might already be past this step.");
            }

            $clubcard = $api->registrationClubcard($email);
            $clubcardSkeleton = $api->registrationClubcardSkeletonCheck($clubcard);
            if ($clubcardSkeleton) {
                throw new Exception("Clubcard '{$clubcard}' is not a skeleton, which is unexpected.");
            }

            $personalDataDto = ClubcardPersonalDataDto::make($clubcard, $email)->fake();
            $personalData = $api->registrationPersonalData($personalDataDto);
            if (!$personalData) {
                throw new Exception("Updating personal information failed, it wasn't marked as realtime.");
            }

            // Return the constructed state
            return [
                'number' => intval($clubcard),
                'password' => $password,
            ];
        });
    }

}

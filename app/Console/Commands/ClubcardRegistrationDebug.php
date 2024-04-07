<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Modules\Clubcard\Api\ClubcardApi;
use Modules\Clubcard\Dto\ClubcardPersonalDataDto;

class ClubcardRegistrationDebug extends Command
{

    protected $signature = 'clubcard:debug-registration {email}';

    protected $description = 'Go through the registration process while providing as much debug information as possible.';

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'email' => 'Which email would you like to generate a Clubcard account for?',
        ];
    }

    public function handle(): void
    {
        $api = ClubcardApi::make();
        $email = $this->argument('email');
        $password = 'CorporateFlow2077';

        $ighsCheck = $api->registrationIghsCheck($email, $password);
        if ($ighsCheck !== 'new') {
            $this->error("Registration IGHS marked this account as {$ighsCheck}, it can't be registered.");
            return;
        }
        $this->info("Registration IGHS check succeeded, account is marked as {$ighsCheck}.");

        $remoteStatus = $api->registrationMcaRemoteStatus($email, $password);
        if ($remoteStatus !== 'eligible') {
            $this->error("Registration MCA remote status check marked this account as {$remoteStatus}, it can't be registered.");
            return;
        }
        $this->info("Registration MCA remote status check succeeded, account is marked as {$remoteStatus}.");

        $registration = $api->registration($email, $password);
        if (!$registration) {
            $this->error("Initial registration failed, the progress might already be past this step.");
            return;
        }
        $this->info("Registration succeeded, account is now available: {$email} {$password}");

        $clubcard = $api->registrationClubcard($email);
        $this->info("Endpoint returned clubcard: {$clubcard}");
        $clubcardSkeleton = $api->registrationClubcardSkeletonCheck($clubcard);
        if ($clubcardSkeleton) {
            $this->error("Clubcard '{$clubcard}' is not a skeleton, which is unexpected.");
            return;
        }
        $this->info("Clubcard {$clubcard} is not a skeleton, it can be used on this account.");

        $personalDataDto = ClubcardPersonalDataDto::make($clubcard, $email)->fake();
        $this->info("Constructed personal data DTO: " . $personalDataDto->toJson());
        $personalData = $api->registrationPersonalData($personalDataDto);
        if (!$personalData) {
            $this->error("Updating personal information failed, it wasn't marked as realtime.");
            return;
        }
        $this->info("Updating personal data was successful, it was marked as realtime.");
    }

}

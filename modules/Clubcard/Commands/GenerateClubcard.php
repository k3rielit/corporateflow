<?php

namespace Modules\Clubcard\Commands;

use Illuminate\Console\Command;
use Modules\Clubcard\Api\ClubcardApi;
use Modules\Clubcard\Database\Factories\ClubcardFactory;
use Modules\Clubcard\Dto\ClubcardPersonalDataDto;
use Modules\Clubcard\Models\Clubcard;

class GenerateClubcard extends Command
{

    protected $signature = 'clubcard:generate {email} {--debug}';

    protected $description = 'Creates a new random Clubcard account.';

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'email' => 'Which email would you like to generate a Clubcard account for?',
        ];
    }

    public function handle(): void
    {
        $email = $this->argument('email');
        $verbose = $this->option('debug');
        if (!$email) {
            $this->error('No email provided, exiting...');
        }
        $clubcard = match ($verbose) {
            true => $this->handleVerbose($email),
            false => $this->handleSimple($email),
        };
        if (!$clubcard) {
            $this->error('Operation failed, exiting...');
        }
        $this->table(['Email', 'Password', 'Clubcard', 'Authorization'], [[$clubcard?->email, $clubcard?->password, $clubcard?->number]]);
        $auth = $clubcard->login();
        $this->info($auth->accessToken ?? 'Login failed.');
    }

    public function handleSimple(string $email): Clubcard|null
    {
        return Clubcard::factory()->email($email)->generate()->createOne();
    }

    public function handleVerbose(string $email): Clubcard|null
    {
        $api = ClubcardApi::make();
        $password = 'CorporateFlow2077';

        $ighsCheck = $api->registrationIghsCheck($email, $password);
        if ($ighsCheck !== 'new') {
            $this->error("Registration IGHS marked this account as {$ighsCheck}, it can't be registered.");
            return null;
        }
        $this->info("Registration IGHS check succeeded, account is marked as {$ighsCheck}.");

        $remoteStatus = $api->registrationMcaRemoteStatus($email, $password);
        if ($remoteStatus !== 'eligible') {
            $this->error("Registration MCA remote status check marked this account as {$remoteStatus}, it can't be registered.");
            return null;
        }
        $this->info("Registration MCA remote status check succeeded, account is marked as {$remoteStatus}.");

        $registration = $api->registration($email, $password);
        if (!$registration) {
            $this->error("Initial registration failed, the progress might already be past this step.");
            return null;
        }
        $this->info("Registration succeeded, account is now available: {$email} {$password}");

        $clubcard = $api->registrationClubcard($email);
        $this->info("Endpoint returned clubcard: {$clubcard}");
        $clubcardSkeleton = $api->registrationClubcardSkeletonCheck($clubcard);
        if ($clubcardSkeleton) {
            $this->error("Clubcard '{$clubcard}' is not a skeleton, which is unexpected.");
            return null;
        }
        $this->info("Clubcard {$clubcard} is not a skeleton, it can be used on this account.");

        $personalDataDto = ClubcardPersonalDataDto::make($clubcard, $email)->fake();
        $this->info("Constructed personal data DTO: " . $personalDataDto->toJson());
        $personalData = $api->registrationPersonalData($personalDataDto);
        if (!$personalData) {
            $this->error("Updating personal information failed, it wasn't marked as realtime.");
            return null;
        }
        $this->info("Updating personal data was successful, it was marked as realtime.");

        return Clubcard::factory()->state([
            'email' => $email,
            'password' => $password,
            'number' => $clubcard,
        ])->createOne();
    }

}

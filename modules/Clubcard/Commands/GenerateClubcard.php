<?php

namespace Modules\Clubcard\Commands;

use Illuminate\Console\Command;
use Modules\Clubcard\Database\Factories\ClubcardFactory;
use Modules\Clubcard\Models\Clubcard;

class GenerateClubcard extends Command
{

    protected $signature = 'clubcard:generate {email}';

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
        $clubcard = Clubcard::factory()->email($email)->generate()->createOne();
        $this->table(['Email', 'Password', 'Clubcard'], [[$clubcard->email, $clubcard->password, $clubcard->number]]);
    }

}

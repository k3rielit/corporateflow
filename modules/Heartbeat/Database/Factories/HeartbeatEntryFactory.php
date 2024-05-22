<?php

namespace Modules\Heartbeat\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Heartbeat\Models\HeartbeatEntry;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class HeartbeatEntryFactory extends Factory
{
    protected $model = HeartbeatEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

}

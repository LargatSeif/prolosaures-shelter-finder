<?php

namespace App\Console\Commands;

use App\Services\ShelterCalculator;
use App\Validators\AltitudeValidator;
use Illuminate\Console\Command;

class CalculateShelter extends Command
{
    protected $signature = 'shelter:calculate {altitudes?*}';

    protected $description = 'Calculates the sheltered area given terrain altitudes.';

    /**
     * Execute the console command.
     *
     * @param ShelterCalculator $calculator The shelter calculator service.
     * @param AltitudeValidator $validator The altitude validator.
     * @return int
     */
    
    public function handle(ShelterCalculator $calculator, AltitudeValidator $validator)
    {
        $altitudes = $this->argument('altitudes');
        
        if (empty($altitudes)) {
            $altitudesString = $this->ask('Enter the altitudes separated by spaces');
            
            $altitudes = explode(' ', $altitudesString);
        }
        $altitudes = array_map('intval', $altitudes);

        $validation = $validator->validate(['altitudes' => $altitudes]);

        if ($validation->fails()) {
            $this->error($validation->errors()->first());
            return 1;
        }

        $result = $calculator->calculateShelteredArea($altitudes);

        $this->info("Sheltered area: " . $result['shelteredArea']);

        return 0;
    }
}

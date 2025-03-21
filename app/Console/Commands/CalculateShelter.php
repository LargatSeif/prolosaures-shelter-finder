<?php

namespace App\Console\Commands;

use App\Services\ShelterCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CalculateShelter extends Command
{
    protected $signature = 'shelter:calculate {altitudes?*}';
    protected $description = 'Calculates the sheltered area given terrain altitudes.';

    public function handle(ShelterCalculator $calculator)
    {
        $altitudes = $this->argument('altitudes');

        if (empty($altitudes)) {
            $altitudesString = $this->ask('Enter the altitudes separated by spaces');
            $altitudes = explode(' ', $altitudesString);
        }

        $altitudes = array_map('intval', $altitudes);

        $validator = Validator::make(['altitudes' => $altitudes], [
            'altitudes' => 'required|array|min:1|max:100000',
            'altitudes.*' => 'required|integer|min:0|max:100000',
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return 1;
        }

        $shelteredArea = $calculator->calculateShelteredArea($altitudes);

        $this->info("Sheltered area: " . $shelteredArea);
        return 0;
    }
}

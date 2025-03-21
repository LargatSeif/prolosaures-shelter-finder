<?php

namespace App\Services;

class ShelterCalculator
{
    public function calculateShelteredArea(array $altitudes): array
    {
        $sheltered = [];
        $highestSoFar = 0;
        $n = count($altitudes);

        for ($i = 0; $i < $n; $i++) {
            $highestSoFar = max($highestSoFar, $altitudes[$i]);
            $sheltered[$i] = false;

            if ($altitudes[$i] >= $highestSoFar) {
                continue; // Not sheltered by itself
            }

            for ($j = 0; $j < $i; $j++) {
                if ($altitudes[$j] > $altitudes[$i]) {
                    $sheltered[$i] = true;
                    break;
                }
            }
        }

        $shelteredArea = 0;
        foreach ($sheltered as $isSheltered) {
            if ($isSheltered) {
                $shelteredArea++;
            }
        }

        return [
            'altitudes' => $altitudes,
            'shelteredArea' => $shelteredArea,
            'sheltered' => $sheltered,
        ];
    }
}


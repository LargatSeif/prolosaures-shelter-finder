<?php
namespace App\Http\Controllers;

use App\Http\Requests\AltitudeRequest;
use App\Services\ShelterCalculator;

class ShelterController extends Controller
{
    public function calculate(AltitudeRequest $request, ShelterCalculator $calculator)
    {
        $altitudes = $request->input('altitudes');
        $altitudes = array_map('intval', $altitudes);

        $result = $calculator->calculateShelteredArea($altitudes);

        return view('shelter_form', $result + ['altitudes' => $altitudes]);
    }
}

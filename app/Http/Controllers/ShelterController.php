<?php

namespace App\Http\Controllers;

use App\Services\ShelterCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShelterController extends Controller
{
    public function calculate(Request $request, ShelterCalculator $calculator)
    {
        $altitudes = $request->input('altitudes');
        $altitudes = array_map('intval', $altitudes);

        $validator = Validator::make(['altitudes' => $altitudes], [
            'altitudes' => 'required|array|min:1|max:100000',
            'altitudes.*' => 'required|integer|min:0|max:100000',
        ]);

        if ($validator->fails()) {
            return view('shelter_form', ['errors' => $validator->errors(), 'altitudes' => $altitudes]);
        }

        $result = $calculator->calculateShelteredArea($altitudes);

        return view('shelter_form', $result + ['altitudes' => $altitudes]);
    }
}

<?php

namespace Tests\Unit\Http\Requests;

use App\Facades\AltitudeValidator;
use App\Http\Requests\AltitudeRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use Mockery;

class AltitudeRequestTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testAuthorize()
    {
        $request = new AltitudeRequest();
        $this->assertTrue($request->authorize());
    }

    public function testRulesAreRetrievedFromValidator()
    {
        // Mock the AltitudeValidator facade
        $expectedRules = ['altitudes' => 'required|array'];
        AltitudeValidator::shouldReceive('getRules')
            ->once()
            ->andReturn($expectedRules);

        $request = new AltitudeRequest();
        $rules = $request->rules();

        $this->assertEquals($expectedRules, $rules);
    }

    public function testMessagesAreRetrievedFromValidator()
    {
        // Mock the AltitudeValidator facade
        $expectedMessages = ['altitudes.required' => 'The altitudes field is required.'];
        AltitudeValidator::shouldReceive('getMessages')
            ->once()
            ->andReturn($expectedMessages);

        $request = new AltitudeRequest();
        $messages = $request->messages();

        $this->assertEquals($expectedMessages, $messages);
    }

    public function testValidationPassesWithValidData()
    {
        // Define mock rules and messages
        $mockRules = ['altitudes' => 'required|array'];
        $mockMessages = ['altitudes.required' => 'The altitudes field is required.'];
        
        // Mock the AltitudeValidator facade
        AltitudeValidator::shouldReceive('getRules')->andReturn($mockRules);
        AltitudeValidator::shouldReceive('getMessages')->andReturn($mockMessages);
        
        // Create a validator instance with valid data
        $validator = Validator::make(
            ['altitudes' => [1, 2, 3]],
            (new AltitudeRequest())->rules(),
            (new AltitudeRequest())->messages()
        );
        
        $this->assertFalse($validator->fails());
    }
    
    public function testValidationFailsWithInvalidData()
    {
        $altitudeRequest = new AltitudeRequest();
        
        // Test missing altitudes
        $validator = Validator::make(
            [],
            $altitudeRequest->rules(),
            $altitudeRequest->messages()
        );
        
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('altitudes'));
        
        // Test non-array altitudes
        $validator = Validator::make(
            ['altitudes' => 'not-an-array'],
            $altitudeRequest->rules(),
            $altitudeRequest->messages()
        );
        
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('altitudes'));

        // Test non-integer altitudes
        $validator = Validator::make(
            ['altitudes' => ['not-an-integer']],
            $altitudeRequest->rules(),
            $altitudeRequest->messages()
        );
        var_dump($validator->fails());
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('altitudes.0'));

        // Test negative altitudes
        $validator = Validator::make(
            ['altitudes' => [-1]],
            $altitudeRequest->rules(),
            $altitudeRequest->messages()
        );
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('altitudes.0'));


    }
}
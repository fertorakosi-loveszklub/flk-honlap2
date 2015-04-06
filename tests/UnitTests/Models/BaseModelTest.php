<?php

use App\BaseModel;

class BaseModelTest extends TestCase
{
    public function testIfValidationPassesIfThereAreNoRules()
    {
        $baseModel = Mockery::mock('App\BaseModel[getValidationRules]');
        $baseModel->shouldReceive('getValidationRules')
                  ->once()
                  ->andReturn(null);

        $this->assertTrue($baseModel->validate(), 'Failed asserting that validation passes if there are no validation rules defined.');
    }

    public function testIfValidationPassesIfValidatorReportsSuccess()
    {
        // Validator should not fail
        $validator = Mockery::mock('MockedValidator');
        $validator->shouldReceive('fails')
                  ->once()
                  ->andReturn(false);

        // Validator facade should return the mocked validator object
        Validator::shouldReceive('make')
                 ->once()
                 ->andReturn($validator);

        // Create a mock instance and return any non-null array
        $baseModel = Mockery::mock('App\BaseModel[getValidationRules]');
        $baseModel->shouldReceive('getValidationRules')
                  ->once()
                  ->andReturn(array('lol' => 'lol'));

        $this->assertTrue($baseModel->validate(), 'Failed asserting that validation passes when the Validator reports success.');
    }

    public function testIfValidationFailsWhenValidatorReportsFailure()
    {
        // Validator should fail
        $validator = Mockery::mock('MockedValidator');
        $validator->shouldReceive('fails')
                  ->once()
                  ->andReturn(true);

        $validator->shouldReceive('messages')
                  ->once();

        // Validator facade should return the mocked validator object
        Validator::shouldReceive('make')
                 ->once()
                 ->andReturn($validator);

        // Create a mock instance and return any non-null array
        $baseModel = Mockery::mock('App\BaseModel[getValidationRules]');
        $baseModel->shouldReceive('getValidationRules')
                  ->once()
                  ->andReturn(array(['test' => 'test']));

        $this->assertFalse($baseModel->validate(), 'Failed asserting that validation fails when the Validator reports failure.');
    }

    public function testIfErrorMessagesAreSetWhenValidatorFails()
    {
        $expected = ['message' => 'Expected error message'];

        // Validator should fail
        $validator = Mockery::mock('MockedValidator');
        $validator->shouldReceive('fails')
                  ->once()
                  ->andReturn(true);

        $validator->shouldReceive('messages')
                  ->once()
                  ->andReturn($expected);

        // Validator facade should return the mocked validator object
        Validator::shouldReceive('make')
                 ->once()
                 ->andReturn($validator);

        // Create a mock instance and return any non-null array
        $baseModel = Mockery::mock('App\BaseModel[getValidationRules]');
        $baseModel->shouldReceive('getValidationRules')
                  ->once()
                  ->andReturn(array(['test' => 'test']));

        $baseModel->validate();

        $this->assertSame($expected, $baseModel->getValidationErrors());
    }
}

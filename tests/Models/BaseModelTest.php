<?php

use App\BaseModel;
use Validator;

class BaseModelTest extends TestCase
{
    public function test_if_validation_passes_if_there_are_no_rules()
    {
        $baseModel = Mockery::mock('App\BaseModel[getValidationRules]');
        $baseModel->shouldReceive('getValidationRules')
                  ->once()
                  ->andReturn(null);

        $this->assertTrue($baseModel->validate(), 'Failed asserting that validation passes if there are no validation rules defined.');
    }

    public function test_if_validation_passes_if_validator_reports_success()
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

    public function test_if_validation_fails_if_validator_reports_failure()
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

    public function test_if_error_messages_are_set_when_validation_fails()
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

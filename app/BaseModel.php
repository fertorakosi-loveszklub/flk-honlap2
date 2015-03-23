<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

abstract class BaseModel extends Model implements ValidatableInterface
{
    /**
     * Validation rules.
     * @var array
     */
    protected $validationRules = null;

    protected $validationErrors = null;

    /**
     * Validates the current object.
     * @return bool A value indicating whether the object is valid.
     */
    public function validate()
    {
        $rules = $this->getValidationRules();
        if ($rules == null)
        {
            // If there are no validation rules, object is valid
            return true;
        }

        $validator = Validator::make($this["attributes"], $rules);

        if ($validator->fails())
        {
            // Errors during validation
            $this->validationErrors = $validator->messages();
            return false;
        }
        else
        {
            // No validation errors
            $this->validationErrors = null;
            return true;
        }
    }

    /**
     * Gets the errors if the previous validate() method call returned false.
     * @return array Array of errors if object is not valid, otherwise null..
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * Gets the validation rules.
     * @return array Validation rules.
     */
    public function getValidationRules()
    {
        return $this->validationRules;
    }
}

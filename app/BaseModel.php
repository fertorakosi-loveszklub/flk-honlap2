<?php
namespace App;

use Crypt;
use Illuminate\Database\Eloquent\Model;
use Validator;

abstract class BaseModel extends Model implements ValidatableInterface
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = null;

    protected $validationErrors = null;

    /**
     * Validates the current object.
     *
     * @param  $ignoredUniqueFields An array of unique keys to ignore. These
     *                              will be ignored when  applying the 'unique'
     *                              rule. Defaults to null.
     *                              Make sure to make the 'unique' rule the last
     *                              in the rule definitions!
     * @param  $encryptedFields     Array of the names of the fields that should
     *                              be decrypted before validation.
     * @return bool A value indicating whether the object is valid.
     */
    public function validate($ignoredUniqueFields = null,
                                $encryptedFields = null)
    {
        $rules = $this->getValidationRules();
        if ($rules == null) {
            // If there are no validation rules, object is valid
            return true;
        }

        // Ignore rules specified in $ignoredUniqueIds
        if (!is_null($ignoredUniqueFields)) {
            foreach ($ignoredUniqueFields as $key) {
                $rules[$key] .= "," . $this->id;
            }
        }

        // Get fields
        $fields = $this["attributes"];

        // Decrypt encrypted fields
        if (!is_null($encryptedFields)) {
            foreach ($encryptedFields as $key) {
                $fields[$key] = Crypt::decrypt($fields[$key]);
            }
        }

        $validator = Validator::make($fields, $rules);

        if ($validator->fails()) {
            // Errors during validation
            $this->validationErrors = $validator->messages();

            return false;
        } else {
            // No validation errors
            $this->validationErrors = null;

            return true;
        }
    }

    /**
     * Gets the errors if the previous validate() method call returned false.
     *
     * @return array Array of errors if object is not valid, otherwise null..
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * Gets the validation rules.
     *
     * @return array Validation rules.
     */
    public function getValidationRules()
    {
        return $this->validationRules;
    }
}

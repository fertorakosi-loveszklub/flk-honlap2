<?php
namespace App;

interface ValidatableInterface
{
    /**
     * Validates the current object.
     * @return bool A value indicating whether the object is valid.
     */
    public function validate();

    /**
     * Gets the errors if the previous validate() method call returned false.
     * @return array Array of errors if object is not valid, otherwise null..
     */
    public function getValidationErrors();
}

<?php

/**
 * Test Validator class
*/

class TestValidator extends Asatru\Controller\BaseValidator {
	private $error = '';
	
	/**
	 * Shall return the name of this validator
	 * 
	 * @return string The identifier of the validator
	 */
	public function getIdent()
	{
		return 'testvalidator';
	}

	/**
	 * Shall validate a token
	 * 
	 * @param mixed $value The value of the item to be verified
	 * @param string $args optional The validator arguments if any
	 * @return boolean True if the item is valid, otherwise false
	 */
	public function verify($value, $args = null)
	{
		if ($value !== $args) {
			$this->error = 'Invalid values';
			return false;
		}
		
		return true;
	}

	/**
	 * Shall return an error description if any
	 * 
	 * @return string A description of the error
	 */
	public function getError()
	{
		return $this->error;
	}
}
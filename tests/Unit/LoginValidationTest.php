<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The validation rules for login
     */
    private function getLoginValidationRules()
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    /**
     * Test email is required
     */
    public function test_email_is_required()
    {
        $data = [
            'email' => '',
            'password' => 'password123'
        ];

        $validator = Validator::make($data, $this->getLoginValidationRules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /**
     * Test password is required
     */
    public function test_password_is_required()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => ''
        ];

        $validator = Validator::make($data, $this->getLoginValidationRules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /**
     * Test email must be valid email format
     */
    public function test_email_must_be_valid_format()
    {
        $invalidEmails = [
            'invalid-email',
            'invalid@',
            '@invalid.com',
            'invalid..email@test.com',
            'invalid email@test.com'
        ];

        foreach ($invalidEmails as $email) {
            $data = [
                'email' => $email,
                'password' => 'password123'
            ];

            $validator = Validator::make($data, $this->getLoginValidationRules());

            $this->assertTrue($validator->fails(), "Email '{$email}' should be invalid");
            $this->assertArrayHasKey('email', $validator->errors()->toArray());
        }
    }

    /**
     * Test valid email formats pass validation
     */
    public function test_valid_email_formats_pass_validation()
    {
        $validEmails = [
            'test@example.com',
            'user.name@example.com',
            'user+tag@example.com',
            'user123@example123.com',
            'test@sub.example.com'
        ];

        foreach ($validEmails as $email) {
            $data = [
                'email' => $email,
                'password' => 'password123'
            ];

            $validator = Validator::make($data, $this->getLoginValidationRules());

            $this->assertFalse($validator->fails(), "Email '{$email}' should be valid");
        }
    }

    /**
     * Test email must be string
     */
    public function test_email_must_be_string()
    {
        $data = [
            'email' => 123456, // Non-string value
            'password' => 'password123'
        ];

        $validator = Validator::make($data, $this->getLoginValidationRules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /**
     * Test password must be string
     */
    public function test_password_must_be_string()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 123456 // Non-string value
        ];

        $validator = Validator::make($data, $this->getLoginValidationRules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /**
     * Test both email and password are required
     */
    public function test_both_email_and_password_are_required()
    {
        $data = [
            'email' => '',
            'password' => ''
        ];

        $validator = Validator::make($data, $this->getLoginValidationRules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /**
     * Test validation passes with valid data
     */
    public function test_validation_passes_with_valid_data()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $validator = Validator::make($data, $this->getLoginValidationRules());

        $this->assertFalse($validator->fails());
        $this->assertEmpty($validator->errors()->toArray());
    }

    /**
     * Test null values fail validation
     */
    public function test_null_values_fail_validation()
    {
        $data = [
            'email' => null,
            'password' => null
        ];

        $validator = Validator::make($data, $this->getLoginValidationRules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /**
     * Test very long email fails validation
     */
    public function test_very_long_email_passes_basic_validation()
    {
        // Create a very long but valid email
        $longEmail = str_repeat('a', 250) . '@example.com';
        
        $data = [
            'email' => $longEmail,
            'password' => 'password123'
        ];

        $validator = Validator::make($data, $this->getLoginValidationRules());

        // Basic validation should pass (email format is valid)
        // Note: Laravel's email validation doesn't have a max length by default
        $this->assertFalse($validator->fails());
    }

    /**
     * Test very long password passes basic validation
     */
    public function test_very_long_password_passes_basic_validation()
    {
        $longPassword = str_repeat('a', 1000);
        
        $data = [
            'email' => 'test@example.com',
            'password' => $longPassword
        ];

        $validator = Validator::make($data, $this->getLoginValidationRules());

        // Basic validation should pass (no max length specified)
        $this->assertFalse($validator->fails());
    }

    /**
     * Test special characters in password are allowed
     */
    public function test_special_characters_in_password_are_allowed()
    {
        $specialPasswords = [
            'pass@word123',
            'p@$$w0rd!',
            'password#123',
            'pass word', // spaces
            'пароль123', // non-latin characters
        ];

        foreach ($specialPasswords as $password) {
            $data = [
                'email' => 'test@example.com',
                'password' => $password
            ];

            $validator = Validator::make($data, $this->getLoginValidationRules());

            $this->assertFalse($validator->fails(), "Password '{$password}' should be valid");
        }
    }

    /**
     * Test email case sensitivity (emails should be case insensitive)
     */
    public function test_email_case_variations_are_valid()
    {
        $emailVariations = [
            'test@example.com',
            'TEST@EXAMPLE.COM',
            'Test@Example.Com',
            'tEsT@eXaMpLe.CoM'
        ];

        foreach ($emailVariations as $email) {
            $data = [
                'email' => $email,
                'password' => 'password123'
            ];

            $validator = Validator::make($data, $this->getLoginValidationRules());

            $this->assertFalse($validator->fails(), "Email '{$email}' should be valid");
        }
    }

    /**
     * Test minimum password length (no minimum enforced in basic validation)
     */
    public function test_very_short_password_passes_basic_validation()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'a' // Single character
        ];

        $validator = Validator::make($data, $this->getLoginValidationRules());

        // Basic login validation doesn't enforce minimum length
        $this->assertFalse($validator->fails());
    }

    /**
     * Test empty string vs null distinction
     */
    public function test_empty_string_vs_null_distinction()
    {
        // Empty string
        $data1 = [
            'email' => '',
            'password' => ''
        ];

        $validator1 = Validator::make($data1, $this->getLoginValidationRules());
        $this->assertTrue($validator1->fails());

        // Null values
        $data2 = [
            'email' => null,
            'password' => null
        ];

        $validator2 = Validator::make($data2, $this->getLoginValidationRules());
        $this->assertTrue($validator2->fails());

        // Missing keys entirely
        $data3 = [];

        $validator3 = Validator::make($data3, $this->getLoginValidationRules());
        $this->assertTrue($validator3->fails());
    }
}
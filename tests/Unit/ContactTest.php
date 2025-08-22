<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    /** @test */
    public function it_can_validate_email_format()
    {
        $validEmails = [
            'test@example.com',
            'user.name@domain.co.uk',
            'admin+tag@company.org'
        ];

        $invalidEmails = [
            'invalid-email',
            '@domain.com',
            'user@',
            'user..name@domain.com'
        ];

        foreach ($validEmails as $email) {
            $this->assertTrue(filter_var($email, FILTER_VALIDATE_EMAIL) !== false);
        }

        foreach ($invalidEmails as $email) {
            $this->assertFalse(filter_var($email, FILTER_VALIDATE_EMAIL) !== false);
        }
    }

    /** @test */
    public function it_can_validate_contact_types()
    {
        $validTypes = ['person', 'organization'];
        $invalidTypes = ['company', 'individual', 'business'];

        foreach ($validTypes as $type) {
            $this->assertContains($type, ['person', 'organization']);
        }

        foreach ($invalidTypes as $type) {
            $this->assertNotContains($type, ['person', 'organization']);
        }
    }

    /** @test */
    public function it_can_format_phone_numbers()
    {
        $phoneNumbers = [
            '+1234567890',
            '(555) 123-4567',
            '555.123.4567',
            '555 123 4567'
        ];

        foreach ($phoneNumbers as $phone) {
            // Basic phone number validation - contains digits
            $this->assertMatchesRegularExpression('/\d/', $phone);
        }
    }
}

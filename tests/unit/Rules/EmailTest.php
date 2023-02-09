<?php

declare(strict_types=1);

namespace StellarWP\Validation\Tests\Unit\Rules;

use StellarWP\Validation\Rules\Email;
use StellarWP\Validation\Tests\TestCase;

class EmailTest extends TestCase
{
    /**
     * @since 1.1.0
     *
     * @dataProvider emailsProvider
     */
    public function testEmailRule($email, bool $shouldBeValid)
    {
        $rule = new Email();

        if ($shouldBeValid) {
            self::assertValidationRulePassed($rule, $email);
        } else {
            self::assertValidationRuleFailed($rule, $email);
        }
    }

    /**
     * @since 1.1.0
     *
     * @return array<int, array<mixed, bool>>
     */
    public function emailsProvider(): array
    {
        return [
            // Valid emails
            ['jason.adams@givewp.com', true],
            ['bill123@example.com', true],

            // Invalid emails
            [true, false],
            [123, false],
            ['jason.adams', false],
            ['jason.adams@', false],
            ['jason.adams@givewp', false],
            ['jason.adams@givewp.', false],
        ];
    }
}

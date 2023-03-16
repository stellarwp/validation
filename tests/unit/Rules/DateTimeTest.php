<?php

namespace StellarWP\Validation\Tests\Unit\Rules;

use DateTime;
use DateTimeImmutable;
use StellarWP\Validation\Rules\DateTime as DateTimeRule;
use StellarWP\Validation\Tests\TestCase;

class DateTimeTest extends TestCase
{
    /**
     * @unreleased
     */
    public function testShouldPassDateTimeInstance()
    {
        $rule = new DateTimeRule();

        $this->assertValidationRulePassed($rule, new DateTime());
        $this->assertValidationRulePassed($rule, new DateTimeImmutable());
    }

    /**
     * @unreleased
     */
    public function testShouldPassDateTimeString()
    {
        $rule = new DateTimeRule();

        $this->assertValidationRulePassed($rule, '2018-01-01 00:00:00');
        $this->assertValidationRulePassed($rule, '2018-01-01 00:00:00.000000');
        $this->assertValidationRulePassed($rule, '2018-01-01T00:00:00+00:00');
    }

    /**
     * @unreleased
     */
    public function testShouldFailRelativeDateTimeString()
    {
        $rule = new DateTimeRule();

        $this->assertValidationRuleFailed($rule, 'now');
        $this->assertValidationRuleFailed($rule, 'tomorrow');
        $this->assertValidationRuleFailed($rule, 'yesterday');
    }

    /**
     * @unreleased
     */
    public function testShouldFailInvalidDateTimeStringValues()
    {
        $rule = new DateTimeRule();

        $this->assertValidationRuleFailed($rule, true);
        $this->assertValidationRuleFailed($rule, []);
        $this->assertValidationRuleFailed($rule, 'abc');
    }

    /**
     * @unreleased
     */
    public function testShouldPassDateTimeStringWithFormat()
    {
        $rule = new DateTimeRule('Y-m-d H:i:s');

        $this->assertValidationRulePassed($rule, '2018-01-01 00:00:00');
        $this->assertValidationRuleFailed($rule, '2018-01-01 00:00:00.000000');
        $this->assertValidationRuleFailed($rule, '2018-01-01T00:00:00+00:00');
    }

    /**
     * @unreleased
     */
    public function testShouldReturnSameDateTimeInstanceWhenSanitizing()
    {
        $rule = new DateTimeRule();

        $date = new DateTimeImmutable();
        $this->assertSame($date, $rule->sanitize($date));
    }

    /**
     * @unreleased
     */
    public function testShouldReturnDateTimeInstanceWhenSanitizingDateTimeString()
    {
        $rule = new DateTimeRule();

        $date = new DateTimeImmutable();
        $this->assertEquals(
            $date->format('Y-m-d H:i:s'),
            $rule->sanitize($date->format('Y-m-d H:i:s'))->format('Y-m-d H:i:s')
        );
    }
}

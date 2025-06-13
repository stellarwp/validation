git <?php

declare(strict_types=1);

namespace StellarWP\Validation\Tests\Unit\Rules;

use StellarWP\Validation\Rules\Currency;
use StellarWP\Validation\Tests\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * @since 1.1.0
     * @dataProvider currencyProvider
     */
    public function testCurrencyValidations($currency, $shouldPass)
    {
        $rule = new Currency();

        if ($shouldPass) {
            self::assertValidationRulePassed($rule, $currency);
        } else {
            self::assertValidationRuleFailed($rule, $currency);
        }
    }

    /**
     * Test custom currency codes override functionality.
     *
     * @since 1.1.0
     */
    public function testCustomCurrencyCodesOverride()
    {
        // Test with custom currency codes
        $customCodes = ['XYZ', 'ABC', 'DEF'];
        $rule = new Currency($customCodes);

        // Custom codes should pass
        self::assertValidationRulePassed($rule, 'XYZ');
        self::assertValidationRulePassed($rule, 'ABC');
        self::assertValidationRulePassed($rule, 'def'); // case insensitive

        // Standard codes should fail when using custom list
        self::assertValidationRuleFailed($rule, 'USD');
        self::assertValidationRuleFailed($rule, 'EUR');
    }

    /**
     * Test that obsolete currency codes no longer pass validation.
     *
     * @since 1.1.0
     * @dataProvider obsoleteCurrencyProvider
     */
    public function testObsoleteCurrencyCodesFail($currency)
    {
        $rule = new Currency();
        self::assertValidationRuleFailed($rule, $currency);
    }

    /**
     * Test newly added currency codes pass validation.
     *
     * @since 1.1.0
     * @dataProvider newCurrencyProvider
     */
    public function testNewCurrencyCodesPass($currency)
    {
        $rule = new Currency();
        self::assertValidationRulePassed($rule, $currency);
    }

    /**
     * Test case insensitivity with various currency codes.
     *
     * @since 1.1.0
     * @dataProvider caseInsensitiveProvider
     */
    public function testCaseInsensitivity($currency, $shouldPass)
    {
        $rule = new Currency();

        if ($shouldPass) {
            self::assertValidationRulePassed($rule, $currency);
        } else {
            self::assertValidationRuleFailed($rule, $currency);
        }
    }

    /**
     * @since 1.1.0
     */
    public function currencyProvider(): array
    {
        return [
            // Common major currencies
            ['USD', true],
            ['EUR', true],
            ['JPY', true],
            ['GBP', true],
            ['CAD', true],
            ['AUD', true],
            ['CHF', true],

            // Case insensitive
            ['usd', true],
            ['eur', true],
            ['jpy', true],
            ['EuR', true],
            ['CaD', true],

            // Newly added currencies (2024 update)
            ['AED', true], // UAE Dirham
            ['BHD', true], // Bahraini Dinar
            ['KWD', true], // Kuwaiti Dinar
            ['QAR', true], // Qatari Riyal
            ['SAR', true], // Saudi Riyal
            ['VES', true], // Venezuelan Bolívar Soberano (new)
            ['BYN', true], // Belarusian Ruble (new)
            ['GHS', true], // Ghanaian Cedi (new)

            // Regional currencies
            ['XAF', true], // Central African CFA Franc
            ['XOF', true], // West African CFA Franc
            ['XCD', true], // East Caribbean Dollar
            ['XPF', true], // CFP Franc

            // Special codes
            ['XDR', true], // Special Drawing Rights

            // Non-ISO but commonly used (kept in list)
            ['GGP', true], // Guernsey Pound
            ['IMP', true], // Isle of Man Pound
            ['JEP', true], // Jersey Pound
            ['TVD', true], // Tuvaluan Dollar

            // Invalid codes
            ['US', false],     // Too short
            ['USDD', false],   // Too long
            ['US D', false],   // Contains space
            ['US-D', false],   // Contains hyphen
            ['ABC', false],    // Not a valid currency
            ['123', false],    // Numeric
            ['', false],       // Empty string
            ['XXX', false],    // Invalid code
            ['ZZZ', false],    // Non-existent
        ];
    }

    /**
     * Currency codes that were removed in the 2024 update.
     *
     * @since 1.1.0
     */
    public function obsoleteCurrencyProvider(): array
    {
        return [
            ['BYR'], // Old Belarusian Ruble (replaced by BYN)
            ['EEK'], // Estonian Kroon (replaced by EUR)
            ['GHC'], // Old Ghanaian Cedi (replaced by GHS)
            ['LVL'], // Latvian Lats (replaced by EUR)
            ['LTL'], // Lithuanian Litas (replaced by EUR)
            ['TRL'], // Old Turkish Lira (now only TRY)
            ['VEF'], // Old Venezuelan Bolívar (replaced by VES)
            ['ZWD'], // Old Zimbabwean Dollar (replaced by ZWL)
        ];
    }

    /**
     * New currency codes added in the 2024 update.
     *
     * @since 1.1.0
     */
    public function newCurrencyProvider(): array
    {
        return [
            ['AED'], // UAE Dirham
            ['AMD'], // Armenian Dram
            ['AOA'], // Angolan Kwanza
            ['BHD'], // Bahraini Dinar
            ['BIF'], // Burundian Franc
            ['BND'], // Brunei Dollar
            ['BTN'], // Bhutanese Ngultrum
            ['BYN'], // Belarusian Ruble (new)
            ['CDF'], // Congolese Franc
            ['CVE'], // Cape Verdean Escudo
            ['DJF'], // Djiboutian Franc
            ['DZD'], // Algerian Dinar
            ['ERN'], // Eritrean Nakfa
            ['ETB'], // Ethiopian Birr
            ['GEL'], // Georgian Lari
            ['GHS'], // Ghanaian Cedi (new)
            ['GMD'], // Gambian Dalasi
            ['GNF'], // Guinean Franc
            ['HTG'], // Haitian Gourde
            ['IQD'], // Iraqi Dinar
            ['JOD'], // Jordanian Dinar
            ['KES'], // Kenyan Shilling
            ['KMF'], // Comorian Franc
            ['KWD'], // Kuwaiti Dinar
            ['LSL'], // Lesotho Loti
            ['LYD'], // Libyan Dinar
            ['MAD'], // Moroccan Dirham
            ['MDL'], // Moldovan Leu
            ['MGA'], // Malagasy Ariary
            ['MMK'], // Myanmar Kyat
            ['MOP'], // Macanese Pataca
            ['MRU'], // Mauritanian Ouguiya
            ['MVR'], // Maldivian Rufiyaa
            ['MWK'], // Malawian Kwacha
            ['PGK'], // Papua New Guinean Kina
            ['RWF'], // Rwandan Franc
            ['SDG'], // Sudanese Pound
            ['SLE'], // Sierra Leonean Leone (new)
            ['SLL'], // Sierra Leonean Leone (old)
            ['SSP'], // South Sudanese Pound
            ['STN'], // São Tomé and Príncipe Dobra
            ['SZL'], // Swazi Lilangeni
            ['TJS'], // Tajikistani Somoni
            ['TMT'], // Turkmenistani Manat
            ['TND'], // Tunisian Dinar
            ['TOP'], // Tongan Paʻanga
            ['TZS'], // Tanzanian Shilling
            ['UGX'], // Ugandan Shilling
            ['VED'], // Venezuelan Bolívar Digital
            ['VES'], // Venezuelan Bolívar Soberano
            ['VUV'], // Vanuatuan Vatu
            ['WST'], // Samoan Tala
            ['XAF'], // Central African CFA Franc
            ['XDR'], // Special Drawing Rights
            ['XOF'], // West African CFA Franc
            ['XPF'], // CFP Franc
            ['ZMW'], // Zambian Kwacha
            ['ZWL'], // Zimbabwean Dollar (new)
        ];
    }

    /**
     * Test various case combinations.
     *
     * @since 1.1.0
     */
    public function caseInsensitiveProvider(): array
    {
        return [
            ['USD', true],
            ['usd', true],
            ['Usd', true],
            ['UsD', true],
            ['EUR', true],
            ['eur', true],
            ['eUr', true],
            ['EuR', true],
            ['JPY', true],
            ['jpy', true],
            ['JpY', true],
            ['jPy', true],
            // Invalid codes in various cases
            ['abc', false],
            ['ABC', false],
            ['AbC', false],
            ['aBc', false],
        ];
    }
}

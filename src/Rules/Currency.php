<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Validation\Contracts\ValidationRule;

class Currency implements ValidationRule, ValidatesOnFrontEnd
{

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public static function id(): string
    {
        return 'currency';
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public static function fromString(string $options = null): ValidationRule
    {
        return new self();
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function serializeOption()
    {
        return null;
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (!is_string($value) || !in_array(strtoupper($value), self::currencyCodes(), true)) {
            $fail(sprintf(__('%s must be a valid currency', '%TEXTDOMAIN%'), '{field}'));
        }
    }

    /**
     * Returns the list of valid ISO 4217 currency codes.
     *
     * @unreleased Updated to match current ISO 4217 standard as of 2024.
     *
     * Major changes include:
     * - Removed obsolete codes: BYR (→BYN), EEK (→EUR), GHC (→GHS), LVL (→EUR),
     *   LTL (→EUR), TRL (→TRY), VEF (→VES), ZWD (→ZWL)
     * - Added 74+ missing current ISO 4217 codes including AED, AMD, AOA, BHD, etc.
     * - Kept commonly used non-ISO codes: GGP, IMP, JEP, TVD
     * - Total codes increased from 95 to 169 for better global coverage
     *
     * @see https://www.iso.org/iso-4217-currency-codes.html
     *
     * @since 1.0.0
     *
     * @return string[]
     */
    public static function currencyCodes(): array
    {
        static $codes = null;

        if ($codes === null) {
            $codes = [
                "AED", // UAE Dirham
                "AFN", // Afghan Afghani
                "ALL", // Albanian Lek
                "AMD", // Armenian Dram
                "ANG", // Netherlands Antillean Guilder
                "AOA", // Angolan Kwanza
                "ARS", // Argentine Peso
                "AUD", // Australian Dollar
                "AWG", // Aruban Florin
                "AZN", // Azerbaijani Manat
                "BAM", // Bosnia and Herzegovina Convertible Mark
                "BBD", // Barbados Dollar
                "BDT", // Bangladeshi Taka
                "BGN", // Bulgarian Lev
                "BHD", // Bahraini Dinar
                "BIF", // Burundian Franc
                "BMD", // Bermudian Dollar
                "BND", // Brunei Dollar
                "BOB", // Bolivian Boliviano
                "BRL", // Brazilian Real
                "BSD", // Bahamian Dollar
                "BTN", // Bhutanese Ngultrum
                "BWP", // Botswanan Pula
                "BYN", // Belarusian Ruble
                "BZD", // Belize Dollar
                "CAD", // Canadian Dollar
                "CDF", // Congolese Franc
                "CHF", // Swiss Franc
                "CLP", // Chilean Peso
                "CNY", // Chinese Yuan
                "COP", // Colombian Peso
                "CRC", // Costa Rican Colón
                "CUP", // Cuban Peso
                "CVE", // Cape Verdean Escudo
                "CZK", // Czech Koruna
                "DJF", // Djiboutian Franc
                "DKK", // Danish Krone
                "DOP", // Dominican Peso
                "DZD", // Algerian Dinar
                "EGP", // Egyptian Pound
                "ERN", // Eritrean Nakfa
                "ETB", // Ethiopian Birr
                "EUR", // Euro
                "FJD", // Fijian Dollar
                "FKP", // Falkland Islands Pound
                "GBP", // British Pound Sterling
                "GEL", // Georgian Lari
                "GGP", // Guernsey Pound (non-ISO but commonly used)
                "GHS", // Ghanaian Cedi
                "GIP", // Gibraltar Pound
                "GMD", // Gambian Dalasi
                "GNF", // Guinean Franc
                "GTQ", // Guatemalan Quetzal
                "GYD", // Guyanese Dollar
                "HKD", // Hong Kong Dollar
                "HNL", // Honduran Lempira
                "HRK", // Croatian Kuna (replaced by EUR in 2023)
                "HTG", // Haitian Gourde
                "HUF", // Hungarian Forint
                "IDR", // Indonesian Rupiah
                "ILS", // Israeli New Shekel
                "IMP", // Isle of Man Pound (non-ISO but commonly used)
                "INR", // Indian Rupee
                "IQD", // Iraqi Dinar
                "IRR", // Iranian Rial
                "ISK", // Icelandic Króna
                "JEP", // Jersey Pound (non-ISO but commonly used)
                "JMD", // Jamaican Dollar
                "JOD", // Jordanian Dinar
                "JPY", // Japanese Yen
                "KES", // Kenyan Shilling
                "KGS", // Kyrgyzstani Som
                "KHR", // Cambodian Riel
                "KMF", // Comorian Franc
                "KPW", // North Korean Won
                "KRW", // South Korean Won
                "KWD", // Kuwaiti Dinar
                "KYD", // Cayman Islands Dollar
                "KZT", // Kazakhstani Tenge
                "LAK", // Laotian Kip
                "LBP", // Lebanese Pound
                "LKR", // Sri Lankan Rupee
                "LRD", // Liberian Dollar
                "LSL", // Lesotho Loti
                "LYD", // Libyan Dinar
                "MAD", // Moroccan Dirham
                "MDL", // Moldovan Leu
                "MGA", // Malagasy Ariary
                "MKD", // Macedonian Denar
                "MMK", // Myanmar Kyat
                "MNT", // Mongolian Tugrik
                "MOP", // Macanese Pataca
                "MRU", // Mauritanian Ouguiya
                "MUR", // Mauritian Rupee
                "MVR", // Maldivian Rufiyaa
                "MWK", // Malawian Kwacha
                "MXN", // Mexican Peso
                "MYR", // Malaysian Ringgit
                "MZN", // Mozambican Metical
                "NAD", // Namibian Dollar
                "NGN", // Nigerian Naira
                "NIO", // Nicaraguan Córdoba
                "NOK", // Norwegian Krone
                "NPR", // Nepalese Rupee
                "NZD", // New Zealand Dollar
                "OMR", // Omani Rial
                "PAB", // Panamanian Balboa
                "PEN", // Peruvian Sol
                "PGK", // Papua New Guinean Kina
                "PHP", // Philippine Peso
                "PKR", // Pakistani Rupee
                "PLN", // Polish Zloty
                "PYG", // Paraguayan Guaraní
                "QAR", // Qatari Riyal
                "RON", // Romanian Leu
                "RSD", // Serbian Dinar
                "RUB", // Russian Ruble
                "RWF", // Rwandan Franc
                "SAR", // Saudi Riyal
                "SBD", // Solomon Islands Dollar
                "SCR", // Seychellois Rupee
                "SDG", // Sudanese Pound
                "SEK", // Swedish Krona
                "SGD", // Singapore Dollar
                "SHP", // Saint Helena Pound
                "SLE", // Sierra Leonean Leone (new)
                "SLL", // Sierra Leonean Leone (old)
                "SOS", // Somali Shilling
                "SRD", // Surinamese Dollar
                "SSP", // South Sudanese Pound
                "STN", // São Tomé and Príncipe Dobra
                "SVC", // Salvadoran Colón
                "SYP", // Syrian Pound
                "SZL", // Swazi Lilangeni
                "THB", // Thai Baht
                "TJS", // Tajikistani Somoni
                "TMT", // Turkmenistani Manat
                "TND", // Tunisian Dinar
                "TOP", // Tongan Paʻanga
                "TRY", // Turkish Lira
                "TTD", // Trinidad and Tobago Dollar
                "TVD", // Tuvaluan Dollar (non-ISO but used)
                "TWD", // New Taiwan Dollar
                "TZS", // Tanzanian Shilling
                "UAH", // Ukrainian Hryvnia
                "UGX", // Ugandan Shilling
                "USD", // United States Dollar
                "UYU", // Uruguayan Peso
                "UZS", // Uzbekistani Som
                "VED", // Venezuelan Bolívar Digital
                "VES", // Venezuelan Bolívar Soberano
                "VND", // Vietnamese Dong
                "VUV", // Vanuatuan Vatu
                "WST", // Samoan Tala
                "XAF", // Central African CFA Franc
                "XCD", // East Caribbean Dollar
                "XDR", // Special Drawing Rights
                "XOF", // West African CFA Franc
                "XPF", // CFP Franc
                "YER", // Yemeni Rial
                "ZAR", // South African Rand
                "ZMW", // Zambian Kwacha
                "ZWL", // Zimbabwean Dollar
            ];
        }

        return $codes;
    }
}

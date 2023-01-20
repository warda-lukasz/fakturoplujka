<?php

namespace Utils;

use NumberFormatter;

class Formatter
{
    const MONETARY_UNIT_GR = 'gr';

    const MONETARY_UNIT_PLN = 'pln';

    /**
     * @var NumberFormatter
     */
    private $currency;

    /**
     * @var NumberFormatter
     */
    private $spellout;

    public function __construct($locale = 'pl_PL')
    {
        $this->currency = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $this->spellout = new NumberFormatter($locale, NumberFormatter::SPELLOUT);
    }

    /**
     * @param string $value
     * @param int $numberFormatterStyle
     * @return string
     */
    public function getFormattedNumber(string $value, int $numberFormatterStyle = NumberFormatter::CURRENCY): string
    {
        switch ($numberFormatterStyle) {
            case NumberFormatter::SPELLOUT:
                return $this->getSpellOut($value);
                break;
            case NumberFormatter::PERCENT:
                return $value * 100 . '\\%';
            default:
                return $this->currency->format($value);
        }
    }

    /**
     * @param $value
     * @return string
     */
    private function getSpellOut($value): string
    {
        $arr = $this->convertToCurrencyParts($value);

        if (isset($arr[1])) {
            return ucfirst(
                sprintf('%s %s i %s %s',
                    trim($this->spellout->format($arr[0])),
                    $this->getPolishDeclension(trim($arr[0])),
                    trim($this->spellout->format($arr[1])),
                    $this->getPolishDeclension(trim($arr[1]), self::MONETARY_UNIT_GR)
                )
            );
        }

        return ucfirst(
            str_ireplace(
                'zero',
                '',
                $this->spellout->format($value) . ' ' . $this->getPolishDeclension($value)
            )
        );
    }

    private function convertToCurrencyParts($value): array
    {
        $values = explode('.', $value);

        if (isset($values[1]) && strlen($values[1]) === 1) {
            $values[1] = intval($values[1]) * 10;
        }

        return $values;
    }

    /**
     * @param string $baseString
     * @param string $monetaryUnit
     * @return string
     */
    private function getPolishDeclension(string $baseString, string $monetaryUnit = self::MONETARY_UNIT_PLN): string
    {
        $stringArr = explode(' ', $baseString);
        $wordCount = count($stringArr);
        $lastWord = array_pop($stringArr);

        switch ($lastWord) {
            case in_array($lastWord, ['dwa', 'trzy', 'cztery']):
                if ($monetaryUnit === self::MONETARY_UNIT_GR) {
                    return 'grosze';
                }

                return 'złote';
            case $lastWord === 'jeden':
                if ($monetaryUnit === self::MONETARY_UNIT_GR && $wordCount === 1) {
                    return 'grosz';
                } elseif ($monetaryUnit === self::MONETARY_UNIT_GR) {
                    return 'groszy';
                }

                if ($wordCount === 1) {
                    return 'złoty';
                }

                return 'złotych';
            default:
                if ($monetaryUnit === self::MONETARY_UNIT_GR) {
                    return 'groszy';
                }

                return 'złotych';
        }
    }
}
<?php

namespace Utils;

use NumberFormatter;

class Formatter
{
    private const string MONETARY_UNIT_GR = 'gr';
    private const string MONETARY_UNIT_PLN = 'pln';
    private NumberFormatter $currency;
    private NumberFormatter $spellout;

    public function __construct(string $locale = 'pl_PL')
    {
        $this->currency = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $this->spellout = new NumberFormatter($locale, NumberFormatter::SPELLOUT);
    }

    public function getFormattedNumber(
        string $value,
        int    $numberFormatterStyle = NumberFormatter::CURRENCY
    ): string {
        return match ($numberFormatterStyle) {
            NumberFormatter::SPELLOUT => $this->getSpellOut($value),
            NumberFormatter::PERCENT => $value * 100 . '\\%',
            default => $this->currency->format($value),
        };
    }

    private function getSpellOut(string $value): string
    {
        [$main, $decimal] = $this->convertToCurrencyParts($value);

        if ($decimal) {
            return $this->formatBothCurrencyParts($main, $decimal);
        }

        return $this->formatOnlyMainPart($main);
    }

    private function convertToCurrencyParts(string $value): array
    {
        [$main, $decimal] = explode('.', $value . '.');

        if ($decimal && strlen($decimal) === 1) {
            $decimal = intval($decimal) * 10;
        }

        return [$main, $decimal];
    }

    private function formatBothCurrencyParts(int $main, int $decimal): string
    {
        return ucfirst(
            sprintf(
                '%s %s i %s %s',
                trim($this->spellout->format($main)),
                $this->getPolishDeclension(trim($main)),
                trim($this->spellout->format($decimal)),
                $this->getPolishDeclension(
                    trim($decimal),
                    self::MONETARY_UNIT_GR
                )
            )
        );
    }

    private function getPolishDeclension(
        string $baseString,
        string $monetaryUnit = self::MONETARY_UNIT_PLN
    ): string {
        $stringArr = explode(' ', $baseString);
        $wordCount = count($stringArr);
        $lastWord = array_pop($stringArr);

        return match ($lastWord) {
            'jeden' => $this->handleLastWordIsOneCase($wordCount, $monetaryUnit),
            'dwa', 'trzy', 'cztery' => $monetaryUnit === self::MONETARY_UNIT_GR ? 'grosze' : 'złote',
            default => $monetaryUnit === self::MONETARY_UNIT_GR ? 'groszy' : 'złotych'
        };
    }

    private function handleLastWordIsOneCase(
        int    $wordCount,
        string $monetaryUnit
    ): string {
        if ($wordCount === 1) {
            return $monetaryUnit === self::MONETARY_UNIT_GR ? 'grosz' : 'złoty';
        } else {
            return $monetaryUnit === self::MONETARY_UNIT_GR ? 'groszy' : 'złotych';
        }
    }

    private function formatOnlyMainPart(int $main): string
    {
        return ucfirst(
            str_ireplace(
                'zero',
                '',
                $this->spellout->format($main) . ' ' . $this->getPolishDeclension($main)
            )
        );
    }
}

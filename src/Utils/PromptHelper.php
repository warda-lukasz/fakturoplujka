<?php

namespace Utils;

use Enums\Color;
use Enums\Format;
use Enums\FormatReset;

class PromptHelper
{
    private float $startTime;
    private string $text;

    public function startClock(): void
    {
        $this->startTime = microtime(true);
    }

    private function stopClock(): float
    {
        return round((microtime(true) - $this->startTime) * 1000, 2);
    }

    public function stopClockString(): string
    {
        return $this->getFormattedText($this->stopClock().'ms', Color::Cyan, Format::Underline, Format::Bold);
    }

    public function printDivider(
        string $symbol,
        int    $amount = 30,
        Color  $color = null,
        Format ...$format
    ): void {
        $this->printl(str_repeat($symbol, $amount), $color, ...$format);
    }

    public function printl(string $text, Color $color = null, Format ...$formats): void
    {
        echo $this->getFormattedText($text, $color, ...$formats) . PHP_EOL;
    }

    public function getFormattedText(
        string $text,
        Color  $color = null,
        Format ...$formats
    ): string {
        $this->text = $text;
        $this->colorText($color);

        foreach ($formats as $format) {
            $this->formatText($format);
        }

        return $this->text;
    }

    private function colorText(Color $color = null): void
    {
        if (!$color) return;
        $this->text = $color->value . $this->text . Color::Default->value;
    }

    private function formatText(Format $format = null): void
    {
        if (!$format) return;
        $this->text = $format->value . $this->text . FormatReset::All->value;
    }

    public function printEmptyLine(int $amount = 1): void
    {
        $this->print(str_repeat(PHP_EOL, $amount));
    }

    public function print(string $text, Color $color = null, Format ...$formats): void
    {
        echo $this->getFormattedText($text, $color, ...$formats);
    }
}

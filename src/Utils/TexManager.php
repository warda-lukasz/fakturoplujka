<?php

namespace Utils;

use DateTime;
use Model\Invoice;

class TexManager
{
    private const string TEX_COMMAND = "pdflatex -interaction=nonstopmode -output-directory=%s %s.tex";
    private const string TEX_TYPE = '.tex';
    private const string PDF_TYPE = '.pdf';

    private array $invoices;

    public function __construct()
    {
        $this->invoices = (new DataManager())->getInvoices();
        FilesHelper::cleanDir(FilesHelper::TEMP_PATH);
    }

    public function parseTemplate(): void
    {
        /** @var Invoice $invoice */
        foreach ($this->invoices as $key => $invoice) {
            $this->makeFiles($invoice, $key + 1);
        }

        FilesHelper::cleanDir(FilesHelper::TEMP_PATH);
    }

    private function makeFiles(Invoice $invoice, int $invoiceNumber): void
    {
        $invoiceNumber = sprintf("%s_%s", $invoiceNumber, (new DateTime('now'))->format('m_Y'));
        $invoice->setInvoiceNumber(str_replace('_', '/', $invoiceNumber));
    
        $filename = sprintf(
            '%s_%s',
            str_replace(' ','',$invoice->getSeller()->getCompanyName()),
            $invoiceNumber
        );

        file_put_contents(
            FilesHelper::TEMP_PATH . $filename . self::TEX_TYPE,
            $this->replacePlaceholders($invoice)
        );

        $this->compileTex($filename);
    }

    private function replacePlaceholders(Invoice $invoice): string
    {
        $patternsAndReplacements = [
            ...($invoice->getPatternsAndReplacements()),
            ...($invoice->getSeller()->getPatternsAndReplacements()),
            ...($invoice->getCustomer()->getPatternsAndReplacements()),
        ];

        return preg_replace(
            array_keys($patternsAndReplacements),
            array_values($patternsAndReplacements),
            file_get_contents(FilesHelper::TEX_TEMPLATE)
        );
    }

    private function compileTex(string $filename): void
    {
        shell_exec($this->prepareCommand($filename));
        $this->moveToOutput($filename);
    }

    private function prepareCommand(string $filename): string
    {
        return sprintf(
            self::TEX_COMMAND,
            FilesHelper::TEMP_PATH,
            FilesHelper::TEMP_PATH . $filename
        );
    }
    
    private function moveToOutput(string $filename): void
    {
        rename(
            FilesHelper::TEMP_PATH . $filename . self::PDF_TYPE,
            FilesHelper::OUTPUT_BASE_PATH . pathinfo(
                FilesHelper::TEMP_PATH . $filename . self::PDF_TYPE,
                PATHINFO_BASENAME
            )
        );
    }
}

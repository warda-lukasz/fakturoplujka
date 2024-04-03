<?php

namespace Utils;

use DateTime;
use Model\Invoice;

class TexManager
{
    private const string TEX_COMMAND = "pdflatex -interaction=nonstopmode -output-directory=%s %s.tex";
    private DataManager $dataManager;
    private array $invoices;

    public function __construct()
    {
        $this->dataManager = new DataManager();
        $this->invoices = $this->dataManager->getInvoices();
        $this->cleanTemp();
    }

    private function cleanTemp(): void
    {
        if (file_exists(FilesHelper::TEMP_PATH)) {
            $ri = FilesHelper::getDirectoryIterator(FilesHelper::TEMP_PATH);

            foreach ($ri as $file) {
                $file->isDir() ? rmdir($file) : unlink($file);
            }
        }
    }

    public function parseTemplate(): void
    {
        $template = file_get_contents(FilesHelper::TEX_TEMPLATE);

        /** @var Invoice $invoice */
        foreach ($this->invoices as $key => $invoice) {
            $this->makeFiles($template, $invoice, $key);
        }

        $this->cleanTemp();
    }

    private function makeFiles(string $template, Invoice $invoice, int $arrKey): void
    {
        $invoiceNumber = sprintf("%s_%s", $arrKey + 1, (new DateTime('now'))->format('m_Y'));
        $invoice->setInvoiceNumber(str_replace('_', '/', $invoiceNumber));

        $filename = sprintf(
            "%s_%s",
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '',
                $invoice->getSeller()->getSellerCompanyName()
            ),
            $invoiceNumber
        );

        $replacedTemplate = $this->replacePlaceholders($invoice, $template);

        file_put_contents(FilesHelper::TEMP_PATH . $filename . '.tex', $replacedTemplate);
        $this->compileTex($filename);
    }

    private function replacePlaceholders(Invoice $invoice, string $template): string
    {
        $patternsAndReplacements = array_merge(
            $invoice->getPatternsAndReplacements(),
            $invoice->getSeller()->getPatternsAndReplacements(),
            $invoice->getCustomer()->getPatternsAndReplacements()
        );

        return preg_replace(
            array_keys($patternsAndReplacements),
            array_values($patternsAndReplacements),
            $template
        );
    }

    private function compileTex(string $filename): void
    {
        $command = sprintf(
            self::TEX_COMMAND,
            FilesHelper::TEMP_PATH,
            FilesHelper::TEMP_PATH . $filename
        );
        shell_exec($command);

        rename(
            FilesHelper::TEMP_PATH . $filename . '.pdf',
            FilesHelper::OUTPUT_BASE_PATH . pathinfo(
                FilesHelper::TEMP_PATH . $filename . '.pdf', PATHINFO_BASENAME
            )
        );
    }
}

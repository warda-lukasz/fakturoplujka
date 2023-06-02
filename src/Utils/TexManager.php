<?php

namespace Utils;

use DateTime;
use FilesystemIterator;
use Model\Invoice;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class TexManager
{
    const TEX_TEMPLATE = 'template/template.tex';

    const OUTPUT_BASE_PATH = 'output';

    const TEMP_PATH = 'temp/';

    const TEX_COMMAND = "pdflatex -interaction=nonstopmode -output-directory=%s %s.tex";

    /**
     * @var DataManager
     */
    private $dataManager;

    /**
     * @var array
     */
    private $invoices;

    public function __construct()
    {
        $this->dataManager = new DataManager();
        $this->invoices = $this->dataManager->getInvoices();
        $this->cleanTemp();
    }

    /**
     * @return void
     */
    private function cleanTemp(): void
    {
        if (file_exists(self::TEMP_PATH)) {
            $ri = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    self::TEMP_PATH,
                    FilesystemIterator::SKIP_DOTS
                ),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($ri as $file) {
                $file->isDir() ? rmdir($file) : unlink($file);
            }
        }
    }

    /**
     * @return void
     */
    public function parseTemplate(): void
    {
        $template = file_get_contents(self::TEX_TEMPLATE);

        /** @var Invoice $invoice */
        foreach ($this->invoices as $key => $invoice) {
            $this->makeFiles($template, $invoice, $key);
        }

        $this->cleanTemp();
    }

    /**
     * @param string $template
     * @param Invoice $invoice
     * @param int $arrKey
     * @return void
     */
    private function makeFiles(string $template, Invoice $invoice, int $arrKey): void
    {
        $invoiceNumber = sprintf("%s_%s", $arrKey + 1, (new DateTime('now'))->format('m_Y'));
        $invoice->setInvoiceNumber(str_replace('_', '/', $invoiceNumber));

        $filename = sprintf(
            "%s_%s",
            preg_replace('/[^A-Za-z0-9\-]/', '', $invoice->getSeller()->getSellerCompanyName()),
            $invoiceNumber
        );

        $replacedTemplate = $this->replacePlaceholders($invoice, $template);

        file_put_contents(self::TEMP_PATH . $filename . '.tex', $replacedTemplate);
        $this->compileTex($filename);
    }

    /**
     * @param Invoice $invoice
     * @param string $template
     * @return string
     */
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

    /**
     * @param $filename
     * @return void
     */
    private function compileTex($filename): void
    {
        $command = sprintf(self::TEX_COMMAND, self::TEMP_PATH, self::TEMP_PATH . $filename);
        shell_exec($command);

        rename(
            self::TEMP_PATH . $filename . '.pdf',
            self::OUTPUT_BASE_PATH . '/' . pathinfo(self::TEMP_PATH . $filename . '.pdf', PATHINFO_BASENAME)
        );
    }
}

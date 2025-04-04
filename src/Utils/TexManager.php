<?php

namespace Utils;

use DateTime;
use Models\Invoice;

class TexManager
{
    private const string TEX_COMMAND = "pdflatex -interaction=nonstopmode -output-directory=%s %s.tex";

    private FilesHelper $filesHelper;
    private DataManager $dataManager;

    public function __construct()
    {

        $this->dataManager = new DataManager();
        $this->filesHelper = new FilesHelper();
        $this->filesHelper->cleanDir(FilesHelper::TEMP_PATH);
    }

    public function parseTemplate(): void
    {
        foreach ($this->dataManager->getInvoices() as $invoice) {
            $this->compileTex($this->prepareInvoice($invoice));
        }

        $this->filesHelper->cleanDir(FilesHelper::TEMP_PATH);
    }

    private function prepareInvoice(Invoice $invoice): string
    {
        $invoiceNumber = sprintf(
            "%s/%s",
            $invoice->invoiceNumber,
            (new DateTime('now'))->format('m/Y')
        );

        $invoice->setInvoiceNumber($invoiceNumber);
        $content = $this->replacePlaceholders($invoice);

        return $this->filesHelper->makeInvoiceFile($invoice, $content);
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
        $command = $this->prepareCommand($filename);
        shell_exec($command);

        $this->filesHelper->moveToOutput($filename, $this->dataManager->getOutput());
    }

    private function prepareCommand(string $filename): string
    {
        return sprintf(
            self::TEX_COMMAND,
            FilesHelper::TEMP_PATH,
            FilesHelper::TEMP_PATH . $filename
        );
    }
}

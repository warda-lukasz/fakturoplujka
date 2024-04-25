<?php

namespace Utils;

use Models\Customer;
use Models\Invoice;
use Models\Seller;
use RecursiveDirectoryIterator;

class DataManager
{
    private array $invoices;
    private array $files;

    public function __construct()
    {
        $this->prepareConfigDirStructure();
        $this->prepareInvoices();
    }

    private function prepareConfigDirStructure(): void
    {
        $directoryIterator = FilesHelper::getDirectoryIterator(FilesHelper::INVOICES_DIR);

        /** @var RecursiveDirectoryIterator $file */
        foreach ($directoryIterator as $file) {
            if ($file->isDir()) continue;
            $dirArr = explode('/', $file->getPath());
            $lastFolder = array_pop($dirArr);
            $modelType = (explode('.', $file->getFilename()))[0];

            $this->files[$lastFolder][$modelType] = $file->getPath() . '/' . $file->getFilename();
            ksort($this->files);
        }
    }

    private function prepareInvoices(): void
    {
        foreach ($this->files as $invoiceName => $invoiceData) {
            $invoice = (new Invoice($invoiceData['invoice']))
                ->setInvoiceName($invoiceName)
                ->setSeller(new Seller(FilesHelper::SELLER_PATH))
                ->setCustomer(new Customer($invoiceData['customer']));

            $this->invoices[] = $invoice;
        }
    }

    public function getInvoices(): array
    {
        return $this->invoices;
    }
}

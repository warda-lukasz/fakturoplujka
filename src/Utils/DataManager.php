<?php

namespace Utils;

use Model\Customer;
use Model\Invoice;
use Model\Seller;
use RecursiveDirectoryIterator;

class DataManager
{
    private Seller $seller;
    private array $invoices;
    private array $files;

    public function __construct()
    {
        $this->files = $this->prepareConfigDirStructure();
        $this->seller = new Seller(FilesHelper::SELLER_PATH);
        $this->prepareInvoices();
    }

    private function prepareConfigDirStructure(): array
    {
        $directoryIterator = FilesHelper::getDirectoryIterator(FilesHelper::INVOICES_DIR);
        $files = [];

        /** @var RecursiveDirectoryIterator $file */
        foreach ($directoryIterator as $file) {
            if ($file->isDir()) continue;
            $dirArr = explode('/', $file->getPath());
            $lastFolder = array_pop($dirArr);
            $modelType = (explode('.', $file->getFilename()))[0];

            $files[$lastFolder][$modelType] = $file->getPath() . '/' . $file->getFilename();
        }

        return $files;
    }

    private function prepareInvoices(): void
    {
        foreach ($this->files as $invoiceName => $invoiceData) {
            $customer = (new Customer())
                ->setFromFile($invoiceData['customer']);

            $invoice = (new Invoice($invoiceData['invoice']))
                ->setInvoiceName($invoiceName)
                ->setSeller($this->seller)
                ->setCustomer($customer);

            $this->invoices[] = $invoice;
        }
    }

    public function getInvoices(): array
    {
        return $this->invoices;
    }
}

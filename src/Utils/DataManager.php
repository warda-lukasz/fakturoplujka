<?php

namespace Utils;

require('src/Model/Seller.php');
require('src/Model/Invoice.php');
require('src/Model/Customer.php');

use FilesystemIterator;
use Model\Customer;
use Model\Invoice;
use Model\Seller;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DataManager
{
    const SELLER_PATH = 'config/seller.json';
    const INVOICES_DIR = 'config/invoices';

    private $seller;

    private $invoices;

    private $files;

    public function __construct()
    {
        $this->files = $this->prepareConfigDirStructure();
        $this->seller = new Seller(self::SELLER_PATH);
        $this->prepareInvoices();
    }

    private function prepareConfigDirStructure(): array
    {
        $rri = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(self::INVOICES_DIR, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        $files = [];

        /** @var RecursiveDirectoryIterator $file */
        foreach ($rri as $file) {
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
            $customer = new Customer();
            $customer->setFromFile($invoiceData['customer']);
            $invoice = new Invoice($invoiceData['invoice']);

            $invoice->setInvoiceName($invoiceName)
                ->setFromFile($invoiceData['invoice'])
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

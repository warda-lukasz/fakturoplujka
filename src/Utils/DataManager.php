<?php

namespace Utils;

use Models\Customer;
use Models\Invoice;
use Models\Seller;
use RecursiveDirectoryIterator;
use Symfony\Component\Yaml\Yaml;
use Symfony\Componenet\VarDumper\VarDumper;

class DataManager
{
    private array $invoices;
    private string $output;

    public function __construct()
    {
        $this->parseData();
    }

    private function parseData(): void
    {
        $config = Yaml::parseFile(FilesHelper::CONFIG_PATH . 'config.yml');
        $this->output = $config['outputDir'];

        $seller = new Seller($config['seller']);
        $invoiceNumber = 1;

        foreach ($config['customers'] as $customerData) {
            $customer = new Customer($customerData);

            foreach ($customerData['invoices'] as $invoiceData) {
                $invoice = new Invoice($invoiceData)
                    ->setInvoiceName($seller->invoiceTitlePrefix . $invoiceNumber)
                    ->setInvoiceNumber($invoiceNumber)
                    ->setSeller($seller)
                    ->setCustomer($customer);
                $this->invoices[] = $invoice;
                $invoiceNumber++;
            }
        }
    }

    public function getInvoices(): array
    {
        return $this->invoices;
    }

    public function getOutput(): string
    {
        return $this->output;
    }
}

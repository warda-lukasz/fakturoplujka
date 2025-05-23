<?php

namespace Utils;

use Models\Customer;
use Models\Invoice;
use Models\Seller;
use Symfony\Component\Yaml\Yaml;

class DataManager
{
    private bool $renderInactive;
    private int $startNumber;
    private array $invoices;
    private string $output;

    public function __construct(bool $renderInactive = false, int $startNumber = 1)
    {
        $this->renderInactive = $renderInactive;
        $this->startNumber = $startNumber;
        $this->parseData();
    }

    private function parseData(): void
    {
        $config = Yaml::parseFile(FilesHelper::CONFIG_PATH . 'config.yaml');
        $this->output = $config['outputDir'];

        $seller = new Seller($config['seller']);
        $invoiceNumber = max(1, $this->startNumber);

        foreach ($config['customers'] as $customerData) {
            $customer = new Customer($customerData);

            foreach ($customerData['invoices'] as $invoiceData) {
                if (!$this->renderInactive && !$invoiceData['active']) {
                    continue;
                }

                $invoice = (new Invoice($invoiceData))
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

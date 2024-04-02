<?php

namespace Model;

use NumberFormatter;
use Utils\Formatter;

class Invoice extends AbstractModel
{
    protected $invoiceName;
    protected $product;
    protected $net;
    protected $vatRate;
    protected $payment;
    protected $daysToPayment;
    protected $issueDate;
    protected $paymentDate;
    protected $saleDate;
    protected $vat;
    protected $vatRatePercentage;
    protected $gross;
    protected $formatter;
    protected $currencyNet;
    protected $currencyVat;
    protected $currencyGross;
    protected $currencySpellout;
    protected $seller;
    protected $customer;
    protected $invoiceNumber;
    protected $issueOnLastDay;
    protected $saleDateFromConfig;

    public function __construct($path)
    {
        $this->formatter = new Formatter();
        $this
            ->setFromFile($path)
            ->setVatAndGross()
            ->setStrings()
            ->setDates();
    }

    private function setDates(): self
    {
        if ($this->issueOnLastDay === true) {
            $issueDate = date('Y-m-t', strtotime('now'));
            $saleDate = $issueDate;
        } else {
            $issueDate = date('Y-m-d', strtotime('now'));
            $saleDate = date('Y-m-d', strtotime($this->saleDateFromConfig));
        }

        $this
            ->setIssueDate($issueDate)
            ->setSaleDate($saleDate);

        $paymentDate = date(
            'd-m-Y', strtotime(
                $this->daysToPayment,
                strtotime($this->issueDate)
            )
        );

        $this->setPaymentDate($paymentDate);

        return $this;
    }

    public function setSaleDate(string $saleDate): self
    {
        $this->saleDate = $saleDate;

        return $this;
    }

    public function setIssueDate(string $issueDate): self
    {
        $this->issueDate = $issueDate;

        return $this;
    }

    public function setPaymentDate(string $paymentDate): self
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    private function setStrings(): self
    {
        $this
            ->setCurrencyGross($this->formatter->getFormattedNumber($this->gross))
            ->setCurrencyNet($this->formatter->getFormattedNumber($this->net))
            ->setCurrencyVat($this->formatter->getFormattedNumber($this->vat))
            ->setCurrencySpellout($this->formatter->getFormattedNumber($this->gross, NumberFormatter::SPELLOUT))
            ->setVatRatePercentage($this->formatter->getFormattedNumber($this->vatRate, NumberFormatter::PERCENT));

        return $this;
    }

    public function setVatRatePercentage(string $vatRatePercentage): self
    {
        $this->vatRatePercentage = $vatRatePercentage;

        return $this;
    }

    public function setCurrencySpellout(string $currencySpellout): self
    {
        $this->currencySpellout = $currencySpellout;

        return $this;
    }

    public function setCurrencyVat(string $currencyVat): self
    {
        $this->currencyVat = $currencyVat;

        return $this;
    }

    public function setCurrencyNet(string $currencyNet): self
    {
        $this->currencyNet = $currencyNet;

        return $this;
    }

    public function setCurrencyGross(string $currencyGross): self
    {
        $this->currencyGross = $currencyGross;

        return $this;
    }

    private function setVatAndGross(): self
    {
        $vat = round($this->net * $this->vatRate, 2);
        $this->setVat($vat)->setGross(($this->net) + $vat);

        return $this;
    }

    public function setGross(float $gross): self
    {
        $this->gross = $gross;

        return $this;
    }

    public function setVat(float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getSeller(): Seller
    {
        return $this->seller;
    }

    public function setSeller(Seller $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function setInvoiceName($invoiceName): self
    {
        $this->invoiceName = $invoiceName;

        return $this;
    }

    public function setInvoiceNumber(string $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }
}

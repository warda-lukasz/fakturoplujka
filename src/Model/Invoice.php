<?php

namespace Model;

use NumberFormatter;
use Utils\Formatter;

class Invoice extends AbstractModel
{
    protected string $invoiceName;
    protected string $product;
    protected float $net;
    protected float $vatRate;
    protected string $payment;
    protected string $daysToPayment;
    protected string $issueDate;
    protected string $paymentDate;
    protected string $saleDate;
    protected float $vat;
    protected string $vatRatePercentage;
    protected float $gross;
    protected string $currencyNet;
    protected string $currencyVat;
    protected string $currencyGross;
    protected string $currencySpellout;
    protected string $invoiceNumber;
    protected bool $issueOnLastDay;
    protected string $saleDateFromConfig;
    protected Formatter $formatter;
    protected Seller $seller;
    protected Customer $customer;

    public function __construct(string $path)
    {
        parent::__construct($path);

        $this->formatter = new Formatter();
        $this->setVatAndGross()
            ->setStrings()
            ->setDates();
    }

    private function setDates(): self
    {
        [$issueDate, $saleDate, $paymentDate] = $this->prepareDates();

        $this
            ->setIssueDate($issueDate)
            ->setSaleDate($saleDate)
            ->setPaymentDate($paymentDate);

        return $this;
    }

    private function prepareDates(): array
    {
        if ($this->issueOnLastDay === true) {
            $issueDate = date('Y-m-t', strtotime('now'));
            $saleDate = $issueDate;
        } else {
            $issueDate = date('Y-m-d', strtotime('now'));
            $saleDate = date('Y-m-d', strtotime($this->saleDateFromConfig));
        }

        $paymentDate = date(
            'd-m-Y', strtotime(
                $this->daysToPayment,
                strtotime($issueDate)
            )
        );

        return [$issueDate, $saleDate, $paymentDate];
    }

    public function setPaymentDate(string $paymentDate): self
    {
        $this->paymentDate = $paymentDate;

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

    private function setStrings(): self
    {
        $this
            ->setCurrencyGross($this->formatter->getFormattedNumber($this->gross))
            ->setCurrencyNet($this->formatter->getFormattedNumber($this->net))
            ->setCurrencyVat($this->formatter->getFormattedNumber($this->vat))
            ->setCurrencySpellout(
                $this->formatter->getFormattedNumber($this->gross, NumberFormatter::SPELLOUT)
            )
            ->setVatRatePercentage(
                $this->formatter->getFormattedNumber($this->vatRate, NumberFormatter::PERCENT)
            );

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
        $this->setVat($vat)->setGross($this->net + $vat);

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

    public function setInvoiceName(string $invoiceName): self
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

<?php

namespace Model;

require_once('src/Utils/Formatter.php');
require_once('src/Model/AbstractModel.php');

use NumberFormatter;
use Utils\Formatter;

class Invoice extends AbstractModel
{
    /**
     * @var string
     */
    protected $invoiceName;

    /**
     * @var string
     */
    protected $product;

    /**
     * @var string
     */
    protected $net;

    /**
     * @var float
     */
    protected $vatRate;

    /**
     * @var string
     */
    protected $payment;

    /**
     * @var string
     */
    protected $daysToPayment;

    /**
     * @var string
     */
    protected $issueDate;

    /**
     * @var string
     */
    protected $paymentDate;

    /**
     * @var string
     */
    protected $saleDate;

    /**
     * @var int
     */
    protected $vat;

    /**
     * @var string
     */
    protected $vatRatePercentage;

    /**
     * @var float
     */
    protected $gross;

    /**
     * @var Formatter
     */
    protected $formatter;

    /**
     * @var string
     */
    protected $currencyNet;

    /**
     * @var string
     */
    protected $currencyVat;

    /**
     * @var string
     */
    protected $currencyGross;

    /**
     * @var string
     */
    protected $currencySpellout;

    /**
     * @var Seller
     */
    protected $seller;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var string
     */
    protected $invoiceNumber;

    public function __construct($path)
    {
        $this->formatter = new Formatter();
        $this
            ->setFromFile($path)
            ->setVatAndGross()
            ->setStrings()
            ->setDates();
    }

    /**
     * @return $this
     */
    private function setDates(): self
    {
        $issueDate = date('Y-m-t', strtotime('now'));
        $this
            ->setIssueDate($issueDate)
            ->setSaleDate($issueDate);

        $paymentDate = date(
            'd-m-Y', strtotime($this->daysToPayment,
                strtotime($this->issueDate))
        );

        $this->setPaymentDate($paymentDate);

        return $this;
    }

    /**
     * @return $this
     */
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

    /**
     * @return $this
     */
    private function setVatAndGross(): self
    {
        $vat = round($this->net * $this->vatRate, 2);
        $this->setVat($vat)->setGross(($this->net) + $vat);

        return $this;
    }

    /**
     * @return Seller
     */
    public function getSeller(): Seller
    {
        return $this->seller;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param string $vatRatePercentage
     * @return Invoice
     */
    public function setVatRatePercentage(string $vatRatePercentage): Invoice
    {
        $this->vatRatePercentage = $vatRatePercentage;
        return $this;
    }

    /**
     * @param string $currencyNet
     * @return Invoice
     */
    public function setCurrencyNet(string $currencyNet): Invoice
    {
        $this->currencyNet = $currencyNet;
        return $this;
    }

    /**
     * @param mixed $currencyVat
     * @return Invoice
     */
    public function setCurrencyVat($currencyVat): Invoice
    {
        $this->currencyVat = $currencyVat;
        return $this;
    }

    /**
     * @param string $currencyGross
     * @return Invoice
     */
    public function setCurrencyGross(string $currencyGross): Invoice
    {
        $this->currencyGross = $currencyGross;
        return $this;
    }

    /**
     * @param string $currencySpellout
     * @return Invoice
     */
    public function setCurrencySpellout(string $currencySpellout): Invoice
    {
        $this->currencySpellout = $currencySpellout;
        return $this;
    }

    /**
     * @param Seller $seller
     * @return Invoice
     */
    public function setSeller(Seller $seller): Invoice
    {
        $this->seller = $seller;
        return $this;
    }

    /**
     * @param Customer $customer
     * @return Invoice
     */
    public function setCustomer(Customer $customer): Invoice
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @param int $vat
     * @return Invoice
     */
    public function setVat(int $vat): Invoice
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * @param float $gross
     * @return Invoice
     */
    public function setGross(float $gross): Invoice
    {
        $this->gross = $gross;
        return $this;
    }

    /**
     * @param string $issueDate
     * @return Invoice
     */
    public function setIssueDate(string $issueDate): Invoice
    {
        $this->issueDate = $issueDate;
        return $this;
    }

    /**
     * @param string $paymentDate
     * @return Invoice
     */
    public function setPaymentDate(string $paymentDate): Invoice
    {
        $this->paymentDate = $paymentDate;
        return $this;
    }

    /**
     * @param string $saleDate
     * @return Invoice
     */
    public function setSaleDate(string $saleDate): Invoice
    {
        $this->saleDate = $saleDate;
        return $this;
    }

    /**
     * @param mixed $invoiceName
     * @return Invoice
     */
    public function setInvoiceName($invoiceName): Invoice
    {
        $this->invoiceName = $invoiceName;
        return $this;
    }

    /**
     * @param string $invoiceNumber
     * @return Invoice
     */
    public function setInvoiceNumber(string $invoiceNumber): Invoice
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }
}
<?php

namespace Model;

class Seller extends AbstractModel
{
    protected string $sellerCompanyName;
    protected string $sellerFullName;
    protected string $sellerAddress;
    protected string $sellerPostalCode;
    protected string $sellerCity;
    protected string $sellerNip;
    protected string $sellerRegon;
    protected string $sellerAccount;
    protected string $sellerBank;

    public function __construct(string $sellerPath)
    {
        $this->setFromFile($sellerPath);
    }

    public function getSellerCompanyName(): string
    {
        return $this->sellerCompanyName;
    }
}

<?php

namespace Model;

require_once('src/Model/AbstractModel.php');

class Seller extends AbstractModel
{
    protected $sellerCompanyName;
    protected $sellerFullName;
    protected $sellerAddress;
    protected $sellerPostalCode;
    protected $sellerCity;
    protected $sellerNip;
    protected $sellerRegon;
    protected $sellerAccount;
    protected $sellerBank;

    public function __construct(string $sellerPath)
    {
        $this->setFromFile($sellerPath);
    }

    public function getSellerCompanyName(): string
    {
        return $this->sellerCompanyName;
    }
}

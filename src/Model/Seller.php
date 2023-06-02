<?php

namespace Model;

require_once('src/Model/AbstractModel.php');

class Seller extends AbstractModel
{
    /**
     * @var string
     */
    protected $sellerCompanyName;

    /**
     * @var string
     */
    protected $sellerFullName;

    /**
     * @var string
     */
    protected $sellerAddress;

    /**
     * @var string
     */
    protected $sellerPostalCode;

    /**
     * @var string
     */
    protected $sellerCity;

    /**
     * @var string
     */
    protected $sellerNip;

    /**
     * @var string
     */
    protected $sellerRegon;

    /**
     * @var string
     */
    protected $sellerAccount;

    /**
     * @var string
     */
    protected $sellerBank;

    /**
     * @param string $sellerPath
     */
    public function __construct(string $sellerPath)
    {
        $this->setFromFile($sellerPath);
    }

    /**
     * @return string
     */
    public function getSellerCompanyName(): string
    {
        return $this->sellerCompanyName;
    }
}

<?php

namespace Model;

require_once('src/Model/AbstractModel.php');

class Customer extends AbstractModel
{
    /**
     * @var string
     */
    protected $customerCompanyName;

    /**
     * @var string
     */
    protected $customerAddress;

    /**
     * @var string
     */
    protected $customerPostalCode;

    /**
     * @var string
     */
    protected $customerCity;

    /**
     * @var string
     */
    protected $customerNip;

    /**
     * @return string
     */
    public function getCustomerCompanyName(): string
    {
        return $this->customerCompanyName;
    }
}
<?php

namespace Model;

class Customer extends AbstractModel
{
    protected $customerCompanyName;
    protected $customerAddress;
    protected $customerPostalCode;
    protected $customerCity;
    protected $customerNip;

    public function getCustomerCompanyName(): string
    {
        return $this->customerCompanyName;
    }
}

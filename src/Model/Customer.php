<?php

namespace Model;

class Customer extends AbstractModel
{
    protected string $customerCompanyName;
    protected string $customerAddress;
    protected string $customerPostalCode;
    protected string $customerCity;
    protected string $customerNip;

    public function getCustomerCompanyName(): string
    {
        return $this->customerCompanyName;
    }
}

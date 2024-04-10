<?php

namespace Models;

use Interfaces\ModelInterface;

class BusinessModel extends AbstractModel implements ModelInterface
{
    protected string $companyName;
    protected string $address;
    protected string $postalCode;
    protected string $city;
    protected string $nip;

    public function getCompanyName(): string
    {
        return $this->companyName;
    }
}

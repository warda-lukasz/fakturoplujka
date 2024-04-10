<?php

namespace Models;

use Interfaces\ModelInterface;

class Seller extends BusinessModel implements ModelInterface
{
    protected const string MODEL_PREFIX = 'seller';
    protected string $fullName;
    protected string $regon;
    protected string $account;
    protected string $bank;
}

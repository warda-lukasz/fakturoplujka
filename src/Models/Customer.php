<?php

namespace Models;

use Interfaces\ModelInterface;

class Customer extends BusinessModel implements ModelInterface
{
    protected const string MODEL_PREFIX = 'customer';
}

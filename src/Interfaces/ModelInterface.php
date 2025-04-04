<?php

namespace Interfaces;

interface ModelInterface
{
    public function setFromArray(array $arr): self;

    public function __get(string $property);

    public function __set(string $property, mixed $value): void;

    public function getPatternsAndReplacements(): array;
}

<?php

namespace Model;

interface ModelInterface {
    public function setFromFile(string $path): self;
    public function __set(string $property, $value): void;
    public function __get(string $property);
    public function getPatternsAndReplacements(): array;
} 

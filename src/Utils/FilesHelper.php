<?php

namespace Utils;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FilesHelper
{
    public const string SELLER_PATH = 'config/seller.json';
    public const string INVOICES_DIR = 'config/invoices';
    public const string TEX_TEMPLATE = 'template/template.tex';
    public const string OUTPUT_BASE_PATH = 'output/';
    public const string TEMP_PATH = 'temp/';

    public static function getDirectoryIterator(string $dir)
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $dir,
                FilesystemIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::CHILD_FIRST
        );
    }
}

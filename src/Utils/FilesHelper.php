<?php

namespace Utils;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class FilesHelper
{
    public const string SELLER_PATH = 'config/seller.json';
    public const string INVOICES_DIR = 'config/invoices';
    public const string TEX_TEMPLATE = 'template/template.tex';
    public const string OUTPUT_BASE_PATH = 'output/';
    public const string TEMP_PATH = 'temp/';

    private const string GIT_KEEP = '.gitkeep';

    public static function cleanDir(string $dir): void
    {
        if (file_exists($dir)) {
            $ri = self::getDirectoryIterator($dir);

            /** @var SplFileInfo $file */
            foreach ($ri as $file) {
                if ($file->getFilename() === self::GIT_KEEP) continue;
                $file->isDir() ? rmdir($file) : unlink($file);
            }
        }
    }

    public static function getDirectoryIterator(string $dir): RecursiveIteratorIterator
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

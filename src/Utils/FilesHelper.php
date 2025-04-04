<?php

namespace Utils;

use FilesystemIterator;
use Models\Invoice;
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
    public const string CONFIG_PATH = 'config/';
    private const string PDF_TYPE = '.pdf';
    private const string TEX_TYPE = '.tex';
    private const string GIT_KEEP = '.gitkeep';

    public function cleanDir(string $dir): void
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

    private static function getDirectoryIterator(string $dir): RecursiveIteratorIterator
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $dir,
                FilesystemIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::CHILD_FIRST
        );
    }

    private function expandTilde(string $path): string
    {
        if (strpos($path, '~') === 0) {
            $path = getenv('HOME') . substr($path, 1);
        }
        return $path;
    }

    public function moveToOutput(string $filename, string $outputDir): void
    {
        $outputDir = $this->expandTilde($outputDir);

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        rename(
            FilesHelper::TEMP_PATH . $filename . self::PDF_TYPE,
            $outputDir . pathinfo(
                FilesHelper::TEMP_PATH . $filename . self::PDF_TYPE,
                PATHINFO_BASENAME
            )
        );
    }

    public function makeInvoiceFile(Invoice $invoice, string $content): string
    {
        $filename = sprintf(
            '%s%s',
            $invoice->getSeller()->invoiceTitlePrefix,
            str_replace('/', '_', $invoice->invoiceNumber)
        );

        file_put_contents(
            FilesHelper::TEMP_PATH . $filename . self::TEX_TYPE,
            $content
        );

        return $filename;
    }
}

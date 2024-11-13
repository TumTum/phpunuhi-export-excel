<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Exchange;

use PHPUnuhi\Bundles\Exchange\ExchangeInterface;
use PHPUnuhi\Bundles\Exchange\ImportResult;
use PHPUnuhi\Models\Translation\TranslationSet;

class ExcelExchange implements ExchangeInterface
{
    public function getName(): string
    {
        return 'excel';
    }

    public function getOptions(): array
    {
        return [];
    }

    public function setOptionValues(array $options): void
    {
        // TODO: Implement setOptionValues() method.
    }

    public function export(TranslationSet $set, string $outputDir, bool $onlyEmpty): void
    {
        // TODO: Implement export() method.
    }

    public function import(string $filename): ImportResult
    {
        return new ImportResult([]);
    }
}

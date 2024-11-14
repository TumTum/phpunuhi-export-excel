<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Exchange;

use PHPUnuhi\Bundles\Exchange\ExchangeInterface;
use PHPUnuhi\Bundles\Exchange\ImportResult;
use PHPUnuhi\Models\Command\CommandOption;
use PHPUnuhi\Models\Translation\TranslationSet;
use Tumtum\PhpunuhiExportExcel\Exchange\Services\ExcelWriter;
use Tumtum\PhpunuhiExportExcel\Exchange\Services\SkipSet;

class ExcelExchange implements ExchangeInterface
{
    private ExcelWriter $excelExporter;

    private SkipSet $skipSet;

    public function getName(): string
    {
        return 'excel';
    }

    public function getOptions(): array
    {
        return [
            'test' => new CommandOption('excel-skip-sets', true),
        ];
    }

    public function setOptionValues(array $options): void
    {
        $this->skipSet = new SkipSet($options['excel-skip-sets'] ?? '');
    }

    public function export(TranslationSet $set, string $outputDir, bool $onlyEmpty): void
    {
        if ($this->skipSet->byName($set->getName())) {
            return;
        }

        if (!isset($this->excelExporter)) {
            $this->excelExporter = new ExcelWriter($outputDir, $onlyEmpty);
        }
        $this->excelExporter->export($set);
    }

    public function import(string $filename): ImportResult
    {
        return new ImportResult([]);
    }

    public function __destruct()
    {
        if (isset($this->excelExporter)) {
            $this->excelExporter->close();
        }
    }
}

<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Exchange;

use PHPUnuhi\Bundles\Exchange\ExchangeInterface;
use PHPUnuhi\Bundles\Exchange\ImportResult;
use PHPUnuhi\Models\Command\CommandOption;
use PHPUnuhi\Models\Translation\TranslationSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tumtum\PhpunuhiExportExcel\Exchange\Services\ExcelReader;
use Tumtum\PhpunuhiExportExcel\Exchange\Services\ExcelWriter;
use Tumtum\PhpunuhiExportExcel\Exchange\Services\SkipSet;

class ExcelExchange implements ExchangeInterface
{
    private ExcelWriter $excelExporter;

    private SkipSet $skipSet;

    private string $selectedSet;

    public function getName(): string
    {
        return 'excel';
    }

    public function getOptions(): array
    {
        return [
            new CommandOption('excel-skip-sets', true),
        ];
    }

    public function setOptionValues(array $options): void
    {
        $this->skipSet = new SkipSet($options['excel-skip-sets'] ?? '');
        $this->selectedSet = $options['set'] ?? '';
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
        $io = new SymfonyStyle(new ArrayInput([]), new ConsoleOutput());
        return (new ExcelReader($io))->read($filename, $this->selectedSet);
    }

    public function __destruct()
    {
        if (isset($this->excelExporter)) {
            $this->excelExporter->close();
        }
    }
}

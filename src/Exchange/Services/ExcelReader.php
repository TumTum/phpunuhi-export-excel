<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Exchange\Services;

use DateInterval;
use DateTimeInterface;
use Exception;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\XLSX\Options as XLSXOptions;
use OpenSpout\Reader\XLSX\Reader as XLSXReader;
use OpenSpout\Reader\XLSX\Sheet;
use PHPUnuhi\Bundles\Exchange\ImportEntry;
use PHPUnuhi\Bundles\Exchange\ImportResult;
use PHPUnuhi\Services\GroupName\GroupNameService;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tumtum\PhpunuhiExportExcel\Exchange\Strings\CellValue;

class ExcelReader
{


    public function __construct(
        private SymfonyStyle $io,
    ) {
    }

    public function read(string $filePath, string $setId): ImportResult
    {
        if (pathinfo($filePath, PATHINFO_EXTENSION) !== 'xlsx') {
            throw new Exception("File path '{$filePath}' not valid. It must be .xlsx");
        }

        $options = new XLSXOptions();
        $xlsxReader = new XLSXReader($options);
        $xlsxReader->open($filePath);

        $xlsxReader->getSheetIterator();

        $this->io->section(sprintf("This sheet are available in %s:", basename($filePath)));

        $selectedSheet = null;

        /** @var Sheet $sheet */
        foreach ($xlsxReader->getSheetIterator() as $sheet) {
            $checkBox = '[ ]';

            if (str_contains($setId, $sheet->getName())) {
                $selectedSheet = $sheet;
                $checkBox = '[*]';
            }

            $this->io->writeln("{$checkBox} <comment>Sheet:</comment> {$sheet->getName()}");
        };

        if ($selectedSheet === null) {
            throw new Exception("There is no sheet available: {$setId}");
        }

        $entries = [];
        $head = null;

        $groupNameService = new GroupNameService();
        $cellValue = new CellValue();


        /** @var Row $row */
        foreach ($selectedSheet->getRowIterator() as $row) {
            $rowContent = array_map(fn (Cell $cell) => $this->transformValue($cell->getValue()), $row->getCells());

            if ($head === null) {
                $head = $rowContent;
                continue;
            }

            $combine = array_combine($head, $rowContent);

            $entriesKey = null;
            $entriesGroupName = null;

            foreach ($combine as $column => $value) {
                if (strtolower($column) === 'key') {
                    $entriesKey = $groupNameService->getPropertyName($value);
                    continue;
                }
                if (strtolower($column) === 'group') {
                    $entriesGroupName = $groupNameService->getGroupID($value);
                    continue;
                }

                if ($entriesKey !== null && $entriesGroupName !== null) {
                    $entries[] = new ImportEntry(
                        $column,
                        $entriesKey,
                        $entriesGroupName,
                        $cellValue->deprotect($value)
                    );
                }
            }
        }

        return new ImportResult($entries);
    }

    private function transformValue(DateInterval|float|DateTimeInterface|bool|int|string|null $value): string
    {
        return match (true) {
            $value instanceof DateInterval => $value->format('Y-m-d'),
            $value instanceof DateTimeInterface => $value->format('Y-m-d H:i:s'),
            is_float($value) => (string)$value,
            is_int($value) => (string)$value,
            is_bool($value) => $value ? 'true' : 'false',
            is_null($value) => '',
            is_scalar($value) => $value,
        };
    }
}

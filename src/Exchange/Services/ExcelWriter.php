<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Exchange\Services;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Entity\SheetView;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer;
use PHPUnuhi\Models\Translation\TranslationSet;
use PHPUnuhi\Services\GroupName\GroupNameService;
use Tumtum\PhpunuhiExportExcel\Exchange\Strings\CellValue;

class ExcelWriter
{
    private const LIGHT_GREEN = 'DFF1D3';
    private const LIGHT_GREEN_PLUS = 'F2F8ED';
    private const GREEN = '9FD77F';
    private bool $onlyEmpty;

    private int $numberSheet = 0;

    private Writer $excelWriter;

    public function __construct(string $outputDir, bool $onlyEmpty)
    {
        $date = date("ymd");
        $filename = $outputDir . DIRECTORY_SEPARATOR . "TranslationProject_{$date}.xlsx";

        $options = new Options();
        $options->DEFAULT_COLUMN_WIDTH = 40;
        $options->DEFAULT_ROW_HEIGHT = 20;
        $options->setColumnWidth(70, 1);
        $options->setColumnWidth(20, 2);

        $this->excelWriter = (new Writer($options));
        $this->excelWriter->openToFile($filename);

        $this->onlyEmpty = $onlyEmpty;
    }

    public function export(TranslationSet $set): void
    {
        $sheetName = substr($set->getName(), -31);

        $groupNameService = new GroupNameService();
        $cellValue = new CellValue();

        if ($this->numberSheet++ !== 0) {
            $this->excelWriter->addNewSheetAndMakeItCurrent();
        }

        $sheet = $this->excelWriter->getCurrentSheet();
        $sheet->setName($sheetName);
        $sheet->setSheetView($this->createSheetView());

        if ($sheet->getWrittenRowCount() === 0) {
            $headTitles = ['Key', 'Group'];
            foreach ($set->getLocales() as $locale) {
                $headTitles[] = $locale->getName();
            }
            $this->excelWriter->addRow(Row::fromValuesWithStyles($headTitles, $this->createHeaderStyle()));
        }

        $style = (new Style())
            ->setShouldWrapText(false);

        $styleB = (clone $style)
            ->setBorder($this->createBorderStyle())
            ->setBackgroundColor(self::LIGHT_GREEN_PLUS);

        foreach ($set->getAllTranslationIDs() as $id) {
            $entry = [];

            foreach ($set->getLocales() as $locale) {
                $translation = $locale->findTranslation($id);

                if ($this->onlyEmpty && !empty($translation->getValue())) {
                    continue;
                }

                $entry['Key'] = $translation->getKey();
                $entry['Group'] = $groupNameService->getPropertyName($translation->getGroup());
                $entry[$locale->getName()] = $cellValue->protect($translation->getValue());
            }

            $currentStyle = $sheet->getWrittenRowCount() % 2 == 0 ? $style : $styleB;
            $this->excelWriter->addRow(Row::fromValuesWithStyles($entry, $currentStyle));
        }
    }

    private function createHeaderStyle(): Style
    {
        return (new Style())
            ->setFontBold()
            ->setFontColor(Color::BLACK)
            ->setCellAlignment(CellAlignment::CENTER)
            ->setShouldWrapText(false)
            ->setBackgroundColor(self::LIGHT_GREEN)
            ->setBorder($this->createBorderStyle());
    }

    private function createBorderStyle(): Border
    {
        return new Border(
            new BorderPart(Border::BOTTOM, self::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::TOP, self::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID)
        );
    }

    private function createSheetView(): SheetView
    {
        return (new SheetView())
            ->setFreezeRow(2)
            ->setFreezeColumn('D')
        ;
    }

    public function close(): void
    {
        if (isset($this->excelWriter)) {
            $this->excelWriter->close();
        }
    }
}

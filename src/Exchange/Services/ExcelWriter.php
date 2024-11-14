<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Exchange\Services;

use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Entity\SheetView;
use OpenSpout\Writer\XLSX\Writer;
use PHPUnuhi\Models\Translation\TranslationSet;
use PHPUnuhi\Services\GroupName\GroupNameService;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Tumtum\PhpunuhiExportExcel\Exchange\Strings\CellValue;

class ExcelWriter
{
    private const LIGHT_GREEN = 'DFF1D3';
    private const LIGHT_GREEN_PLUS = 'F2F8ED';
    private const GREEN = '9FD77F';
    private bool $onlyEmpty;

    /**
     * @var string[]
     */
    private array $sets = [];

    private SimpleExcelWriter $excelWriter;

    public function __construct(string $outputDir, bool $onlyEmpty)
    {
        $date = date("ymd");
        $filename = $outputDir . DIRECTORY_SEPARATOR . "TranslationProject_{$date}.xlsx";

        $this->excelWriter = SimpleExcelWriter::create(
            $filename,
            '',
            function (Writer $writer): void {
                $options = $writer->getOptions();
                $options->DEFAULT_COLUMN_WIDTH = 40; // set default width
                $options->DEFAULT_ROW_HEIGHT = 20; // set default width
                $options->setColumnWidth(70, 1);
                $options->setColumnWidth(20, 2);
            }
        )->setHeaderStyle($this->createHeaderStyle());

        $this->onlyEmpty = $onlyEmpty;
    }

    public function export(TranslationSet $set): void
    {
        $cheatname = substr($set->getName(), -31);

        if ($this->sets !== []) {
            $this->excelWriter->addNewSheetAndMakeItCurrent();
        }

        $this->sets[] = $cheatname;
        $this->excelWriter->nameCurrentSheet($cheatname);
        $this->getWriter()?->getCurrentSheet()->setSheetView($this->createSheetView());

        $groupNameService = new GroupNameService();
        $cellValue = new CellValue();

        $style = (new Style())
            ->setShouldWrapText(false);

        $styleB = (clone $style)
            ->setBorder($this->createBorderStyle())
            ->setBackgroundColor(self::LIGHT_GREEN_PLUS);

        $index = 0;

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

            $currentStyle = $index++ % 2 == 0 ? $style : $styleB;
            $this->excelWriter->addRow($entry, $currentStyle);
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

    private function getWriter(): ?Writer
    {
        if ($this->excelWriter->getWriter() instanceof Writer) {
            return $this->excelWriter->getWriter();
        }

        return null;
    }
}

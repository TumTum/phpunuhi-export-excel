<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Exchange\Services;

use PHPUnit\Framework\TestCase;
use PHPUnuhi\Models\Translation\TranslationSet;

/**
 * @group Services
 */
class ExcelExporterTest extends TestCase
{
    /**
     * Test export functionality of ExcelExporter
     */
    public function testExport(): void
    {
        //Assert
        $exporter = new ExcelExporter(__DIR__ . '/../../../playground/', false);

        $set = $this->getMockBuilder(TranslationSet::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $set->expects($this->once())
            ->method('getName')
            ->willReturn('SheetName');

        //Act
        $exporter->export($set);
        $exporter->close();

        //Assert
        $date = date("ymd");
        $expectFilename = "TranslationProject_{$date}.xlsx";

        $this->assertFileExists(__DIR__ . '/../../../playground/' . $expectFilename);
    }

    protected function tearDown(): void
    {
        exec('rm ' . __DIR__ . '/../../../playground/TranslationProject_*.xlsx');
    }

}

<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Tests\Exchange\Services;

use PHPUnit\Framework\TestCase;
use PHPUnuhi\Models\Translation\TranslationSet;
use Tumtum\PhpunuhiExportExcel\Exchange\Services\ExcelWriter;

/**
 * @group Services
 */
class ExcelWriterTest extends TestCase
{
    /**
     * Test export functionality of ExcelWriter
     */
    public function testExport(): void
    {
        //Assert
        $exporter = new ExcelWriter(__DIR__ . '/../../../playground/', false);

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

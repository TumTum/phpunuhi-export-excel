<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Tests\Exchange\Services;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tumtum\PhpunuhiExportExcel\Exchange\Services\ExcelReader;

class ExcelReaderTest extends TestCase
{
    private ExcelReader $excelReader;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * Setup test case
     */
    protected function setUp(): void
    {
        $this->io = $this->createMock(SymfonyStyle::class);
        $this->excelReader = new ExcelReader($this->io);
    }

    /**
     * Test that the read() method throws an exception if the file is not valid.
     */
    public function testReadThrowsExceptionIfFileIsNotValid(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("File path 'invalid_file_path' not valid. It must be .xlsx");
        $this->excelReader->read('invalid_file_path', 'setId');
    }

    /**
     * Test that the read() method throws an exception if there is no sheet available.
     */
    //    public function testReadThrowsExceptionIfNoSheetIsAvailable(): void
    //    {
    //        $this->markTestSkipped('This test has not been implemented yet.');
    //    }

    /**
     * Test that the read() method returns ImportResult with expected entries
     * when valid filepath and setId are provided
     */
    //    public function testReadWhenValidFileAndSetIdAreGiven(): void
    //    {
    //        $this->markTestSkipped('This test has not been implemented yet.');
    //    }
}
<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Tests\Exchange\Strings;

use PHPUnit\Framework\TestCase;
use Tumtum\PhpunuhiExportExcel\Exchange\Strings\CellValue;

class CellValueTest extends TestCase
{
    /**
     * Test the protect method of the CellValue class. It should prepend a single quote
     * to the input string if it starts with =, +, or - sign. If not, it should return the input string as it is.
     */
    public function testProtect(): void
    {
        $cellValue = new CellValue();
        $value = '=SUM(A1:A5)';
        $expected = "'=SUM(A1:A5)";
        $this->assertEquals($expected, $cellValue->protect($value));

        $value = '+831212';
        $expected = "'+831212";
        $this->assertEquals($expected, $cellValue->protect($value));

        $value = '-John Doe';
        $expected = "'-John Doe";
        $this->assertEquals($expected, $cellValue->protect($value));

        $value = 'John Doe';
        $expected = 'John Doe';
        $this->assertEquals($expected, $cellValue->protect($value));
    }

    public function testDeprotect(): void
    {
        $cellValue = new CellValue();

        $value = "'=SUM(A1:A5)";
        $expected = '=SUM(A1:A5)';
        $this->assertEquals($expected, $cellValue->deprotect($value));

        $value = "'+831212";
        $expected = '+831212';
        $this->assertEquals($expected, $cellValue->deprotect($value));

        $value = "'-John Doe";
        $expected = '-John Doe';
        $this->assertEquals($expected, $cellValue->deprotect($value));

        $value = 'John Doe';
        $expected = 'John Doe';
        $this->assertEquals($expected, $cellValue->deprotect($value));
    }
}

<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Tests\Exchange\Services;

use PHPUnit\Framework\TestCase;
use Tumtum\PhpunuhiExportExcel\Exchange\Services\SkipSet;

class SkipSetTest extends TestCase
{
    public function testByName(): void
    {
        $skipSet = new SkipSet('test1,test2,%test%');

        $this->assertTrue($skipSet->byName('test1'));
        $this->assertTrue($skipSet->byName('test2'));
        $this->assertTrue($skipSet->byName('myteststring')); // This should match %test%
        $this->assertFalse($skipSet->byName('notInSkipSet'));
    }
}

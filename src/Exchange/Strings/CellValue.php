<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Exchange\Strings;

class CellValue
{
    public function protect(string $value): string
    {
        return preg_replace('/^([=+-])/', "'$1", $value) ?? $value;
    }

    public function deprotect(string $value): string
    {
        return preg_replace('/^\'([=+-])/', "$1", $value) ?? $value;
    }
}

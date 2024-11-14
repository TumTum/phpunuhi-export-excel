<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel;

use PHPUnuhi\Bundles\Exchange\ExchangeFactory;
use Tumtum\PhpunuhiExportExcel\Exchange\ExcelExchange;

if (class_exists('PHPUnuhi\Bundles\Exchange\ExchangeFactory')) {
    ExchangeFactory::getInstance()->registerExchangeFormat(
        new ExcelExchange()
    );
}

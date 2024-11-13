<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel;

if (class_exists('PHPUnuhi\Bundles\Exchange\ExchangeFactory')) {
    \PHPUnuhi\Bundles\Exchange\ExchangeFactory::getInstance()->registerExchangeFormat(
        new Exchange\ExcelExchange()
    );
}

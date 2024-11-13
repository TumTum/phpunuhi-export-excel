<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\Set\ValueObject\SetList;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {

    $rectorConfig->paths([
        __DIR__ . '/bin',
        __DIR__ . '/scripts',
        __DIR__ . '/src',
        __DIR__ . '/tests/phpunit',
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_72);

    $rectorConfig->importNames();

    $rectorConfig->sets([
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::STRICT_BOOLEANS,
        SetList::TYPE_DECLARATION,
        SetList::EARLY_RETURN,
        SetList::INSTANCEOF,
    ]);

    $rectorConfig->skip([
        RemoveUselessReturnTagRector::class,
        RemoveUselessParamTagRector::class,
        RemoveUselessVarTagRector::class,
    ]);
};

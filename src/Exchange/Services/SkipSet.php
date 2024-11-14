<?php

declare(strict_types=1);

namespace Tumtum\PhpunuhiExportExcel\Exchange\Services;

class SkipSet
{
    /**
     * @var callable[]
     */
    private $skipSet = [];

    /**
     * @param $skipSet
     */
    public function __construct(string $skipSet)
    {
        if ($skipSet !== '') {
            $this->init($skipSet);
        }
    }

    private function init(string $skipSet): void
    {
        $skipSet = explode(',', $skipSet);

        foreach ($skipSet as $setName) {
            $setName = trim($setName);

            if (str_contains($setName, '%')) {
                $query = preg_quote($setName, '/');
                $query = str_replace('%', '.*', $query);

                $this->skipSet[] = fn ($value) => preg_match('/' . $query . '/', $value);
            } else {
                $this->skipSet[] = fn ($value) => $value === $setName;
            }
        }
    }

    public function byName(string $name): bool
    {
        foreach ($this->skipSet as $comparison) {
            if ($comparison($name)) {
                return true;
            }
        }
        return false;
    }
}

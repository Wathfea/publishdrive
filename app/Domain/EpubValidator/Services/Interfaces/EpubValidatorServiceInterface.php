<?php

namespace App\Domain\EpubValidator\Services\Interfaces;

interface EpubValidatorServiceInterface
{
    /**
     * @return void
     */
    public function writeValidationResultToXml(): void;
}

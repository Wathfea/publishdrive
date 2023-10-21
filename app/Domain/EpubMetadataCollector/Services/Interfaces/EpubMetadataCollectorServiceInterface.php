<?php

namespace App\Domain\EpubMetadataCollector\Services\Interfaces;

interface EpubMetadataCollectorServiceInterface
{
    /**
     * @param  array  $metadata
     * @param $destinationPath
     * @return void
     */
    public function writeMetadataToXml(array $metadata, $destinationPath): void;

    /**
     * @param  array  $metadatas
     * @return array
     */
    public function collectMetadata(array $metadatas): array;
}

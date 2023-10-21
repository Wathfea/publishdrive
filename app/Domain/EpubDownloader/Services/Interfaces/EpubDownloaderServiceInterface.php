<?php

namespace App\Domain\EpubDownloader\Services\Interfaces;

interface EpubDownloaderServiceInterface
{
    /**
     * Provide the target file urls for download them to the target destination
     *
     * @param  array  $urls
     * @param $destinationPath
     * @return int
     */
    public function downloadEpubFiles(array $urls, $destinationPath): int;
}

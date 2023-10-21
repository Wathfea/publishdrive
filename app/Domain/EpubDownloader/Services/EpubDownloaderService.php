<?php

namespace App\Domain\EpubDownloader\Services;

use App\Domain\EpubDownloader\Services\Interfaces\EpubDownloaderServiceInterface;
use Illuminate\Support\Facades\Http;

class EpubDownloaderService implements EpubDownloaderServiceInterface
{

    /**
     * @inheritDoc
     */
    public function downloadEpubFiles(array $urls, $destinationPath): int
    {
        foreach ($urls as $url) {
            $response = Http::get($url);
            $destination = $destinationPath . '/' . basename($url);
            file_put_contents($destination, $response->body());
        }

        return count($urls);
    }
}

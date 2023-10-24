<?php

namespace App\Domain\EpubDownloader\Services;

use App\Domain\EpubDownloader\Services\Interfaces\EpubDownloaderServiceInterface;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EpubDownloaderService implements EpubDownloaderServiceInterface
{
    /**
     * @inheritDoc
     */
    public function downloadEpubFiles(array $urls, $destinationPath): int
    {
        $successfulDownloads = 0;

        foreach ($urls as $url) {
            $destination = $destinationPath . '/' . basename($url);

            try {
                $response = Http::get($url);
                file_put_contents($destination, $response->body());
                $successfulDownloads++;
            } catch (Exception $e) {
                // Handle download errors (e.g., log, report, or skip)
                // You may log the error, send a notification, or take other actions.
                // For simplicity, we're just skipping failed downloads here and just simple create a basic log msg.
                Log::error("Error during download from {$url}", ['error' => $e->getMessage()]);
                continue;
            }
        }

        return $successfulDownloads;
    }
}

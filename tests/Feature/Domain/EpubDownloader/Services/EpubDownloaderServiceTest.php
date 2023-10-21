<?php

use App\Domain\EpubDownloader\Services\Interfaces\EpubDownloaderServiceInterface;

test('it can download EPUB files', function () {
    // Create a temporary directory to store the downloaded EPUB files
    $tempDir = __DIR__ . '/temp_downloads';
    if (!is_dir($tempDir)) {
        mkdir($tempDir);
    }

    // Define the URLs of EPUB files to download
    $urls = [
        'https://account.publishdrive.com/Books/Book1.epub',
        'https://account.publishdrive.com/Books/Book2.epub',
    ];

    // Create an instance of the EpubDownloaderService
    $epubDownloaderService = app(EpubDownloaderServiceInterface::class);

    // Download the EPUB files
    $downloadedCount = $epubDownloaderService->downloadEpubFiles($urls, $tempDir);

    // Assert that the correct number of files were downloaded
    expect($downloadedCount)->toBe(count($urls));

    // Assert that the downloaded files exist in the temporary directory
    foreach ($urls as $url) {
        $filename = basename($url);
        $filePath = $tempDir . '/' . $filename;
        expect(file_exists($filePath))->toBeTrue();
    }

    // Clean up - remove the temporary directory and downloaded files
    foreach ($urls as $url) {
        $filename = basename($url);
        $filePath = $tempDir . '/' . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    if (is_dir($tempDir)) {
        rmdir($tempDir);
    }
});

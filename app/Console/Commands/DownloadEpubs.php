<?php

namespace App\Console\Commands;

use App\Domain\EpubDownloader\Services\Interfaces\EpubDownloaderServiceInterface;
use Illuminate\Console\Command;

class DownloadEpubs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:download-epubs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and store following files for further actions';

    public function __construct(public EpubDownloaderServiceInterface $epubDownloaderService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $urls = [
            'https://account.publishdrive.com/Books/Book1.epub',
            'https://account.publishdrive.com/Books/Book2.epub',
            'https://account.publishdrive.com/Books/Book3.epub',
            'https://account.publishdrive.com/Books/Book4.epub',
        ];

        if (!is_dir(public_path('epub_files'))) {
            mkdir(public_path('epub_files'));
        }

        $downloadedCount = $this->epubDownloaderService->downloadEpubFiles($urls, public_path('epub_files'));

        $this->info("Downloaded $downloadedCount EPUB files.");
    }
}

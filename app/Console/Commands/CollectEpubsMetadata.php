<?php

namespace App\Console\Commands;

use App\Domain\EpubMetadataCollector\Services\Interfaces\EpubMetadataCollectorServiceInterface;
use Illuminate\Console\Command;

class CollectEpubsMetadata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:collect-epubs-metadata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find and collect metadata from EPUB files';

    public function __construct(public EpubMetadataCollectorServiceInterface $collectorService)
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $metadataToLookFor = ['creator', 'title', 'publisher'];
        $collectedData = $this->collectorService->collectMetadata($metadataToLookFor);
        $this->collectorService->writeMetadataToXml($collectedData, public_path('metadata.xml'));

        $this->info('Metadata written to XML file.');
    }
}

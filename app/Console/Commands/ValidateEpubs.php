<?php

namespace App\Console\Commands;

use App\Domain\EpubValidator\Services\Interfaces\EpubValidatorServiceInterface;
use Illuminate\Console\Command;
use SimpleXMLElement;

class validateEpubs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:validate-epubs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate epub files and add result to metadata.xml ';

    public function __construct(public EpubValidatorServiceInterface $epubValidatorService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->epubValidatorService->writeValidationResultToXml();
        $this->info("Validation results wrote into XML");
    }
}

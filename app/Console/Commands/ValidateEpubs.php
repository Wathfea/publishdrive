<?php

namespace App\Console\Commands;

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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directoryPath = public_path('epub_files');
        $ebookChecker = config('services.epubcheck.jar_location');

        if ($ebookChecker === '') {
            die('Jar file nincs definiálva az ENV-ben');
        }

        if (is_dir($directoryPath)) {
            $files = array_diff(scandir($directoryPath), ['.', '..']); // Remove '.' and '..' entries

            foreach ($files as $file) {
                $filePath = $directoryPath.'/'.$file;
                $validationResult = shell_exec("java -jar $ebookChecker $filePath 2>&1");

                $epubcheckResult = '<epubcheck>' . $validationResult . '</epubcheck>';
                $xml = simplexml_load_file(public_path('metadata.xml'));

                // Search for the <book> element with the matching $file
                $bookElement = null;
                foreach ($xml->book as $book) {
                    if ($book['name'] == $file) {
                        $bookElement = $book;
                        break;
                    }
                }

                if ($bookElement) {
                    // Create a new XML element to hold the validation result
                    $validationElement = new SimpleXMLElement($epubcheckResult);
                    // Append the validation result to the <book> element
                    $bookElement->addChild('validationResult', $validationElement);
                }

                // Save the modified XML back to the file
                $xml->asXML(public_path('metadata.xml'));
            }
        }
    }
}

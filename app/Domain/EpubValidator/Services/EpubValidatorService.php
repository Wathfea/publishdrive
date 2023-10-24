<?php

namespace App\Domain\EpubValidator\Services;

use App\Domain\EpubValidator\Services\Interfaces\EpubValidatorServiceInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use RuntimeException;
use SimpleXMLElement;

class EpubValidatorService implements EpubValidatorServiceInterface
{
    /**
     * Validate an EPUB file using epubcheck.
     *  For validating the epubs we use a thirdparty lib and shell_exec
     *  Because of the shell_exec additional security measures added.
     *
     * @param string $filePath
     * @return string|null
     */
    private function validateEpub(string $filePath): ?string
    {
        $ebookChecker = Config::get('services.epubcheck.jar_location');
        $javaPath = Config::get('services.epubcheck.java_path');

        if (empty($ebookChecker) || empty($javaPath)) {
            throw new RuntimeException('Epubcheck configuration is missing.');
        }

        $escapedFilePath = escapeshellarg($filePath);

        return shell_exec("$javaPath -jar $ebookChecker $escapedFilePath 2>&1");
    }

    /**
     * @inheritDoc
     */
    public function writeValidationResultToXml(): void
    {
        $metadataFilePath = public_path('metadata.xml');

        if (!File::exists($metadataFilePath)) {
            throw new RuntimeException('Metadata XML does not exist.');
        }

        $epubFilesDirectory = public_path('epub_files');

        if (!File::isDirectory($epubFilesDirectory)) {
            throw new RuntimeException('Epub files directory is missing.');
        }

        $xml = simplexml_load_file($metadataFilePath);

        $files = array_diff(scandir($epubFilesDirectory), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $epubFilesDirectory . '/' . $file;
            $validationResult = $this->validateEpub($filePath);

            $epubcheckResult = '<epubcheck>' . $validationResult . '</epubcheck>';

            // Find the book element with the matching name attribute
            $bookElement = $xml->xpath("//book[@name='$file']")[0] ?? null;

            if ($bookElement) {
                // Create a new XML element to hold the validation result
                $validationElement = new SimpleXMLElement($epubcheckResult);
                // Append the validation result to the book element
                $bookElement->addChild('validationResult', $validationElement);
            }
        }

        $xml->asXML($metadataFilePath);
    }
}

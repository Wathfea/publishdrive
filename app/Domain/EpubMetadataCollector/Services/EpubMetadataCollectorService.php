<?php

namespace App\Domain\EpubMetadataCollector\Services;

use App\Domain\EpubMetadataCollector\Services\Interfaces\EpubMetadataCollectorServiceInterface;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SimpleXMLElement;
use ZipArchive;

class EpubMetadataCollectorService implements EpubMetadataCollectorServiceInterface
{

    /**
     * @inheritDoc
     */
    public function writeMetadataToXml(array $metadata, $destinationPath): void
    {
        $rootElement = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><books/>');

        foreach ($metadata as $bookName => $bookData) {
            $bookElement = $rootElement->addChild('book');
            $bookElement->addAttribute('name', $bookName);

            foreach ($bookData as $key => $value) {
                $bookElement->addChild($key, $value);
            }
        }

        $rootElement->asXML($destinationPath);
    }

    public function collectMetadata(array $metadatas): array
    {
        $returnData = [];
        $tempDir = 'temp';

        $directoryPath = public_path('epub_files');

        if (is_dir($directoryPath)) {
            $files = array_diff(scandir($directoryPath), ['.', '..']); // Remove '.' and '..' entries

            foreach ($files as $file) {
                $filePath = $directoryPath . '/' . $file;
                $destinationPath = $tempDir;

                $zip = new ZipArchive();
                if ($zip->open($filePath) === true) {
                    $zip->extractTo($destinationPath);
                    $zip->close();
                } else {
                    die('Failed to open the EPUB file.');
                }

                $infoFile = $destinationPath . '/META-INF/container.xml';
                if (file_exists($infoFile)) {
                    $infoContent = file_get_contents($infoFile);
                    $xml = new SimpleXMLElement($infoContent);
                    $metadataFilePath = (string) $xml->rootfiles->rootfile['full-path'];
                    $metadataFile = $destinationPath . '/' . $metadataFilePath;

                    if (file_exists($metadataFile)) {
                        $metadataContent = file_get_contents($metadataFile);
                        $xml = new SimpleXMLElement($metadataContent);
                        $namespaces = $xml->getNamespaces(true); // Retrieve all namespaces
                        $dc = $xml->metadata->children($namespaces['dc']); // Use the 'dc' namespace

                        $metadata = [];
                        // Extracting metadata fields
                        foreach ($metadatas as $needle) {
                            $metadata[$needle] = (string) $dc->{$needle};
                        }

                        $returnData[$file] = $metadata;

                        // Clean up the temporary folder
                        if (is_dir($destinationPath)) {
                            $this->cleanUpTempFolder($destinationPath);
                        }
                    } else {
                        die('Metadata file not found.');
                    }
                } else {
                    die('Info file not found.');
                }
            }
        } else {
            echo 'The directory does not exist.';
        }

        return $returnData;
    }

    /**
     * @param $folderPath
     * @return void
     */
    private function cleanUpTempFolder($folderPath): void
    {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            if ($path->isDir()) {
                rmdir($path->__toString());
            } else {
                unlink($path->__toString());
            }
        }
        rmdir($folderPath);
    }


}

<?php

namespace Differ\Differ;

use function Differ\Agregator\agregateDiff;
use function Differ\Parsers\parse;

const OUTPUT_FORMATS = ['Json', 'Plain', 'Pretty'];

function genDiff(string $firstFilePath, string $secondFilePath, string $format): string
{
    $firstFileKeys = readFile($firstFilePath);
    $secondFileKeys = readFile($secondFilePath);

    $differences = agregateDiff($firstFileKeys, $secondFileKeys);
    $render = selectRender($format, OUTPUT_FORMATS);
    return $render($differences);
}

function readFile(string $filePath): array
{
    if (!file_exists($filePath)) {
        throw new \Exception("File not found. You should write a correct path to the file '$filePath'");
    }
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
    $fileContent = file_get_contents($filePath);
    return parse($fileContent, $fileExtension);
}

function selectRender(string $format, array $output): string
{
    $currentFormat = ucfirst($format);
    if (!in_array($currentFormat, $output)) {
        throw new \Exception("Unknown format '$format'. Supported formats: " . implode(', ', $output) . ".");
    }
    return "Differ\\Formatters\\$currentFormat\\render";
}

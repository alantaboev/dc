<?php

namespace Differ\Differ;

use function Differ\Agregator\agregateDiff;
use function Differ\Parsers\parse;

function genDiff(string $firstFilePath, string $secondFilePath, string $format): string
{
    // Получение расширения файлов. Необходимо, чтобы понимать какой формат парсить
    $firstFileExtension = pathinfo($firstFilePath, PATHINFO_EXTENSION);
    $secondFileExtension = pathinfo($secondFilePath, PATHINFO_EXTENSION);

    // Парсинг содержимого файлов в ассоциативный массив
    $firstFileKeys = readFile($firstFilePath, $firstFileExtension);
    $secondFileKeys = readFile($secondFilePath, $secondFileExtension);

    // Генерация массива данных с действиями, ключами и их значениями
    $differences = agregateDiff($firstFileKeys, $secondFileKeys);

    // Выбор функции для рендера
    $formats = ['Json', 'Plain', 'Pretty']; // Поддерживаемые форматы вывода
    $currentFormat = ucfirst($format);
    if (!in_array($currentFormat, $formats)) {
        throw new \Exception("Unknown format '$format'. Supported formats: " . implode(', ', $formats) . ".");
    }
    $render = "Differ\\Formatters\\$format\\render";

    return $render($differences);
}

function readFile(string $filePath, string $fileExtension): array
{
    // Проверка путей с выбросом исключения в случае отсутствия файла
    if (!file_exists($filePath)) {
        throw new \Exception("File not found. You should write a correct path to the file '$filePath'");
    }

    $fileContent = file_get_contents($filePath); // Получение содержимого файла
    return parse($fileContent, $fileExtension);  // Возврат содержимого файла в ассоциативном массиве
}

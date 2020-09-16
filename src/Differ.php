<?php

namespace Differ\Differ;

use function Differ\Agregator\agregateDiff;
use function Differ\Parser\parse;
use function Differ\Formatters\Pretty\render;

function genDiff($firstFilePath, $secondFilePath, $format)
{
    // Проверка путей с выбросом исключения в случае отсутствия файла
    if (!file_exists($firstFilePath) || !file_exists($secondFilePath)) {
        throw new \Exception("File not found. You should write a correct path to the file");
    }

    // Получение расширения файлов. Необходимо для функции parse, чтобы понимать какой формат парсить
    $firstFileExtension = pathinfo($firstFilePath, PATHINFO_EXTENSION);
    $secondFileExtension = pathinfo($secondFilePath, PATHINFO_EXTENSION);

    // Получение содержимого файлов
    $firstFileContent = file_get_contents($firstFilePath);
    $secondFileContent = file_get_contents($secondFilePath);

    // Парсинг содержимого файлов в ассоциативный массив
    $firstFileKeys = parse($firstFileContent, $firstFileExtension);
    $secondFileKeys = parse($secondFileContent, $secondFileExtension);

    // Генерация массива данных с действиями, ключами и их значениями
    $differences = agregateDiff($firstFileKeys, $secondFileKeys);

    return render($differences);
}

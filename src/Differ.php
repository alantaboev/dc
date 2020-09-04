<?php

namespace Differ\Differ;

function genDiff($filepath1, $filepath2, $format = 'pretty')
{
    if (!file_exists($filepath1) || !file_exists($filepath2)) {
        throw new \Exception("File not found. You should write a correct path to the file");
    }
    return "$filepath1";
}

<?php

namespace Differ\Agregator;

use function Funct\Collection\union;

function agregateDiff(array $before, array $after)
{
    $uniqueKeys = array_keys(union($before, $after)); // Объединение и выборка уникальных ключей в отдельный массив
    return array_reduce($uniqueKeys, function ($acc, $key) use ($before, $after) {
        $acc[] = getData($key, $before, $after);
        return $acc;
    }, $acc = []);
}

// Получение данных ключа
function getData(string $key, array $before, array $after)
{
    // Если ключа нет в первом файле, то он считается добавленным
    if (!array_key_exists($key, $before)) {
        return ['key' => $key, 'value' => $after[$key], 'type' => 'added'];
    }
    // Если ключа нет во втором файле, то он считается удаленным
    if (!array_key_exists($key, $after)) {
        return ['key' => $key, 'value' => $before[$key], 'type' => 'deleted'];
    }
    // Если значения ключа из обоих файлов равны, значит он НЕ изменился
    if ($before[$key] === $after[$key]) {
        return ['key' => $key, 'value' => $before[$key], 'type' => 'unchanged'];
    }
    // Если значения ключа из обоих файлов НЕ равны, значит он изменился
    if ($before[$key] !== $after[$key]) {
        return ['key' => $key, 'beforeValue' => $before[$key], 'afterValue' => $after[$key], 'type' => 'changed'];
    }
    // Нужно ли тут выбросить исключение или оставить как есть?
    return null;
}

<?php

namespace Differ\Agregator;

use function Funct\Collection\union;

function agregateDiff(array $before, array $after): array
{
    // Объединение и выборка уникальных ключей в отдельный массив
    $uniqueKeys = union(array_keys($before), array_keys($after));
    sort($uniqueKeys);
    return array_reduce($uniqueKeys, function ($acc, $key) use ($before, $after) {
        $acc[] = getData($key, $before, $after);
        return $acc;
    }, $acc = []);
}

// Получение данных ключа
function getData(string $key, array $before, array $after): array
{
    // В зависимости от условий проставляется значение type
    if (!array_key_exists($key, $before)) {
        // Если ключа нет в первом файле, то он считается добавленным
        $result = ['key' => $key, 'value' => $after[$key], 'type' => 'added'];
    } elseif (!array_key_exists($key, $after)) {
        // Если ключа нет во втором файле, то он считается удаленным
        $result = ['key' => $key, 'value' => $before[$key], 'type' => 'deleted'];
    } elseif (is_array($before[$key]) && is_array($after[$key])) {
        // Если значение ключа в обоих случаях массив, то создаем узел
        $result = ['name' => $key, 'type' => 'parent', 'children' => agregateDiff($before[$key], $after[$key])];
    } elseif ($before[$key] === $after[$key]) {
        // Если значения ключа из обоих файлов равны, значит он НЕ изменился
        $result = ['key' => $key, 'value' => $before[$key], 'type' => 'unchanged'];
    } else {
        // Если значения ключа из обоих файлов НЕ равны, значит он изменился
        $result = ['key' => $key, 'beforeValue' => $before[$key], 'afterValue' => $after[$key], 'type' => 'changed'];
    }
    return $result;
}

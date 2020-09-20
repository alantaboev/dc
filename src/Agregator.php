<?php

namespace Differ\Agregator;

use function Funct\Collection\union;

function agregateDiff($before, $after): array
{
    // Проверка входных данных. Объекты преобразуем в ассоциативные массивы
    if (is_object($before)) {
        $before = convertObjectToArray($before);
    }
    if (is_object($after)) {
        $after = convertObjectToArray($after);
    }
    // Объединение и выборка уникальных ключей в отдельный массив
    $uniqueKeys = union(array_keys($before), array_keys($after));
    sort($uniqueKeys);
    return array_reduce($uniqueKeys, function ($acc, $key) use ($before, $after) {
        $acc[] = getData($key, $before, $after);
        return $acc;
    }, $acc = []);
}

// Получение данных ключа
function getData(string $key, array $before, array $after)
{
    // В зависимости от условий проставляется значение type
    if (!array_key_exists($key, $before)) {
        // Если ключа нет в первом файле, то он считается добавленным
        return ['key' => $key, 'value' => $after[$key], 'type' => 'added'];
    } elseif (!array_key_exists($key, $after)) {
        // Если ключа нет во втором файле, то он считается удаленным
        return ['key' => $key, 'value' => $before[$key], 'type' => 'deleted'];
    } elseif (is_array($before[$key]) && is_array($after[$key])) {
        // Если значение ключа в обоих случаях массив, то создаем узел
        return ['name' => $key, 'type' => 'parent', 'children' => agregateDiff($before[$key], $after[$key])];
    } elseif ($before[$key] === $after[$key]) {
        // Если значения ключа из обоих файлов равны, значит он НЕ изменился
        return ['key' => $key, 'value' => $before[$key], 'type' => 'unchanged'];
    } else {
        // Если значения ключа из обоих файлов НЕ равны, значит он изменился
        return ['key' => $key, 'beforeValue' => $before[$key], 'afterValue' => $after[$key], 'type' => 'changed'];
    }
}

// Преобразование объекта в ассоциативный массив
function convertObjectToArray(object $data): array
{
    $result = [];
    foreach ((array)$data as $key => $value) {
        $result[$key] = is_object($value) ? convertObjectToArray($value) : $value;
    }
    return $result;
}

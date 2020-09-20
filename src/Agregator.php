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
    // Если ключа нет в первом файле, то он считается добавленным
    if (!array_key_exists($key, $before)) {
        return ['key' => $key, 'value' => $after[$key], 'type' => 'added'];
    }
    // Если ключа нет во втором файле, то он считается удаленным
    if (!array_key_exists($key, $after)) {
        return ['key' => $key, 'value' => $before[$key], 'type' => 'deleted'];
    }
    // Если значение ключа в обоих случаях массив, то создаем узел
    if (is_array($before[$key]) && is_array($after[$key])) {
        return ['name' => $key, 'type' => 'node', 'children' => agregateDiff($before[$key], $after[$key])];
    }
    // Если значения ключа из обоих файлов равны, значит он НЕ изменился
    if ($before[$key] === $after[$key]) {
        return ['key' => $key, 'value' => $before[$key], 'type' => 'unchanged'];
    }
    // Если значения ключа из обоих файлов НЕ равны, значит он изменился
    if ($before[$key] !== $after[$key]) {
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

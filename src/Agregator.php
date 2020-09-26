<?php

namespace Differ\Agregator;

use function Funct\Collection\union;

function agregateDiff(array $before, array $after): array
{
    $uniqueKeys = union(array_keys($before), array_keys($after)); // Выборка уникальных ключей в отдельный массив
    sort($uniqueKeys);
    return array_map(function ($key) use ($before, $after) {
        return getChanges($key, $before, $after);
    }, $uniqueKeys);
}

// Получение данных ключа
function getChanges(string $key, array $before, array $after): array
{
    // В зависимости от условий проставляется значение type
    // Если ключа нет в первом файле, то он считается добавленным
    if (!array_key_exists($key, $before)) {
        return createSimpleStructure($key, $after[$key], 'added');
    }
    // Если ключа нет во втором файле, то он считается удаленным
    if (!array_key_exists($key, $after)) {
        return createSimpleStructure($key, $before[$key], 'deleted');
    }
    // Если значение ключа в обоих случаях массив, то создаем узел
    if (is_array($before[$key]) && is_array($after[$key])) {
        return createNode($key, $before[$key], $after[$key], 'parent');
    }
    // Если значения ключа из обоих файлов НЕ равны, значит он изменился
    if ($before[$key] !== $after[$key]) {
        return createStructure($key, $before[$key], $after[$key], 'changed');
    }
    // Ключ не был изменен, если не сработало ни одно из условий выше
    return createSimpleStructure($key, $before[$key], 'unchanged');
}

// Создание простой структуры
function createSimpleStructure(string $key, $value, string $type): array
{
    return ['key' => $key, 'value' => $value, 'type' => $type];
}

// Создание структуры с изменением данных
function createStructure(string $key, $oldValue, $newValue, string $type): array
{
    return ['key' => $key, 'beforeValue' => $oldValue, 'afterValue' => $newValue, 'type' => $type];
}

//Создание дерева/узла
function createNode(string $key, array $before, array $after, string $type): array
{
    return ['key' => $key, 'type' => $type, 'children' => agregateDiff($before, $after)];
}

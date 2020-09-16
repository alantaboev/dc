<?php

namespace Differ\Tests;

use \PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testJsonPretty()
    {
        $before = __DIR__ . '\fixtures\before.json';
        $after = __DIR__ . '\fixtures\after.json';
        $content = file_get_contents(__DIR__ . '\fixtures\expectJsonPretty');
        $expect = str_replace("\r\n", "\n", $content); // Заменяем переносы строк, если работаем в Windows
        $this->assertEquals($expect, genDiff($before, $after, 'pretty'));
    }
}

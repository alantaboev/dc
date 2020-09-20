<?php

namespace Differ\Tests;

use \PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    // Тест плоских файлов JSON с выводом в формате Pretty
    public function testJsonPretty()
    {
        $before = __DIR__ . "/fixtures/before.json";
        $after = __DIR__ . "/fixtures/after.json";
        $content = file_get_contents(__DIR__ . "/fixtures/expectPretty");
        $expect = str_replace("\r\n", "\n", $content); // Замена переноса строк, если работаем в Windows
        $this->assertEquals($expect, genDiff($before, $after, 'pretty'));
    }

    // Тест плоских файлов YAML с выводом в формате Pretty
    public function testYamlPretty()
    {
        $before = __DIR__ . "/fixtures/before.yaml";
        $after = __DIR__ . "/fixtures/after.yaml";
        $content = file_get_contents(__DIR__ . "/fixtures/expectPretty");
        $expect = str_replace("\r\n", "\n", $content);
        $this->assertEquals($expect, genDiff($before, $after, 'pretty'));
    }

    // Тест вложенных файлов JSON с выводом в формате Pretty
    public function testNestedJsonPretty()
    {
        $before = __DIR__ . "/fixtures/beforeNested.json";
        $after = __DIR__ . "/fixtures/afterNested.json";
        $content = file_get_contents(__DIR__ . "/fixtures/expectNestedPretty");
        $expect = str_replace("\r\n", "\n", $content);
        $this->assertEquals($expect, genDiff($before, $after, 'pretty'));
    }

    // Тест вложенных файлов YAML с выводом в формате Pretty
    public function testNestedYamlPretty()
    {
        $before = __DIR__ . "/fixtures/beforeNested.yaml";
        $after = __DIR__ . "/fixtures/afterNested.yaml";
        $content = file_get_contents(__DIR__ . "/fixtures/expectNestedPretty");
        $expect = str_replace("\r\n", "\n", $content);
        $this->assertEquals($expect, genDiff($before, $after, 'pretty'));
    }

    // Тест вложенных файлов JSON с выводом в формате Plain
    public function testNestedJsonPlain()
    {
        $before = __DIR__ . "/fixtures/beforeNested.json";
        $after = __DIR__ . "/fixtures/afterNested.json";
        $content = file_get_contents(__DIR__ . "/fixtures/expectPlain");
        $expect = str_replace("\r\n", "\n", $content);
        $this->assertEquals($expect, genDiff($before, $after, 'plain'));
    }

    // Тест вложенных файлов YAML с выводом в формате Plain
    public function testNestedYamlPlain()
    {
        $before = __DIR__ . "/fixtures/beforeNested.yaml";
        $after = __DIR__ . "/fixtures/afterNested.yaml";
        $content = file_get_contents(__DIR__ . "/fixtures/expectPlain");
        $expect = str_replace("\r\n", "\n", $content);
        $this->assertEquals($expect, genDiff($before, $after, 'plain'));
    }

    // Тест вложенных файлов JSON с выводом в формате Json
    public function testNestedJson()
    {
        $before = __DIR__ . "/fixtures/beforeNested.json";
        $after = __DIR__ . "/fixtures/afterNested.json";
        $content = file_get_contents(__DIR__ . "/fixtures/expect.json");
        $expect = str_replace("\r\n", "\n", $content);
        $this->assertEquals($expect, genDiff($before, $after, 'json'));
    }

    // Тест вложенных файлов YAML с выводом в формате Json
    public function testNestedYaml()
    {
        $before = __DIR__ . "/fixtures/beforeNested.yaml";
        $after = __DIR__ . "/fixtures/afterNested.yaml";
        $content = file_get_contents(__DIR__ . "/fixtures/expect.json");
        $expect = str_replace("\r\n", "\n", $content);
        $this->assertEquals($expect, genDiff($before, $after, 'json'));
    }
}

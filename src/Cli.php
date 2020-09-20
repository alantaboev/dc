<?php

namespace Differ\Cli;

use Docopt;

use function Differ\Differ\genDiff;

function run()
{
    $doc = <<<DOC
    Generate diff
        
    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
      gendiff [--format <fmt>] <firstFile> <secondFile>
    
    Options:
      -h --help                     Show this screen
      -v --version                  Show version
      --format <fmt>                Report format [default: pretty]
    DOC;

    $args = Docopt::handle($doc, ['version' => 'gendiff v1.0.*']);

    try {
        print_r(genDiff($args['<firstFile>'], $args['<secondFile>'], $args['--format']));
    } catch (\Exception $e) {
        print_r($e->getMessage());
        die();
    }
}

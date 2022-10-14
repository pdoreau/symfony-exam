<?php

namespace App\Tests\Service;

use App\Service\Directory;
use App\Service\File;
use App\Service\VisitFiles;
use PHPUnit\Framework\TestCase;

class VisitFilesTest extends TestCase
{
    public function testUsageExemple()
    {
        $this->assertEquals(
            ['a', 'aba', 'c', 'b', 'bcb'],
            array_map(
                function(File $file) {
                    return $file->name;
                },
                (new VisitFiles())->usageExemple(
                    (new Directory(
                        'A',
                        [
                            new File('a'),
                            new File('ab'),
                            new File('aba'),
                            new Directory('A.B',
                                [
                                    new Directory('A.B.C', [new File('c')]),
                                    new File('b'),
                                    new File('bc'),
                                    new File('bcb'),
                                ]
                            )
                        ]
                    ))
                )
            )
        );
    }
}

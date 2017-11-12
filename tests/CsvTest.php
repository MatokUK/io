<?php

namespace Matok\IO\Test;

use Matok\IO\Csv;
use Matok\IO\Exception\FileNotExistsException;
use PHPUnit\Framework\TestCase;

class CsvIOTest extends TestCase
{
    /**
     * @dataProvider getFilesForReading
     */
    public function testReadLine($filename, $delimiter, $lineNumber, $expectedLine)
    {
        $csvReader = new Csv($filename, ['delimiter' => $delimiter]);
        $readData = $csvReader->readLine($lineNumber);

        $this->assertEquals($expectedLine, $readData);
    }

    public function testWriteLine()
    {
        $csvReader = new Csv(__DIR__.'/write/example.csv', ['delimiter' => ':']);

        $lineHeader = ['ID', 'text', 'color'];
        $lineOne = [1, 'red', '#FF0000'];
        $lineTwo = [5, 'blue', '#0000FF'];

        $csvReader->writeLine($lineHeader);
        $csvReader->writeLine($lineOne);

        $expectedContent = implode(':', $lineHeader)."\n".implode(':', $lineOne)."\n";
        $this->assertEquals($expectedContent, file_get_contents(__DIR__.'/write/example.csv'));

        $csvReader->writeLine($lineTwo);
        $expectedContent .= implode(':', $lineTwo)."\n";
        $this->assertEquals($expectedContent, file_get_contents(__DIR__.'/write/example.csv'));


        $csvReader2 = new Csv(__DIR__.'/write/example.csv', ['delimiter' => ':']);
        $csvReader2->writeLine($lineTwo);
        $expectedContent .= implode(':', $lineTwo)."\n";
        $this->assertEquals($expectedContent, file_get_contents(__DIR__.'/write/example.csv'));
    }

    public function testReadThanWrite()
    {
        file_put_contents(__DIR__.'/write/permission.csv', '');
        $csvIO = new Csv(__DIR__.'/write/permission.csv');


        $line = $csvIO->readLine(0);
        $csvIO->writeLine(['write', 'write', 'writing']);

        $check = file_get_contents(__DIR__.'/write/permission.csv');
        $this->assertContains('write', $check);
    }

    /**
     * @expectedException \Matok\IO\Exception\FileNotExistsException
     */
    public function testReadNonExistingFile()
    {
        $csvReader = new Csv(__DIR__.'/read/not_exist.csv');
        $csvReader->readLine(1);
    }

    public function getFilesForReading()
    {
        return [
            [__DIR__.'/read/coma.csv', ',', 1, ['line 1', 'abc;bb']],
            [__DIR__.'/read/semicolon.csv', ';', 2, ['line 2', 'bbb']],
        ];
    }

    public function tearDown()
    {
        if (is_file(__DIR__.'/write/example.csv')) {
            unlink(__DIR__.'/write/example.csv');
        }

        if (is_file(__DIR__.'/write/permission.csv')) {
            unlink(__DIR__.'/write/permission.csv');
        }
    }
}
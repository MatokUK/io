<?php

namespace Matok\IO;

class Csv
{
    private $delimiter = ';';

    private $filename;

    private $fp;


    public function __construct($filename, $options = [])
    {
        $this->filename = $filename;
        $this->readOptions($options);
    }

    public function __destruct()
    {
        if (null !== $this->fp) {
            fclose($this->fp);
        }
    }

    private function readOptions($options)
    {
        if (isset($options['delimiter'])) {
            $this->delimiter = $options['delimiter'];
        }
    }

    public function readLines()
    {
        $this->openFile('r');

        $result = [];
        while (($data = fgetcsv($this->fp, 0, $this->delimiter)) !== false) {
            $result[] = $data;
        }

        return $result;
    }

    public function readLine($lineNumber)
    {
        $actualLine = 1;
        $this->openFile('r');

        while (($data = fgetcsv($this->fp, 0, $this->delimiter)) !== false) {
            if ($lineNumber == $actualLine++) {
                break;
            }
        }

        return $data;
    }

    protected function getHeader()
    {
        $header = $this->readLine(1);

        return array_combine(array_values($header), array_keys($header));
    }

    public function writeLine($data)
    {
        $this->openFile();

        fputcsv($this->fp, $data, $this->delimiter);
    }

    private function openFile($mode = 'a')
    {
        if (null === $this->fp) {
            $this->fp = fopen($this->filename, $mode);
        }
    }
}
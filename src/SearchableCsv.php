<?php

namespace Matok\IO;

class SearchableCsv extends Csv
{
    public function searchByIndex($search)
    {
        $lines = $this->readLines();

        foreach ($lines as $line) {
            if ($this->applySearchCriteria($line, $search)) {
                return $line;
            }
        }

        return false;
    }

    public function searchByName($search)
    {
        $header = $this->getHeader();

        $searchByIndex = [];

        foreach ($search as $key => $value) {
            $index = $header[$key] + 1;
            $searchByIndex[$index] = $value;
        }

        return $this->searchByIndex($searchByIndex);
    }

    private function applySearchCriteria($line, $search)
    {
        foreach ($search as $index => $searchTerm) {
            if ($line[$index-1] != $searchTerm) {
                return false;
            }
        }

        return true;
    }
}
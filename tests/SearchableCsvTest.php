<?php

namespace Matok\IO\Test;

use Matok\IO\SearchableCsv;
use PHPUnit\Framework\TestCase;

class SearchableCsvTest extends TestCase
{
    /**
     * @dataProvider getIndexedSearchTests
     */
    public function testSearchByIndex($filename, $search, $expectedResult)
    {
        $csv = new SearchableCsv($filename, ['delimiter' => ';']);
        $searchResult = $csv->searchByIndex($search);
        $searchResultLine = implode(';', $searchResult);

        $this->assertContains($expectedResult, $searchResultLine);
    }

    /**
     * @dataProvider getNamedSearchTests
     */
    public function testSearchByName($filename, $search, $expectedResult)
    {
        $csv = new SearchableCsv($filename, ['delimiter' => ';']);
        $searchResult = $csv->searchByName($search);
        $searchResultLine = implode(';', $searchResult);

        $this->assertContains($expectedResult, $searchResultLine);
    }

    public function getIndexedSearchTests()
    {
        return [
            [__DIR__.'/search/data.csv', [1 => 'social', 2 => 'facebook'], 'https://facebook.com'],
            [__DIR__.'/search/data.csv', [1 => 'blog', 3 => 'https://matok.me.uk'], 'Matok PHP'],
        ];
    }

    public function getNamedSearchTests()
    {
        return [
            [__DIR__.'/search/data.csv', ['type' => 'mail', 'name' => 'yahoo'], 'https://yahoo.com'],
            [__DIR__.'/search/data.csv', ['type' => 'mail', 'name' => 'gmail'], 'https://gmail.com'],
        ];
    }
}
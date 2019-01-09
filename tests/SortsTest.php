<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Query\Sorts;
use Sarala\Query\SortField;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class SortsTest extends TestCase
{
    /** @var Sorts $sorts */
    private $sorts;

    public function setUp()
    {
        parent::setUp();
        $request = Request::create('/url?sort=-created_at,title');
        $this->sorts = new Sorts($request);
    }

    public function test_sort()
    {
        $this->sorts->each(function (SortField $sortField) {
            if ($sortField->getField() == 'created_at') {
                $this->assertEquals('desc', $sortField->getDirection());
            }

            if ($sortField->getField() == 'title') {
                $this->assertEquals('asc', $sortField->getDirection());
            }
        });
    }
}

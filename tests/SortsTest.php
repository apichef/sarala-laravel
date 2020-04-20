<?php

declare(strict_types=1);

namespace Sarala;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Sarala\Query\SortField;
use Sarala\Query\Sorts;

class SortsTest extends TestCase
{
    /** @var Sorts */
    private $sorts;

    public function setUp(): void
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

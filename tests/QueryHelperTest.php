<?php

declare(strict_types=1);

namespace Sarala;

use Sarala\Dummy\Post;
use Illuminate\Http\Request;
use Sarala\Query\QueryHelper;
use Sarala\Query\QueryParamBag;
use Illuminate\Support\Facades\DB;

class QueryHelperTest extends TestCase
{
    public function test_can_eager_load_relationship_as_a_string()
    {
        $request = Request::create('/url?include=comments');
        $includes = new QueryParamBag($request, 'include');
        $query = Post::query();

        (new QueryHelper($query, $includes))->exact('comments');

        $this->assertArrayHasKey('comments', $query->getEagerLoads());
    }

    public function test_can_eager_load_relationship_as_an_array()
    {
        $request = Request::create('/url?include=comments,tags');
        $includes = new QueryParamBag($request, 'include');
        $query = Post::query();

        (new QueryHelper($query, $includes))->exact([
            'comments',
            'tags',
        ]);

        $this->assertArrayHasKey('comments', $query->getEagerLoads());
        $this->assertArrayHasKey('tags', $query->getEagerLoads());
    }

    public function test_can_eager_load_relationship_by_alias_as_a_string()
    {
        $request = Request::create('/url?include=author');
        $includes = new QueryParamBag($request, 'include');
        $query = Post::query();

        (new QueryHelper($query, $includes))->alias('author', 'user');

        $this->assertArrayHasKey('user', $query->getEagerLoads());
    }

    public function test_can_eager_load_relationship_by_alias_as_an_associative_array()
    {
        $request = Request::create('/url?include=author');
        $includes = new QueryParamBag($request, 'include');
        $query = Post::query();

        (new QueryHelper($query, $includes))->alias([
            'author' => 'user',
        ]);

        $this->assertArrayHasKey('user', $query->getEagerLoads());
    }

    public function test_can_eager_load_relationship_by_alias_as_an_associative_array_with_closure_as_value()
    {
        $request = Request::create('/url?include=author,comments:limit(4)');
        $includes = new QueryParamBag($request, 'include');
        $query = Post::query();

        (new QueryHelper($query, $includes))->alias([
            'author' => 'user',
            'comments' => function ($query) use ($includes) {
                $query->with(['comments' => function ($query) use ($includes) {
                    $query
                        ->when($includes->has('comments.limit'), function ($query) use ($includes) {
                            list($limit) = $includes->get('comments.limit');
                            $query->limit($limit);
                        });
                }]);
            },
        ]);

        $this->assertArrayHasKey('user', $query->getEagerLoads());
        $this->assertArrayHasKey('comments', $query->getEagerLoads());
    }

    public function test_can_eager_load_count_of_relationship_as_a_string()
    {
        $request = Request::create('/url?include=comments_count');
        $includes = new QueryParamBag($request, 'include');
        $query = Post::query();

        (new QueryHelper($query, $includes))->countExact('comments_count');

        DB::enableQueryLog();

        $query->get();

        $exicutedQueries = DB::getQueryLog();

        DB::disableQueryLog();

        $expectedQuery = 'select "posts".*, '.
            '(select count(*) from "comments" where "posts"."id" = "comments"."post_id") as "comments_count" '.
            'from "posts"';

        $this->assertCount(1, $exicutedQueries);
        $this->assertEquals($expectedQuery, $exicutedQueries[0]['query']);
    }

    public function test_can_eager_load_count_of_relationship_as_an_array()
    {
        $request = Request::create('/url?include=comments_count,tags_count');
        $includes = new QueryParamBag($request, 'include');
        $query = Post::query();

        (new QueryHelper($query, $includes))->countExact([
            'comments_count',
            'tags_count',
        ]);

        DB::enableQueryLog();

        $query->get();

        $exicutedQueries = DB::getQueryLog();

        DB::disableQueryLog();

        $expectedQuery = 'select "posts".*, '.
            '(select count(*) from "comments" where "posts"."id" = "comments"."post_id") as "comments_count", '.
            '(select count(*) from "tags" inner join "post_tag" on "tags"."id" = "post_tag"."tag_id" where "posts"."id" = "post_tag"."post_id") as "tags_count" '.
            'from "posts"';

        $this->assertCount(1, $exicutedQueries);
        $this->assertEquals($expectedQuery, $exicutedQueries[0]['query']);
    }

    public function test_can_eager_load_count_of_relationship_as_an_associative_array_with_include_relationship()
    {
        $request = Request::create('/url?include=response_count');
        $includes = new QueryParamBag($request, 'include');
        $query = Post::query();

        (new QueryHelper($query, $includes))->countAlias([
            'response_count' => 'comments',
        ]);

        DB::enableQueryLog();

        $query->get();

        $exicutedQueries = DB::getQueryLog();

        DB::disableQueryLog();

        $expectedQuery = 'select "posts".*, '.
            '(select count(*) from "comments" where "posts"."id" = "comments"."post_id") as "comments_count" '.
            'from "posts"';

        $this->assertCount(1, $exicutedQueries);
        $this->assertEquals($expectedQuery, $exicutedQueries[0]['query']);
    }

    public function test_countAlias_does_not_accept_closure_as_value()
    {
        $request = Request::create('/url?include=foo_comments_count');
        $includes = new QueryParamBag($request, 'include');
        $query = Post::query();

        $this->expectException(\InvalidArgumentException::class);

        (new QueryHelper($query, $includes))->countAlias([
            'foo_comments_count' => function ($query) {
                $query->where('content', 'like', 'foo%');
            },
        ]);
    }
}

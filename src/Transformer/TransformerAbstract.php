<?php

declare(strict_types=1);

namespace Sarala\Transformer;

use Illuminate\Support\Arr;
use League\Fractal\TransformerAbstract as BaseTransformerAbstract;
use Sarala\Links;
use Sarala\Query\Fields;

abstract class TransformerAbstract extends BaseTransformerAbstract
{
    const REQUIRED_FIELDS = [
        'id',
    ];

    public function transform($data): array
    {
        $user = auth(config('sarala.guard'))->user();

        $links = $this->links($data, $user)->all();
        $meta = $this->meta($data, $user);
        $data = $this->filterFields($this->data($data));

        if (! empty($links)) {
            $data['links'] = $links;
        }

        if (! empty($meta)) {
            $data['meta'] = $meta;
        }

        return $data;
    }

    protected function links($model, $user = null): Links
    {
        return Links::make();
    }

    protected function meta($model, $user = null): array
    {
        return [];
    }

    private function filterFields(array $data): array
    {
        $resourceName = $this->getCurrentScope()->getResource()->getResourceKey();
        /** @var Fields $fields */
        $fields = request()->fields();

        if (! $fields->has($resourceName)) {
            return $data;
        }

        $fields = array_merge($fields->get($resourceName), self::REQUIRED_FIELDS);

        return Arr::only($data, $fields);
    }
}

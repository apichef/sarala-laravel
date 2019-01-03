<?php

declare(strict_types=1);

namespace Sarala\Dummy\Transformers;

use Sarala\Transformer\TransformerAbstract;

class TagTransformer extends TransformerAbstract
{
    public function data($tag): array
    {
        return [
            'id' => (int) $tag->id,
            'name' => $tag->name,
        ];
    }

    public function links($data): array
    {
        return [];
    }
}

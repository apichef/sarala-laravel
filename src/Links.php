<?php

declare(strict_types=1);

namespace Sarala;

class Links
{
    private $links = [];

    public static function make()
    {
        return new self();
    }

    public function push(Link $link)
    {
        $this->links[$link->name()] = $link->data();

        return $this;
    }

    public function when($value, Link $link, Link $default = null)
    {
        if ($value) {
            $this->push($link);
        } elseif ($default) {
            $this->push($default);
        }

        return $this;
    }

    public function all(): array
    {
        return $this->links;
    }
}

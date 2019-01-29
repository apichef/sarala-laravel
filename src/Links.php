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

    public function push($link)
    {
        if ($link instanceof \Closure) {
            $link = $link();
        }

        if (! $link instanceof Link) {
            throw new \InvalidArgumentException(
                'push() method expects an instance of '.Link::class.' or a closure returns a '.Link::class.'. '.gettype($link).' given'
            );
        }

        $this->links[$link->name()] = $link->data();

        return $this;
    }

    public function when($value, $link, $default = null)
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

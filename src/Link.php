<?php

declare(strict_types=1);

namespace Sarala;

class Link
{
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';
    const METHOD_PATCH = 'patch';
    const METHOD_DELETE = 'delete';

    private string $name;

    private string $url;

    private array $meta = [];

    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    public static function make(string $name, string $url): self
    {
        return new self($name, $url);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function post(): self
    {
        return $this->setMethod(self::METHOD_POST);
    }

    public function put(): self
    {
        return $this->setMethod(self::METHOD_PUT);
    }

    public function patch(): self
    {
        return $this->setMethod(self::METHOD_PATCH);
    }

    public function delete(): self
    {
        return $this->setMethod(self::METHOD_DELETE);
    }

    private function setMethod(string $method): self
    {
        return $this->meta('method', $method);
    }

    public function meta(string $key, $value): self
    {
        $this->meta[$key] = $value;

        return $this;
    }

    public function setData(array $data): self
    {
        return $this->meta('data', $data);
    }

    /**
     * @return array|string
     */
    public function data()
    {
        if (empty($this->meta)) {
            return $this->url;
        }

        return [
            'href' => $this->url,
            'meta' => $this->meta,
        ];
    }
}

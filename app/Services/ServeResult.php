<?php
namespace App\Services;

use Illuminate\Support\Collection;

final class ServeResult
{
    private $errors = [];

    private $data;

    public static function make(array $errors = [], $data = null): ServeResult
    {
        return new static($errors, $data);
    }

    public function __construct(array $errors = [], $data = null)
    {
        $this->errors = new Collection($errors);
        $this->data = $data;
    }

    public function hasErrors(): bool
    {
        return !$this->errors->isEmpty();
    }

    public function getErrors(): Collection
    {
        return $this->errors;
    }

    public function getError(): string
    {
        return is_array($this->errors->first())? $this->errors->first()[0]: $this->errors->first();
    }

    public function getData()
    {
        return $this->data;
    }
}

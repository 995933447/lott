<?php
namespace App\Services\Issue\Tasks\IssueManagers;

use ArrayAccess;
use Countable;
use Iterator;

class Issuers implements ArrayAccess, Countable, Iterator
{
    private $position = 0;
    private $fetchedIssues = [];

    public function add(Issuer $issue)
    {
        $this->fetchedIssues[] = $issue;
        usort($this->fetchedIssues, function ($issue1, $issue2) {
            return $issue1->issue > $issue2->issue? -1: 1;
        });
    }

    public function toArray(): array
    {
        return $this->fetchedIssues;
    }

    public function count(): int
    {
        return count($this->fetchedIssues);
    }

    public function offsetGet($offset)
    {
        return $this->toArray()[$offset];
    }

    public function offsetExists($offset)
    {
        return isset($this->toArray()[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            return $this->add($offset, $value);
        } else {
            $this->fetchedIssues[$offset] = $value;
        }
    }

    public function offsetUnset($offset) {
        unset($this->fetchedIssues[$offset]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->fetchedIssues[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->fetchedIssues[$this->position]);
    }
}

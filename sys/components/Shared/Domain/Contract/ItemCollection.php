<?php

declare(strict_types=1);

namespace app\components\Shared\Domain\Contract;

use ArrayObject;

/**
 * @extends ArrayObject<null|int, mixed>
 */
class ItemCollection extends ArrayObject
{
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->validate($item);
        }
        parent::__construct($items);
    }

    public function append($value): void
    {
        $this->validate($value);
        parent::append($value);
    }

    public function offsetSet($key, $value): void
    {
        $this->validate($value);
        parent::offsetSet($key, $value);
    }

    /** $return array<null|int, mixed> */
    public function toArray(): array
    {
        $res = [];
        foreach ($this as $k => $v) {
            $res[$k] = $v;
        }
        return $res;
    }

    // override in child 
    protected function validate($value): void
    {
        // if (!($value instanceof Task)) {
        //      throw new InvalidArgumentException('Not an instance of ...');
        // }
    }


    /*
    protected array $items = [];

    public function __construct(...$items)
    {
        foreach ($items as $item) {
            $this->validate($item);
        }
        $this->items = $items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function append($value): void
    {
        $this->validate($value);
        $this->items[] = $value;
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->items);
    }

    // [i]=, []=
    public function offsetSet($key, $value): void
    {
        $this->validate($value);
        if (is_null($key)) {
            $key = $this->count();
        }
        $this->items[$key] = $value;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    // override in child 
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    // override in child 
    protected function validate($value): void
    {

        // if (!($value instanceof Task)) {
        //      throw new InvalidArgumentException('Not an instance of ...');
        // }
    }
    */
}

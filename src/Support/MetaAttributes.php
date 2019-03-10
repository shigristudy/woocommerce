<?php

namespace Corcel\WooCommerce\Support;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Meta Attributes
 *
 * @package Corcel\WooCommerce\Support
 * @author Krzysztof Grabania <krzysztof@grabania.pl>
 */
abstract class MetaAttributes implements Arrayable, ArrayAccess
{
    /**
     * Instance of meta collection
     *
     * @var  \Corcel\Model\Collection\MetaCollection
     */
    protected $meta;

    /**
     * List of instance attributes.
     *
     * @var  array
     */
    protected $attributes = [];

    /**
     * Fill the instance with an array of attributes.
     *
     * @return  void
     */
    public function parseMeta()
    {
        foreach ($this->metaKeys() as $key) {
            $this->attributes[$key] = $this->metaValue($key);
        }
    }

    /**
     * Get the list of meta keys.
     *
     * @return  array
     */
    public function metaKeys()
    {
        return [];
    }

    /**
     * Get the formatted meta key.
     *
     * @param   string  $key
     * @return  string|null
     */
    public function metaKey($key)
    {
        return $key;
    }

    /**
     * Get the meta value.
     *
     * @param   string  $key
     * @return  mixed
     */
    public function metaValue($key)
    {
        $metaKey = $this->metaKey($key);

        if (!empty($metaKey)) {
            return $this->meta->$metaKey;
        }
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Dynamically retrieve attributes of the instance.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * Dynamically set attributes on the instance.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Determine if an attribute exists on the instance.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset an attribute on the instance.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->attributes);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (array_key_exists($offset, $this->attributes)) {
            return $this->attributes[$offset];
        }
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$offset] = $value;
        }
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }
}

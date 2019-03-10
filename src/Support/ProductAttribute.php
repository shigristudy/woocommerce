<?php

namespace Corcel\WooCommerce\Support;

use ArrayAccess;
use Corcel\WooCommerce\Model\Product;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class ProductAttribute implements Arrayable, ArrayAccess
{
    protected $product;

    protected $taxonomies;

    protected $attribute;

    protected $data;

    public function __construct(Product $product, Collection $taxonomies, array $attribute)
    {
        $this->product    = $product;
        $this->taxonomies = $taxonomies;
        $this->attribute  = (object) $attribute;

        $this->data = $this->formatData();
    }

    protected function formatData()
    {
        return [
            'name'         => $this->name(),
            'slug'         => $this->slug(),
            'taxonomy'     => $this->taxonomy(),
            'values'       => $this->values(),
            'is_visible'   => $this->isVisible(),
            'is_variation' => $this->isVariation(),
            'is_taxonomy'  => $this->isTaxonomy(),
        ];
    }

    public function name()
    {
        if ($this->isTaxonomy()) {
            if ($attributeTaxonomy = $this->taxonomies->get($this->slug())) {
                return $attributeTaxonomy;
            }
        }

        return $this->slug();
    }

    public function slug()
    {
        if (!isset($this->attribute->name)) {
            return;
        }

        $slug = $this->attribute->name;

        return substr($slug, 0, 3) == 'pa_' ? substr($slug, 3) : $slug;
    }

    public function taxonomy()
    {
        if ($this->isTaxonomy() && isset($this->attribute->name)) {
            return $this->attribute->name;
        }
    }

    public function values()
    {
        if ($this->isTaxonomy()) {
            return $this->product->productAttributes
                ->where('taxonomy', $this->taxonomy())
                ->pluck('term')
                ->pluck('name');
        }

        if (!isset($this->attribute->value)) {
            return new Collection();
        }

        return Collection::make(explode('|', $this->attribute->value))->map(function ($value) {
            return trim($value);
        });
    }

    public function isVisible()
    {
        if (isset($this->attribute->is_visible)) {
            return (bool) $this->attribute->is_visible;
        }
    }

    public function isVariation()
    {
        if (isset($this->attribute->is_variation)) {
            return (bool) $this->attribute->is_variation;
        }
    }

    public function isTaxonomy()
    {
        if (isset($this->attribute->is_taxonomy)) {
            return (bool) $this->attribute->is_taxonomy;
        }
    }

    public function toArray()
    {
        return $this->data;
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
        return array_key_exists($offset, $this->data);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (array_key_exists($offset, $this->data)) {
            return $this->data[$offset];
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
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
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
        unset($this->data[$offset]);
    }
}

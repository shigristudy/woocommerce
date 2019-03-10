<?php

namespace Corcel\WooCommerce\Model;

use Corcel\Concerns\Aliases;
use Corcel\Model\Attachment;
use Corcel\Model\Post;
use Corcel\Model\Taxonomy;
use Corcel\WooCommerce\Model\Product\Attribute;
use Corcel\WooCommerce\Model\Product\Category;
use Corcel\WooCommerce\Model\Product\Tag;
use Corcel\WooCommerce\Model\Product\Type;
use Corcel\WooCommerce\Support\ProductAttribute;
use Corcel\WooCommerce\Support\ProductPrice;
use Illuminate\Support\Collection;

class Product extends Post
{
    use Aliases;

    protected static $attributeTaxonomies;

    protected static $aliases = [
        'id'            => 'ID',
        'author_id'     => 'post_author',
        'parent_id'     => 'post_parent',
        'content'       => 'post_content',
        'name'          => 'post_title',
        'slug'          => 'post_name',
        'excerpt'       => 'post_excerpt',
        'status'        => 'post_status',
        'price'         => ['meta' => '_price'],
        'regular_price' => ['meta' => '_regular_price'],
        'sale_price'    => ['meta' => '_sale_price'],
        'sku'           => ['meta' => '_sku'],
        'tax_status'    => ['meta' => '_tax_status'],
        'weight'        => ['meta' => '_weight'],
        'length'        => ['meta' => '_length'],
        'width'         => ['meta' => '_width'],
        'height'        => ['meta' => '_height'],
        'stock'         => ['meta' => '_stock'],
    ];

    protected $appends = [
        'id',
        'author_id',
        'parent_id',
        'name',
        'slug',
        'content',
        'excerpt',
        'status',
        'price',
        'regular_price',
        'sale_price',
        'sku',
        'tax_status',
        'weight',
        'length',
        'width',
        'height',
        'virtual',
        'downloadable',
        'stock',
        'in_stock',
        'type',
        'created_at',
        'updated_at',
        'attributes',
    ];

    protected $hidden = [
        'meta',
        'productAttributes',
        'productType',
    ];

    protected $with = [
        'productAttributes',
        'productType',
    ];

    protected $postType = 'product';

    protected static function boot()
    {
        parent::boot();

        if (empty(static::$attributeTaxonomies)) {
            static::$attributeTaxonomies = Attribute::pluck('attribute_label', 'attribute_name');
        }
    }

    public function categories()
    {
        return $this->belongsToManyTaxonomies(Category::class);
    }

    protected function productAttributes()
    {
        return $this->belongsToManyTaxonomies(Taxonomy::class)->where('taxonomy', 'like', 'pa_%');
    }

    protected function productType()
    {
        return $this->belongsToManyTaxonomies(Type::class);
    }

    public function tags()
    {
        return $this->belongsToManyTaxonomies(Tag::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'post_parent');
    }

    protected function belongsToManyTaxonomies($class)
    {
        return $this->belongsToMany($class, 'term_relationships', 'object_id', 'term_taxonomy_id');
    }

    protected function getAttributesAttribute()
    {
        $attributes = $this->unserializeData($this->meta->_product_attributes);

        if (!is_array($attributes)) {
            $attributes = [];
        }

        return Collection::make($attributes)->map(function ($attribute) {
            return new ProductAttribute($this, static::$attributeTaxonomies, $attribute);
        });
    }

    public function getChildrenAttribute()
    {
        $children = optional($this->meta->first(function ($meta) {
            return $meta->meta_key === '_children';
        }))->value;

        if (empty($children) || !is_array($children)) {
            return;
        }

        return $this->newQuery()
            ->whereIn('ID', $children)
            ->get();
    }

    public function getCrosssellsAttribute()
    {
        $ids = $this->unserializeData($this->meta->_crosssell_ids);

        if (!is_array($ids) || empty($ids)) {
            return new Collection();
        }

        return static::whereIn('ID', $ids)->get();
    }

    protected function unserializeData($data)
    {
        if (!is_string($data) || empty($data)) {
            return false;
        }

        return unserialize($data, ['allowed_classes' => false]);
    }

    public function getDownloadableAttribute()
    {
        return 'yes' === $this->meta->_downloadable;
    }

    public function getGalleryAttribute()
    {
        $gallery = Collection::make([$this->thumbnail->attachment]);
        $ids     = array_filter(explode(',', $this->meta->_product_image_gallery));

        if (!is_array($ids) || empty($ids)) {
            return $gallery;
        }

        $attachments = Attachment::whereIn('ID', $ids)->get();

        return $gallery->merge($attachments);
    }

    public function getFormattedPriceAttribute()
    {
        return new ProductPrice($this);
    }

    public function getInStockAttribute()
    {
        return $this->meta->_stock_status === 'instock';
    }

    public function getOnSaleAttribute()
    {
        return (!empty($this->sale_price) || is_numeric($this->sale_price)) && $this->sale_price < $this->regular_price;
    }

    public function getManageStockAttribute()
    {
        return $this->meta->_manage_stock === 'yes';
    }

    protected function getTypeAttribute()
    {
        $type = $this->productType->first();

        if (!empty($type)) {
            return $type->term->name;
        }
    }

    public function getUpsellsAttribute()
    {
        $ids = $this->unserializeData($this->meta->_upsell_ids);

        if (!is_array($ids) || empty($ids)) {
            return new Collection();
        }

        return static::whereIn('ID', $ids)->get();
    }

    public function getVirtualAttribute()
    {
        return 'yes' === $this->meta->_virtual;
    }

    public function isTaxable()
    {
        return 'taxable' === $this->tax_status;
    }
}

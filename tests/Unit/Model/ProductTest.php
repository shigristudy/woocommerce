<?php

namespace Corcel\WooCommerce\Tests\Unit\Model;

use Corcel\Model\Taxonomy;
use Corcel\WooCommerce\Model\Product;
use Corcel\WooCommerce\Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_it_has_related_categories()
    {
        $product    = factory(Product::class)->create();
        $categories = factory(Taxonomy::class, 5)->states('product_category')->create();

        $product->categories()->attach($categories->pluck('term_taxonomy_id'));

        dd($product->toArray());

        $this->assertCount(5, $product->categories);
        $this->assertInstanceOf(Taxonomy::class, $product->categories->first());
    }

    // public function test_it_has_related_product_attributes()
    // {
    //     $product    = factory(Product::class)->create();
    //     $attributes = factory(Taxonomy::class, 4)->states('product_attribute')->create();

    //     $product->productAttributes()->attach($attributes->pluck('term_taxonomy_id'));

    //     $this->assertCount(4, $product->productAttributes);
    //     $this->assertInstanceOf(Taxonomy::class, $product->productAttributes->first());
    // }


    public function test_it_has_related_tags()
    {
        $product    = factory(Product::class)->create();
        $attributes = factory(Taxonomy::class, 4)->states('tag')->create();

        $product->tags()->attach($attributes->pluck('term_taxonomy_id'));

        $this->assertCount(4, $product->productAttributes);
        $this->assertInstanceOf(Taxonomy::class, $product->productAttributes->first());
    }
}

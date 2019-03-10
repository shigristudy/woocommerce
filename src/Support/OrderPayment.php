<?php

namespace Corcel\WooCommerce\Support;

use Corcel\Model\Collection\MetaCollection as Meta;

/**
 * Class Order Payment
 *
 * @package Corcel\WooCommerce\Support
 * @author Krzysztof Grabania <krzysztof@grabania.pl>
 */
class OrderPayment extends MetaAttributes
{
    /**
     * Instance of meta collection.
     *
     * @var  \Corcel\Model\Collection\MetaCollection
     */
    protected $meta;

    /**
     * Create order payment instance.
     *
     * @param  \Corcel\Model\Collection\MetaCollection  $meta
     */
    public function __construct(Meta $meta)
    {
        $this->meta = $meta;

        $this->parseMeta();
    }

    /**
     * Get the list of meta keys.
     *
     * @return  array
     */
    public function metaKeys()
    {
        return [
            'method',
            'title',
            'transaction_id',
        ];
    }

    /**
     * Get the prefixed meta key.
     *
     * @param   string  $key
     *
     * @return  string
     */
    public function metaKey($key)
    {
        $map = [
            'method'         => '_payment_method',
            'title'          => '_payment_method_title',
            'transaction_id' => '_transaction_id',
        ];

        return isset($map[$key]) ? $map[$key] : null;
    }
}

<?php

namespace Corcel\WooCommerce\Support;

use Corcel\Model;
use Corcel\Model\Collection\MetaCollection as Meta;

/**
 * Class Address
 *
 * @package Corcel\WooCommerce\Support
 * @author Krzysztof Grabania <krzysztof@grabania.pl>
 */
class Address extends MetaAttributes
{
    const BILLING  = 'billing';
    const SHIPPING = 'shipping';

    /**
     * List of base meta keys.
     *
     * @var  array
     */
    protected $baseMetaKeys = [
        'first_name',
        'last_name',
        'company',
        'address_1',
        'address_2',
        'city',
        'state',
        'postcode',
        'country',
    ];

    /**
     * Name of the related model class.
     *
     * @var  string
     */
    protected $modelClass;

    /**
     * Type of the address.
     *
     * @var  string
     */
    protected $type;

    /**
     * Instance of meta collection.
     *
     * @var  \Corcel\Model\Collection\MetaCollection
     */
    protected $meta;

    /**
     * Create address instance.
     *
     * @param  \Corcel\Model  $model
     * @param  \Corcel\Model\Collection\MetaCollection  $meta
     * @param  string  $type
     */
    public function __construct(Model $model, Meta $meta, $type)
    {
        $this->modelClass = class_basename($model);

        $this->meta = $meta;
        $this->type = $type;

        $this->parseMeta();
    }

    /**
     * Get the list of meta keys based on address type.
     *
     * @return  array
     */
    public function metaKeys()
    {
        if ($this->type === static::SHIPPING) {
            return $this->baseMetaKeys;
        }

        if ($this->type === static::BILLING) {
            return array_merge($this->baseMetaKeys, ['email', 'phone']);
        }

        return [];
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
        switch ($this->modelClass) {
            case 'Customer':
                $format = '%s_%s';
                break;
            case 'Order':
            default:
                $format = '_%s_%s';
        }

        return sprintf($format, $this->type, $key);
    }
}

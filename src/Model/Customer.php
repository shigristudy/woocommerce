<?php

namespace Corcel\WooCommerce\Model;

use Corcel\Concerns\Aliases;
use Corcel\Model\User;
use Corcel\WooCommerce\Model\Order;
use Corcel\WooCommerce\Traits\HasAddresses;

/**
 * Class Customer
 *
 * @package Corcel\WooCommerce\Model
 * @author Krzysztof Grabania <krzysztof@grabania.pl>
 */
class Customer extends User
{
    use Aliases;
    use HasAddresses;

    /**
     * The aliases of model's properties or meta values.
     *
     * @var array
     */
    protected static $aliases = [
        'id' => 'ID',
    ];

    /**
     * The accessors to append to the customer's array form.
     *
     * @var array
     */
    protected $appends = [
        'id',
        'login',
        'email',
        'slug',
        'url',
        'nickname',
        'first_name',
        'last_name',
        'avatar',
        'created_at',
        'billing_address',
        'shipping_address',
    ];

    /**
     * List of hidden attributes.
     *
     * This list contains all attributes that have "user" word in its name.
     * Customers are special type of user, so it should not show anything
     * related to users.
     *
     * @var  array
     */
    protected $hidden = [
        'ID',
        'user_login',
        'user_pass',
        'user_nicename',
        'user_email',
        'user_url',
        'user_registered',
        'user_activation_key',
        'user_status',
        'display_name',
    ];

    /**
     * Get the orders collection.
     *
     * @return  \Illuminate\Database\Eloquent\Collection|null
     */
    protected function getOrdersAttribute() {
        return Order::hasMeta('_customer_user', $this->ID)->get();
    }
}

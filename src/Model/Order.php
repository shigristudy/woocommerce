<?php

namespace Corcel\WooCommerce\Model;

use Corcel\Concerns\Aliases;
use Corcel\Model\Meta\PostMeta;
use Corcel\Model\Post;
use Corcel\Model\User as Author;
use Corcel\WooCommerce\Model\Builder\OrderBuilder;
use Corcel\WooCommerce\Model\Note;
use Corcel\WooCommerce\Support\OrderPayment as Payment;
use Corcel\WooCommerce\Support\OrderStatus as Status;
use Corcel\WooCommerce\Traits\HasAddresses;
use Illuminate\Support\Carbon;

class Order extends Post
{
    use Aliases;
    use HasAddresses;

    /**
     * The aliases of model's properties or meta values.
     *
     * @var array
     */
    protected static $aliases = [
        'id'             => 'ID',
        'author_id'      => 'post_author',
        'parent_id'      => 'post_parent',
        'currency'       => ['meta' => '_order_currency'],
        'customer_id'    => ['meta' => '_customer_user'],
        'customer_note'  => 'post_excerpt',
        'created_at_gmt' => 'post_date_gmt',
        'updated_at_gmt' => 'post_modified_gmt',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'id',
        'parent_id',
        'customer_id',
        'customer_note',
        'status',
        'currency',
        'created_at',
        'updated_at',
        'completed_at',
        'paid_at',
        'billing_address',
        'shipping_address',
        'payment',
    ];

    /**
     * List of hidden attributes.
     *
     * This list contains all attributes that have "post" word in its name.
     * Orders are special type of post, so it should not show anything
     * related to posts.
     *
     * @var  array
     */
    protected $hidden = [
        'ID',
        'post_author',
        'post_content',
        'post_date',
        'post_date_gmt',
        'post_modified',
        'post_modified_gmt',
        'post_title',
        'post_excerpt',
        'post_parent',
        'post_status',
        'post_type',
        'comment_count',
        'comment_status',
        'ping_status',
        'post_password',
        'post_name',
        'to_ping',
        'pinged',
        'post_content_filtered',
        'guid',
        'menu_order',
        'post_mime_type',
    ];

    /**
     * The type of post in posts table.
     *
     * @var  string
     */
    protected $postType = 'shop_order';

    /**
     * Get the completed at attribute.
     *
     * @return  \Illuminate\Support\Carbon
     */
    protected function getCompletedAtAttribute()
    {
        return Carbon::createFromTimestamp($this->meta->_date_completed);
    }

    /**
     * Get the customer instance.
     *
     * @return  \Corcel\WooCommerce\Model\Customer|null
     */
    protected function getCustomerAttribute()
    {
        return Customer::find($this->meta->_customer_user);
    }

    /**
     * Get the paid at attribute.
     *
     * @return  \Illuminate\Support\Carbon
     */
    protected function getPaidAtAttribute()
    {
        return Carbon::createFromTimestamp($this->meta->_date_paid);
    }

    /**
     * Get the payment instance.
     *
     * @return  \Corcel\WooCommerce\Support\Payment
     */
    protected function getPaymentAttribute()
    {
        return new Payment($this->meta);
    }

    /**
     * Get the status attribute.
     *
     * @return  string|null
     */
    protected function getStatusAttribute()
    {
        return Status::make($this->post_status)->format();
    }

    /**
     * Get the related author.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Author::class, 'post_author');
    }

    /**
     * Get the related items.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'order_id');
    }

    /**
     * Get the related notes.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class, 'comment_post_ID')->where('comment_type', 'order_note');
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Corcel\WooCommerce\Model\Builder\OrderBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new OrderBuilder($query);
    }
}

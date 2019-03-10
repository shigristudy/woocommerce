<?php

namespace Corcel\WooCommerce\Model;

use Corcel\Concerns\Aliases;
use Corcel\Model\Comment;
use Corcel\WooCommerce\Support\DateTime;

class Note extends Comment
{
    use Aliases;

    /**
     * The aliases of model properties or meta values.
     *
     * @var array
     */
    protected static $aliases = [
        'id'                => 'comment_ID',
        'order_id'          => 'comment_post_ID',
        'author'            => 'comment_author',
        'author_email'      => 'comment_author_email',
        'author_url'        => 'comment_author_url',
        'author_ip_address' => 'comment_author_IP',
        'content'           => 'comment_content',
        'created_at'        => 'comment_date',
    ];

    /**
     * The accessors and aliases to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'id',
        'order_id',
        'author',
        'author_email',
        'author_url',
        'author_ip_address',
        'content',
        'created_at',
    ];

    /**
     * List of hidden attributes.
     *
     * This list contains all attributes that have "comment" word in its name.
     * Order notes are special type of comment, so it should not show anything
     * related to comments.
     *
     * @var  array
     */
    protected $hidden = [
        'comment_ID',
        'comment_post_ID',
        'comment_author',
        'comment_author_email',
        'comment_author_url',
        'comment_author_IP',
        'comment_content',
        'comment_karma',
        'comment_approved',
        'comment_agent',
        'comment_type',
        'comment_parent',
    ];

    public function getCreatedAtGmtAttribute()
    {
        return DateTime::make($this->comment_date_gmt);
    }
}

<?php

namespace App\DBAL;

/**
 * In case you need to add new value or remove old value from enum field using migrations:diff
 * simply remove comment for according enum field and then run migrations:diff (on local PC)
 */
class TableLogEnum extends EnumType
{
    public const BLOG = 'Blog';
    public const ROLE = 'Role';
    public const TAG = 'Tag';
    public const STORY = 'Story';
    public const USER = 'User';

    // @see http://symfony.com/doc/current/reference/forms/types/choice.html#example-usage
    protected static $valuesNames = [
        'Blog' => self::BLOG,
        'Role' => self::ROLE,
        'Tag' => self::TAG,
        'Story' => self::STORY,
        'User' => self::USER,
    ];

    // add config to /app/config/config.yml > doctrine > dbal > types
    protected static $name = 'table_log_enum';
}

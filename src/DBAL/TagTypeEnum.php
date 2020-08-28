<?php

namespace App\DBAL;

/**
 * In case you need to add new value or remove old value from enum field using migrations:diff
 * simply remove comment for according enum field and then run migrations:diff (on local PC)
 */
class TagTypeEnum extends EnumType
{
    public const VALUE_DEFAULT = 'DEFAULT';
    public const VALUE_STORY = 'Story';
    public const VALUE_BLOG = 'Blog';

    // @see http://symfony.com/doc/current/reference/forms/types/choice.html#example-usage
    protected static $valuesNames = [
        'DEFAULT' => self::VALUE_DEFAULT,
        'Story' => self::VALUE_STORY,
        'Blog' => self::VALUE_BLOG,
    ];

    // add config to /app/config/config.yml > doctrine > dbal > types
    protected static $name = 'tag_type_enum';
}

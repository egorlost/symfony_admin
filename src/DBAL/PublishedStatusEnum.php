<?php

namespace App\DBAL;

/**
 * In case you need to add new value or remove old value from enum field using migrations:diff
 * simply remove comment for according enum field and then run migrations:diff (on local PC)
 */
class PublishedStatusEnum extends EnumType
{
    public const VALUE_PUBLISHED = 'PUBLISHED';
    public const VALUE_UNPUBLISHED = 'UNPUBLISHED';

    // @see http://symfony.com/doc/current/reference/forms/types/choice.html#example-usage
    protected static $valuesNames = [
        'Опубліковано' => self::VALUE_PUBLISHED,
        'Не опубліковано' => self::VALUE_UNPUBLISHED,
    ];

    // add config to /app/config/config.yml > doctrine > dbal > types
    protected static $name = 'published_status_enum';
}

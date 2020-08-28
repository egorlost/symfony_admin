<?php

namespace App\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * @see http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/mysql-enums.html
 * @see https://github.com/fre5h/DoctrineEnumBundle
 *
 * If you need to add new value or remove old value from enum field using migrations:diff
 * simply remove comment for according enum field and then run migrations:diff (on local PC)
 */
abstract class EnumType extends Type
{
    protected static $valuesNames = array();
    protected static $name;
    protected static $nullable = false;

    /**
     * It returns array of ENUM values
     *
     * @return array
     */
    public static function getValues(): array
    {
        return array_values(static::$valuesNames);
    }

    /**
     * It returns ENUM values names
     *
     * @return array
     */
    public static function getValuesNames(): array
    {
        return static::$valuesNames;
    }

    /**
     * It returns ENUM value name by ENUM value
     *
     * @param string $value
     * @return string|bool
     */
    public static function getValueName($value)
    {
        if (!in_array($value, self::getValues(), true)) {
            return false;
        }

        $valuesNames = array_flip(static::$valuesNames);

        return $valuesNames[$value];
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $values = array_map(function ($val) {
            return "'" . $val . "'";
        }, self::getValues());

        return 'ENUM(' . implode(', ', $values) . ')';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (false === static::$nullable && ($value === null)) {
            throw new \InvalidArgumentException("ENUM field '" . static::$name . "' can't be null.");
        }

        if (($value !== null) && !in_array($value, self::getValues(), true)) {
            throw new \InvalidArgumentException("Invalid '" . static::$name . "' value.");
        }

        return $value;
    }

    public function getName(): string
    {
        return static::$name;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}

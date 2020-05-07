<?php

namespace dozer111\TypeChecker;

class TypeChecker
{

    public const TYPE_STRING = __STRING__;
    public const TYPE_INT = __INTEGER__;
    public const TYPE_BOOL = __BOOLEAN__;
    public const TYPE_DOUBLE = __DOUBLE__;
    public const TYPE_FLOAT = __FLOAT__;
    public const TYPE_OBJECT = __OBJECT__;
    public const TYPE_RESOURCE = __RESOURCE__;
    public const TYPE_ARRAY = __ARRAY__;
    public const TYPE_NULL = __NULL__;
    public const TYPE_NUMERIC = __NUMERIC__;


    /**
     * MultiChecking of value
     *
     * @param $value
     * @param array $types
     * @param bool $nullable
     * @return bool
     * @example
     * $myVal = 'someMyVal';
     * if(TypeChecker::check($myVal,[TypeChecker::TYPE_STRING,TypeChecker::TYPE_NULL])
     * {
     *      doSmth();
     * }
     * =================================================================================================================
     * instead of
     * if(is_string($myVal) || is_null($myVal) .......)
     *
     *
     */
    public static function check($value, array $types, bool $nullable = false): bool
    {
        $valueType = gettype($value);
        $types = self::setupTypes($types, $nullable);

        $check = in_array($valueType, $types);
        if ($check === false && $valueType === self::TYPE_OBJECT) {
            return self::checkObjects($value, $types);
        } elseif ($check === false && in_array(self::TYPE_NUMERIC, $types)) {
            return self::checkNumeric($value);
        }

        return $check;
    }

    /**
     * Checks value, and if it`s not right type, throw exception
     *
     * @param $value
     * @param array $types
     * @return void
     * @example
     * // before
     * $someVal = 123;
     * if(!is_string($someVal) || !is_null($someVal))
     * {
     *      throw new Exception("$someVal must be a string!");
     * }
     *
     * // after
     * $someVal = 123;
     * TypeChecker::check($val,[TypeChecker::TYPE_STRING,TypeChecker::TYPE_NULL]);
     *
     * // .... code here
     * // .... code here
     *
     *
     */
    public static function hardCheck($value, array $types, bool $nullable = false): void
    {
        if (!self::check($value, $types, $nullable)) {
            self::throwHardCheckError();
        }
    }

    public static function checkObject($value, string $className = null, bool $nullable = false): bool
    {
        if ($nullable && is_null($value))
            return true;

        $check = is_object($value);
        if ($className)
            $check = $check && ($value instanceof $className);

        return $check;
    }

    public static function hardCheckObject($value, string $className = null, bool $nullable = false): void
    {
        self::checkObject($value, $className, $nullable) || self::throwHardCheckError();
    }

    public static function hardCheckString($value, bool $nullable = false): void
    {
        self::hardCheck($value, [self::TYPE_STRING], $nullable);
    }

    public static function hardCheckInt($value, bool $nullable = false): void
    {
        self::hardCheck($value, [self::TYPE_INT], $nullable);
    }

    public static function hardCheckBool($value, bool $nullable = false): void
    {
        self::hardCheck($value, [self::TYPE_BOOL], $nullable);
    }

    public static function hardCheckDouble($value, bool $nullable = false): void
    {
        self::hardCheck($value, [self::TYPE_DOUBLE], $nullable);
    }

    /**
     * @see https://www.php.net/manual/en/function.gettype.php#refsect1-function.gettype-returnvalues
     * @param $value
     * @return void
     */
    public static function hardCheckFloat($value, bool $nullable = false): void
    {
        self::hardCheckDouble($value, $nullable);
    }

    public static function hardCheckArray($value, bool $nullable = false): void
    {
        self::hardCheck($value, [self::TYPE_ARRAY], $nullable);
    }

    public static function hardCheckResource($value, bool $nullable = false): void
    {
        self::hardCheck($value, [self::TYPE_RESOURCE], $nullable);
    }

    public static function hardCheckNull($value): void
    {
        self::hardCheck($value, [self::TYPE_NULL]);
    }

    public static function checkNumeric($value, bool $nullable = false): bool
    {
        $condition = is_numeric($value);
        return ($nullable)
            ? $condition || is_null($value)
            : $condition;
    }

    public static function hardCheckNumeric($value, bool $nullable = false): void
    {
        self::hardCheck($value, [self::TYPE_NUMERIC], $nullable);
    }


    protected static function setupTypes(array $types, bool $nullable): array
    {
        return ($nullable)
            ? array_merge($types, [self::TYPE_NULL])
            : $types;
    }

    protected static function checkObjects($value, array $types)
    {
        $valueClassName = get_class($value);
        return in_array($valueClassName, $types) && self::checkObject($value, $valueClassName);
    }

    /**
     * Change it to throw your exception
     * @return void
     */
    protected static function throwHardCheckError()
    {
        throw new \InvalidArgumentException('Value has wrong type');
    }
}
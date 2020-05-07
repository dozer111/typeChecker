<?php

namespace dozer111\TypeChecker;

class TypeChecker
{

    public const TYPE_STRING = 'string';
    public const TYPE_INT = 'integer';
    public const TYPE_BOOL = 'boolean';
    public const TYPE_DOUBLE = 'double';
    public const TYPE_FLOAT = 'float';
    public const TYPE_OBJECT = 'object';
    public const TYPE_RESOURCE = 'resource';
    public const TYPE_ARRAY = 'array';
    public const TYPE_NULL = 'NULL';
    public const TYPE_NUMERIC = 'numeric';


    /**
     * MultiChecking of value
     *
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
     * @param $value
     * @param array $types
     * @return bool
     */
    public static function check($value, array $types): bool
    {
        $valueType = gettype($value);

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
     * @param $value
     * @param array $types
     * @return void
     */
    public static function hardCheck($value, array $types): void
    {
        if (!self::check($value, $types)) {
            self::throwHardCheckError();
        }
    }
    public static function checkObject($value, string $className = null): bool
    {
        $check = is_object($value);
        if ($className)
            $check = $check && ($value instanceof $className);

        return $check;
    }
    public static function hardCheckObject($value, string $className = null): void
    {
        self::checkObject($value, $className) || self::throwHardCheckError();
    }
    public static function hardCheckString($value): void
    {
        self::hardCheck($value, [self::TYPE_STRING]);
    }
    public static function hardCheckInt($value): void
    {
        self::hardCheck($value, [self::TYPE_INT]);
    }
    public static function hardCheckBool($value): void
    {
        self::hardCheck($value, [self::TYPE_BOOL]);
    }
    public static function hardCheckDouble($value): void
    {
        self::hardCheck($value, [self::TYPE_DOUBLE]);
    }
    /**
     * @see https://www.php.net/manual/en/function.gettype.php#refsect1-function.gettype-returnvalues
     * @param $value
     * @return void
     */
    public static function hardCheckFloat($value): void
    {
        self::hardCheckDouble($value);
    }
    public static function hardCheckArray($value): void
    {
        self::hardCheck($value,[self::TYPE_ARRAY]);
    }
    public static function hardCheckResource($value): void
    {
        self::hardCheck($value,[self::TYPE_RESOURCE]);
    }
    public static function hardCheckNull($value): void
    {
        self::hardCheck($value,[self::TYPE_NULL]);
    }
    public static function checkNumeric($value): bool
    {
        return is_numeric($value);
    }
    public static function hardCheckNumeric($value): void
    {
        self::hardCheck($value,[self::TYPE_NUMERIC]);
    }
    /**
     * Change it to throw your exception
     * @return void
     */
    protected static function throwHardCheckError()
    {
        throw new \InvalidArgumentException('Value has wrong type');
    }
    protected static function checkObjects($value, array $types)
    {
        $valueClassName = get_class($value);
        return in_array($valueClassName, $types) && self::checkObject($value, $valueClassName);
    }

}
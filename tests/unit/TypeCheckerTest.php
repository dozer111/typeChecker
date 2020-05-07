<?php

use dozer111\TypeChecker\TypeChecker;

class TypeCheckerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $resource;
    protected $resource2;


    protected function _after()
    {
        $this->closeResources();
    }


    /**
     * @dataProvider checkData
     * @param $value
     * @param $types
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testCheck($value, $types, $nullable, $result)
    {
        $valueIsCorrect = TypeChecker::check($value, $types, $nullable);
        expect($valueIsCorrect)->same($result);
    }

    /**
     * @dataProvider checkData
     * @param $value
     * @param $types
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testHardCheck($value, $types, $nullable, $result)
    {
        if ($result) {
            TypeChecker::hardCheck($value, $types, $nullable);
            $this->addToAssertionCount(1);
        } else {
            $this->setExpectedException();
            TypeChecker::hardCheck($value, $types, $nullable);
        }
    }


    /**
     * @dataProvider checkObjectData
     * @param $value
     * @param $className
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testCheckObject($value, $className, $nullable, $result)
    {
        $isValueCorrect = TypeChecker::checkObject($value, $className, $nullable);
        expect($isValueCorrect)->same($result);
    }


    /**
     * @dataProvider checkObjectData
     * @param $value
     * @param $className
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testHardCheckObject($value, $className, $nullable, $result)
    {
        if ($result) {
            TypeChecker::hardCheckObject($value, $className, $nullable);
            $this->addToAssertionCount(1);
        } else {
            $this->setExpectedException();
            TypeChecker::hardCheckObject($value, $className, $nullable);
        }
    }

    /**
     * @dataProvider checkStringData
     * @param $value
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testHardCheckString($value, $nullable, $result)
    {
        $this->hardTest('String', $value, $nullable, $result);
    }


    /**
     * @dataProvider checkIntData
     * @param $value
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testHardCheckInt($value, $nullable, $result)
    {
        $this->hardTest('Int', $value, $nullable, $result);
    }

    /**
     * @dataProvider checkBoolData
     * @param $value
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testHardCheckBool($value, $nullable, $result)
    {
        $this->hardTest('Bool', $value, $nullable, $result);
    }

    /**
     * @dataProvider checkDoubleData
     * @param $value
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testHardCheckDouble($value, $nullable, $result)
    {
        $this->hardTest('Double', $value, $nullable, $result);
    }

    /**
     * @dataProvider checkFloatData
     * @param $value
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testHardCheckFloat($value, $nullable, $result)
    {
        $this->hardTest('Float', $value, $nullable, $result);
    }

    /**
     * @dataProvider checkNumericData
     * @param $value
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testCheckNumeric($value, $nullable, $result)
    {
        $isNumeric = TypeChecker::checkNumeric($value, $nullable);
        expect($isNumeric)->same($result);
    }

    /**
     * @dataProvider checkNumericData
     * @param $value
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testHardCheckNumeric($value, $nullable, $result)
    {
        $this->hardTest('Numeric', $value, $nullable, $result);
    }

    /**
     * @dataProvider checkArrayData
     * @param $value
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testHardCheckArray($value, $nullable, $result)
    {
        $this->hardTest('Array', $value, $nullable, $result);
    }

    /**
     * @dataProvider checkNullData
     * @param $value
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testHardCheckNull($value, $nullable, $result)
    {
        $this->hardTest('Null', $value, $nullable, $result);
    }

    /**
     * @dataProvider checkResourceData
     * @param $value
     * @param $nullable
     * @param $result
     * @return void
     */
    public function testHardCheckResource($value, $nullable, $result)
    {
        $this->hardTest('Resource', $value, $nullable, $result);
    }


    public function checkData()
    {
        ///value,types,nullable,result
        return [
            '!nullable: 1 type => correct' => [
                123,[__INTEGER__],false,true
            ],
            '!nullable: n types => 1 correct' => [
                'ozzy',[__NULL__,__ARRAY__,DateTime::class,__STRING__],false,true
            ],
            '!nullable: 1 type => 0 correct' => [
                'dodo',[__ARRAY__],false,false
            ],
            '!nullable: n types => 0 correct' => [
                'dodo',[__NULL__,__ARRAY__,DateTime::class],false,false
            ],
            'nullable' => [
                null,[__ARRAY__],true,true
            ],
            '!nullable: numeric' => [123,[__NUMERIC__],false,true],
            '!nullable: numeric 2' => [123,[__NUMERIC__],false,true],
            '!nullable: numeric 3' => [-123,[__NUMERIC__],false,true],
            '!nullable: numeric 4' => [0,[__NUMERIC__],false,true],
            '!nullable: numeric 5' => [1e5,[__NUMERIC__],false,true],
            '!nullable: numeric 6' => [1.23e5,[__NUMERIC__],false,true],
            '!nullable: numeric 7' => ['1e5',[__NUMERIC__],false,true],
            '!nullable: numeric 8' => ['432',[__NUMERIC__],false,true],
            '!nullable: numeric 9' => ['-10',[__NUMERIC__],false,true],
            '!nullable: numeric 10' => ['-10.15',[__NUMERIC__],false,true],

            '!nullable: correct custom object' => [
                new DateTime(),[__INTEGER__,__ARRAY__,DateTime::class],false,true
            ],
            '!nullable: wrong custom object' => [
                new DateTimeZone('Europe/Jersey'),[__INTEGER__,__ARRAY__,DateTime::class],false,false
            ],
        ];
    }

    public function checkResourceData()
    {
        $this->doResources();
        return [
            'resource 1' => [$this->resource, false, true],
            'resource 2' => [$this->resource2, false, true],

            '!resource 1' => [0, false, false],
            '!resource 2' => ['', false, false],
            '!resource 3' => [new DateTime(), false, false],

            'null !nullable' => [null, false, false],
            'null nullable' => [null, true, true],
        ];
    }

    public function checkArrayData()
    {
        return [
            'array 1' => [[], false, true],
            'array 2' => [['asd'], false, true],

            '!array 1' => [0, false, false],
            '!array 2' => ['', false, false],
            '!array 3' => [new DateTime(), false, false],

            'null !nullable' => [null, false, false],
            'null nullable' => [null, true, true],
        ];
    }


    public function checkNullData()
    {
        return [
            'null' => [null, false, true],

            '!null 1' => [0, false, false],
            '!null 2' => ['', false, false],
            '!null 3' => [[], false, false],
            '!null 4' => ['  ', false, false],
            '!null 5' => ['123', false, false],
        ];
    }


    public function checkNumericData()
    {
        return [
            'numeric 1' => [0, false, true],
            'numeric 2' => [213, false, true],
            'numeric 3' => [123.123, false, true],
            'numeric 4' => [15e5, false, true],
            'numeric 5' => [-15e5, false, true],
            'numeric 6' => ['15e5', false, true],
            'numeric 7' => ['-15e5', false, true],
            'numeric 8' => ['213', false, true],
            'numeric 9' => ['-213', false, true],
            'numeric 10' => ['123.123', false, true],
            'numeric 11' => ['-123.123', false, true],

            '!numeric 1' => ['', false, false],
            '!numeric 2' => [' ', false, false],
            '!numeric 3' => ['7x5', false, false],
            '!numeric 4' => [[], false, false],
            '!numeric 5' => ['asd', false, false],
            '!numeric 6' => ['5someTextafter', false, false],
            '!numeric 7' => ['5 someTextafter', false, false],
            '!numeric 8' => [false, false, false],
            '!numeric 9' => [true, false, false],

            'null !nullable' => [null, false, false],
            'null nullable' => [null, true, true],
        ];
    }


    public function checkBoolData()
    {
        return [
            'bool' => [false, false, true],

            '!bool 1' => [0, false, false],
            '!bool 2' => ['', false, false],
            '!bool 3' => [' ', false, false],
            '!bool 4' => [[], false, false],
            '!bool 5' => ['asd', false, false],

            'null !nullable' => [null, false, false],
            'null nullable' => [null, true, true],
        ];
    }

    public function checkFloatData($keys = 'float')
    {
        return [
            $keys => [123.222, false, true],

            "!$keys 1" => [123, false, false],
            "$keys 2" => ['123.222', false, false],
            "!$keys 3" => ['', false, false],
            "$keys 4" => [[123.222], false, false],

            'null !nullable' => [null, false, false],
            'null nullable' => [null, true, true],
        ];
    }

    public function checkDoubleData()
    {
        return $this->checkFloatData('double');
    }


    public function checkStringData()
    {
        return [
            'string' => ['dodo', false, true],

            '!string 1' => [123, false, false],
            '!string 2' => [false, false, false],
            '!string 3' => [new DateTime(), false, false],
            '!string 4' => [['asd'], false, false],

            'null !nullable' => [null, false, false],
            'null nullable' => [null, true, true],
        ];
    }

    public function checkIntData()
    {
        return [
            'int' => [123, false, true],

            '!int 1' => ['123', false, false],
            '!int 2' => [123.12, false, false],
            '!int 3' => ['Dondo', false, false],
            '!int 4' => [['asd'], false, false],

            'null !nullable' => [null, false, false],
            'null nullable' => [null, true, true],
        ];
    }

    public function checkObjectData()
    {
        // obj, className,nullable,methResult
        return [
            'no nullable,check default OBJECT type' => [new DateTime(), null, false, true],
            'no nullable,check CORRECT specific object' => [new DateTime(), DateTime::class, false, true],
            'no nullable,check WRONG specific object' => [new DateTimeZone('UTC'), DateTime::class, false, false],
            'no nullable, check NON-Object data' => ['Ozzy', DateTime::class, false, false],
            'no nullable, check null' => [null, DateTime::class, false, false],

            'NULLABLE,check default OBJECT type' => [new DateTime(), null, true, true],
            'NULLABLE,check CORRECT specific object' => [new DateTime(), DateTime::class, true, true],
            'NULLABLE,check WRONG specific object' => [new DateTimeZone('UTC'), DateTime::class, true, false],
            'NULLABLE, check NON-Object data' => ['Ozzy', DateTime::class, true, false],
            'NULLABLE, check null' => [null, DateTime::class, true, true],
        ];
    }


    protected function closeResources()
    {
        $this->resource && fclose($this->resource);
        $this->resource2 && fclose($this->resource2);
    }

    protected function doResources()
    {
        $this->closeResources();
        $filePath = __DIR__ . '/../_data/text.txt';

        $this->resource = fopen($filePath, 'rb');
        $this->resource2 = tmpfile();
    }

    protected function setExpectedException()
    {
        $this->expectException(InvalidArgumentException::class);
    }

    protected function hardTest($name, $value, $nullable, $result)
    {
        $methName = "hardCheck$name";
        if ($result) {
            TypeChecker::$methName($value, $nullable);
            $this->addToAssertionCount(1);
        } else {
            $this->setExpectedException();
            TypeChecker::$methName($value, $nullable);
        }
    }
}
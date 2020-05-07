<?php

use Codeception\Specify;
use dozer111\TypeChecker\TypeChecker;

class TypeCheckerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    use Specify;

    protected $resource;

    protected function _before()
    {
        $filePath = __DIR__ . '/../_data/text.txt';

        $this->resource = fopen($filePath, 'rb');
    }

    protected function _after()
    {
        fclose($this->resource);
    }


    public function testCheckObject()
    {
        $this->specify('Object/object + value har correct type', function () {
            $obj = new DateTime();
            $checkForObject = TypeChecker::checkObject($obj);
            $checkForObjectReallyInstanceOfClassName = TypeChecker::checkObject($obj, DateTime::class);

            expect($checkForObject)->true();
            expect($checkForObjectReallyInstanceOfClassName)->true();
        });


        $this->specify('Object/object + value har wrong type', function () {
            $wrongObject = new DateTimeZone('UTC');
            $checkForObject = TypeChecker::checkObject($wrongObject);
            $checkForObjectReallyInstanceOfClassName = TypeChecker::checkObject($wrongObject, DateTime::class);

            expect($checkForObject)->true();
            expect($checkForObjectReallyInstanceOfClassName)->false();
        });

        $this->specify('NOT object', function () {
            $wrongObject = 123;
            $checkForObject = TypeChecker::checkObject($wrongObject);
            $checkForObjectReallyInstanceOfClassName = TypeChecker::checkObject($wrongObject, DateTime::class);

            expect($checkForObject)->false();
            expect($checkForObjectReallyInstanceOfClassName)->false();
        });

    }


    public function testHardCheckObjectOnWrightData()
    {
        $this->specify('Object/object + value har correct type', function () {
            $obj = new DateTime();

            TypeChecker::hardCheckObject($obj);
            $this->addToAssertionCount(1);

            TypeChecker::hardCheckObject($obj, DateTime::class);
            $this->addToAssertionCount(1);
        });

    }

    public function testHardCheckObjectOnWrongData1()
    {
        $this->specify('Object/object + value har wrong type', function () {
            $wrongObject = new DateTimeZone('UTC');

            TypeChecker::hardCheckObject($wrongObject);
            $this->addToAssertionCount(1);


            $this->expectException(InvalidArgumentException::class);
            TypeChecker::hardCheckObject($wrongObject, DateTime::class);

        });
    }

    public function testHardCheckObjectOnWrongData2()
    {
        $this->specify('NOT object', function () {
            $wrongObject = 123;

            $this->expectException(InvalidArgumentException::class);
            TypeChecker::hardCheckObject($wrongObject);

        });
    }

    public function testHardCheckObjectOnWrongData3()
    {
        $this->specify('NOT object', function () {
            $wrongObject = 123;


            $this->expectException(InvalidArgumentException::class);
            TypeChecker::hardCheckObject($wrongObject, DateTime::class);

        });
    }

    public function testHardCheckInt()
    {
        $int = 123;
        $wrongInt = '123';

        TypeChecker::hardCheckInt($int);
        $this->addToAssertionCount(1);

        $this->expectException(InvalidArgumentException::class);
        TypeChecker::hardCheckInt($wrongInt);
    }


    public function testHardCheckString()
    {
        $string = '123';
        $wrongString = 123;

        TypeChecker::hardCheckString($string);
        $this->addToAssertionCount(1);

        $this->expectException(InvalidArgumentException::class);
        TypeChecker::hardCheckString($wrongString);
    }

    public function testHardCheckFloat()
    {
        $float = 123.123;
        $wrongFloat = 123;

        TypeChecker::hardCheckFloat($float);
        $this->addToAssertionCount(1);

        $this->expectException(InvalidArgumentException::class);
        TypeChecker::hardCheckFloat($wrongFloat);
    }

    public function testHardCheckDouble()
    {
        $float = 123.123;
        $wrongFloat = 123;

        TypeChecker::hardCheckDouble($float);
        $this->addToAssertionCount(1);

        $this->expectException(InvalidArgumentException::class);
        TypeChecker::hardCheckDouble($wrongFloat);
    }

    public function testHardCheckBool()
    {
        $bool = true;
        $wrongBool = 0;

        TypeChecker::hardCheckBool($bool);
        $this->addToAssertionCount(1);

        $this->expectException(InvalidArgumentException::class);
        TypeChecker::hardCheckBool($wrongBool);
    }

    public function testHardCheckArray()
    {
        $arr = [true];
        $wrongArr = 0;

        TypeChecker::hardCheckArray($arr);
        $this->addToAssertionCount(1);

        $this->expectException(InvalidArgumentException::class);
        TypeChecker::hardCheckArray($wrongArr);
    }

    public function testHardCheckRecourse()
    {
        $resource = $this->resource;
        $wrongRecourse = 0;

        TypeChecker::hardCheckResource($resource);
        $this->addToAssertionCount(1);

        $this->expectException(InvalidArgumentException::class);
        TypeChecker::hardCheckResource($wrongRecourse);
    }

    public function testHardCheckNull()
    {
        $null = null;
        $wrongNull = '';

        TypeChecker::hardCheckNull($null);
        $this->addToAssertionCount(1);

        $this->expectException(InvalidArgumentException::class);
        TypeChecker::hardCheckNull($wrongNull);
    }

    /**
     * @dataProvider numericDataProvider
     * @return void
     */
    public function testCheckNumeric($value,$result)
    {
        expect(TypeChecker::checkNumeric($value))->same($result);
    }

    /**
     * @dataProvider numericDataProvider
     * @return void
     */
    public function testHardCheckNumeric($value,$result)
    {
        if(!$result)
        {
            $this->expectException(InvalidArgumentException::class);
            TypeChecker::hardCheckNumeric($value);
        }
        else{
            TypeChecker::hardCheckNumeric($value);
            $this->addToAssertionCount(1);
        }
    }


    public function numericDataProvider()
    {
        return [
            [123,true],
            [123.123,true],
            [123.0,true],
            [0,true],
            [-15,true],
            ['126',true],
            ['123 123',false],
            ['ozzy',false],
            [false,false],
            [$this->resource,false],
            ['',false],
            [' ',false],
            ['10 niggas ',false],
        ];
    }

}
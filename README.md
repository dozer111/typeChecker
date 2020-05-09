# PHP type checker

`composer require dozer111/type_checker`

Was created, for better and faster checking `$yourValue` data types in imperativeStyle        

---

**Main idea** , that there is no need to write and read after looong checks(which takes our time and energy),
when we can change it to some simple structure, which would do code shorter and readPrettier     

---

Lib has 2 main ways:
* **check**
* **hardCheck/guard** => check, and if false -> throw exception    
```php
TypeChecker::hardCheck($value,[__INTEGER__,__STRING__]);
TypeChecker::hardCheckInt($value,[__INTEGER__,__STRING__]);
TypeChecker::hardCheckString($value,[__INTEGER__,__STRING__]);
// or same, but with synonyms
TypeChecker::guard($value,[__INTEGER__,__STRING__]);
TypeChecker::guardInt($value,[__INTEGER__,__STRING__]);
TypeChecker::guardString($value,[__INTEGER__,__STRING__]);
```



### Example1 => common usage
```php
//before
$value = '<someYourValue>';
if(is_int($value) || is_string($value))
{
    doSmth();
}


// now
$valueHasCorrectType = TypeChecker::check($value,[__INTEGER__,__STRING__]);

//========================================================================================================================
//========================================================================================================================

// before
if(is_int($value) || is_string($value))
{
    throw new SomeYourException();
}

// now hardCheck or guard
TypeChecker::hardCheck($value,[__INTEGER__,__STRING__]);
TypeChecker::guard($value,[__INTEGER__,__STRING__]);
```

### Example2 => value + nullable
TypeChecker has own type `TYPE_NULL`, and you can use this.  
Also, you can use `nullable` argument, instead of adding this to `check()` method:
```php
// add manually
TypeChecker::check($value,[__INTEGER__,__STRING__,__NULL__]);
// or use nullable mechanism
TypeChecker::check($value,[__INTEGER__,__STRING__],true);
```
---

This trick works almost for all methods:
```php
TypeChecker::hardCheckInt($x,true); // null or int
TypeChecker::hardCheckInt($x); // int ONLY!
```

### Example3 => checking Object
* TypeChecker can check object in 2 ways
    * check, that value is just `object` type
    * check, that object really instanceOf your needs

```php
// we can check objects in couple of ways:
// 1 => using TypeChecker::check
TypeChecker::check($value,[__OBJECT__]);
TypeChecker::check($value,[YorClassName::class]);

// 2 => TypeChecker::checkObject()/TypeChecker::hardCheckObject()
TypeChecker::checkObject($value); // will check for `object` type
TypeChecker::checkObject($value,YorClassName::class); // will check for `object` && YorClassName types
TypeChecker::checkObject($value,YorClassName::class,true); // will check for (`object` && YorClassName) or null types
```


### Example4 => change default throw exception
just extends class, and rewrite `throwHardCheckError()`
## 1.0
* Add main functions, + test on them


## 2.0 
* No new methods from 1.0
* Add nullable mechanism
* Add global constants e.g `__<TYPE_NAME>__`
* Rewrite/upgrade tests


## 2.1
* Add `guard` synonyms to `hardCheck` methods:
```
TypeChecker::hardCheckInt(123) => TypeChecker::guardInt(123);
TypeChecker::hardCheckBool(false) => TypeChecker::guardBool(false);
....
```
php-array-utils
===============

A array-function library

```PHP
$input = ['a' => 0, 'b' => 1, 'c' => 2, 'd' => 3];

arr\join($input);
# 0123

arr\join($input, ', ');
# 0, 1, 2, 3

arr\intersect($input, [1, 3]);
# [b => 1, d => 3]

arr\intersect\keys($input, ['a', 'b']);
# [a => 0, b => 1]

arr\diff($input, [1, 2]);
# [a => 0, d => 3]

arr\diff\keys($input, ['a', 'c']);
# [b => 1, d => 3]

arr\filter($input);
# [b => 1, c => 2, d => 3]

arr\filter($input, function ($val) { return $val !== 1; });
# [a => 0, c => 2, d => 3]

arr\filter\keys($input);
# [a => 0, b => 1, c => 2, d => 3]

arr\filter\keys($input, function ($key) { return $key !== 'b'; });
# [a => 0, c => 2, d => 3]

arr\filter\keysAndValues($input, function ($key, $value) { return $value === 0 || $key === 'b'; });
# [a => 0, b => 1]

arr\map($input, 'exp');
# [a => 1, b => 2.718281828459, c => 7.3890560989307, d => 20.085536923188]

arr\map($input, function ($val) { return $val + 1; });
# [a => 1, b => 2, c => 3, d => 4]

arr\map\keys($input, 'strtoupper');
# [A => 0, B => 1, C => 2, D => 3]

arr\map\keys($input, function ($key) { return dechex(ord($key)); });
# [61 => 0, 62 => 1, 63 => 2, 64 => 3]

arr\map\keysAndValues($input, function (&$key, $value) { list($value, $key) = array($key, $value); return $value; });
# [0 => a, 1 => b, 2 => c, 3 => d]

arr\map\func([[255, 128, 0], [128, 255, 0]], 'vsprintf', array('#%02X%02X%02X'), 1);
# [0 => #FF8000, 1 => #80FF00]
```

# FAQ

> Hey, all the cool kids use OOP. Isn't this library pointing in the wrong direction?

It depends either on the situation or the extent in which an array is used. In PHP, arrays are often meat in different situations. At least, $_-variables are arrays. You can immediately wrap an Array into an ArrayObject, but then you can't use many of the features built into PHP directly. So I think I have to explain the advantages and disadvantages of each approach:

Advantage of array-functions:

* You don't have to make an Object out of every array you wan't to apply functionality on.
* You can still use php's built in functions on Arrays without the need to conversion like array_merge().
* You can add as many functions by yourself as you wish. You could so the same with an ArrayObject-descendant, but then you have a god-object with tens or hundreds of methods (Closure-bindung?).
* You can always use the array-typehint and still use the extensions of this library directly.

Disadvantages of array-functions:

* Of you pass an array as a parameter to a function/method, the whole array gets copied.
* You can utilize an inner state that can help to track changes or whatever.
php-array-utils
===============

A array-function library

```PHP
$input = ['a' => 0, 'b' => 1, 'c' => 2, 'd' => 3];

arr\join($input);
> 0123

arr\join($input, ', ');
> 0, 1, 2, 3

arr\intersect($input, [1, 3]);
> b: 1, d: 3

arr\intersectKeys($input, ['a', 'b']);
> a: 0, b: 1

arr\diff($input, [1, 2]);
> a: 0, d: 3

arr\diffKeys($input, ['a', 'c']);
> b: 1, d: 3

arr\filter($input);
> b: 1, c: 2, d: 3

arr\filter($input, function ($val) { return $val !== 1; });
> a: 0, c: 2, d: 3

arr\filterKeys($input);
> a: 0, b: 1, c: 2, d: 3

arr\filterKeys($input, function ($key) { return $key !== 'b'; });
> a: 0, c: 2, d: 3

arr\filterKeysAndValues($input, function ($key, $value) { return $value === 0 || $key === 'b'; });
> a: 0, b: 1

arr\map($input, 'exp');
> a: 1, b: 2.718281828459, c: 7.3890560989307, d: 20.085536923188

arr\map($input, function ($val) { return $val + 1; });
> a: 1, b: 2, c: 3, d: 4

arr\mapKeys($input, 'strtoupper');
> A: 0, B: 1, C: 2, D: 3

arr\mapKeys($input, function ($key) { return dechex(ord($key)); });
> 61: 0, 62: 1, 63: 2, 64: 3

arr\mapKeysAndValues($input, function (&$key, $value) { list($value, $key) = array($key, $value); return $value; });
> 0: a, 1: b, 2: c, 3: d

arr\mapFunc([[255, 128, 0], [128, 255, 0]], 'vsprintf', array('#%02X%02X%02X'), 1);
> 0: #FF8000, 1: #80FF00
```
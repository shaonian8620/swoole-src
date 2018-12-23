--TEST--
swoole_coroutine: error
--SKIPIF--
<?php require __DIR__ . '/../../include/skipif.inc'; ?>
--FILE--
<?php
require __DIR__ . '/../../include/bootstrap.php';
go(function () {
    echo "start\n";
    throw new Error('fatal error');
});
go(function () {
    throw new Exception('exception');
    co::sleep(.001);
    echo "after sleep\n";
});
?>
--EXPECTF--
start

Fatal error: Uncaught Error: fatal error in /Users/twosee/Toast/swoole-src/tests/swoole_coroutine/exception/error.php:5
Stack trace:
#0 {main}
  thrown in /Users/twosee/Toast/swoole-src/tests/swoole_coroutine/exception/error.php on line 5

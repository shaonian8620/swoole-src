--TEST--
swoole_coroutine: throw itself
--SKIPIF--
<?php require __DIR__ . '/../../include/skipif.inc'; ?>
--FILE--
<?php
require __DIR__ . '/../../include/bootstrap.php';
go(function () {
    $ret = co::throw(co::getCid());
    assert($ret === false && swoole_last_error() === SWOOLE_ERROR_CO_NONCANCELABLE_OPERATION);
    echo "DONE\n";
});
?>
--EXPECT--
DONE

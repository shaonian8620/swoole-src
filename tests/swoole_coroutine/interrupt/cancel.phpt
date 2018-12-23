--TEST--
swoole_coroutine: cancel coroutine
--SKIPIF--
<?php require __DIR__ . '/../../include/skipif.inc'; ?>
--FILE--
<?php
require __DIR__ . '/../../include/bootstrap.php';

$sleep = go(function () {
    if (co::sleep(1)) {
        echo "normal termination\n";
    } elseif (co::wasCancelled()) {
        echo "timer was canceled\n";
    } else {
        echo "create timer error\n";
    }
});

$socket_io = go(function () {
    $socket = new  Co\Socket(AF_INET, SOCK_DGRAM, 0);
    if ($socket->recvfrom($peer, 1)) {
        echo "recv from ok\n";
    } elseif (co::wasCancelled()) {
        echo "socket io was canceled\n";
    } else {
        echo "recv from failed\n";
    }
});

$file_io = go(function () {
    assert(file_get_contents(__FILE__) === co::readFile(__FILE__));
});

go(function () use ($sleep, $socket_io, $file_io) {
    echo 'cancel sleep ' . (co::cancel($sleep) ? 'ok' : 'failed') . "\n";
    echo 'cancel socket io ' . (co::cancel($socket_io) ? 'ok' : 'failed') . "\n";
    if (!co::cancel($file_io) && swoole_last_error() === SWOOLE_ERROR_CO_NONCANCELABLE_OPERATION) {
        echo "file io can not be canceled now\n";
    }
});

?>
--EXPECT--
timer was canceled
cancel sleep ok
socket io was canceled
cancel socket io ok
file io can not be canceled now

--TEST--
swoole_coroutine: call_not_exists_func
--SKIPIF--
<?php require __DIR__ . '/../include/skipif.inc'; ?>
--FILE--
<?php
require __DIR__ . '/../include/bootstrap.php';
$pm = new ProcessManager;
$pm->parentFunc = function (int $pid) use ($pm) {
    echo curlGet("http://127.0.0.1:{$pm->getFreePort()}/");
};
$pm->childFunc = function () use ($pm) {
    $http = new swoole_http_server('127.0.0.1', $pm->getFreePort(), SWOOLE_BASE);
    $http->set(['worker_num' => 1]);
    $http->on('workerStart', function () use ($pm) {
        $pm->wakeup();
    });
    $http->on('request', function (swoole_http_request $request, swoole_http_response $response) {
        none();
    });
    $http->start();
};
$pm->childFirst();
$pm->run();
?>
--EXPECTF--
Fatal error: Uncaught Error: Call to undefined function none() in %s/tests/swoole_coroutine/call_not_exists_func.php:14
Stack trace:
#0 {main}
  thrown in %s/tests/swoole_coroutine/call_not_exists_func.php on line 14
[%s]	ERROR	zm_deactivate_swoole (ERROR 503): Fatal error: Uncaught Error: Call to undefined function none() in %s/tests/swoole_coroutine/call_not_exists_func.php:14
Stack trace:
#0 {main}
  thrown in %s/tests/swoole_coroutine/call_not_exists_func.php on line 14.
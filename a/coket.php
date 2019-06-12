<?php
$server = new Swoole\WebSocket\Server("0.0.0.0", 9502);

$server->on('open', function (Swoole\WebSocket\Server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
});

$server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
//    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
//        $server->push($frame->fd,json_encode(['word']));
        //检查当前所有链接  广播消息
    foreach ($server->connections as $fds){
        //需要先判断是否正确websocket 连接,否则有可能会push失败
        if($server->isEstablished($fds)){
            $server->push($fds,$frame->data);
        }
    }
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();
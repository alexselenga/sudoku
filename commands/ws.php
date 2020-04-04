<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__DIR__) . '/vendor/autoload.php';

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $userNames;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $client) {
        $this->clients->attach($client);
    }

    public function onMessage(ConnectionInterface $client, $msg) {
        $data = json_decode($msg);

        if ($data->type == 'setUserName') {
            $this->userNames[$client->resourceId] = $data->userName;
            $this->sendUserNames();
        } else
            $this->sendToAll($client, $msg);
    }

    public function onClose(ConnectionInterface $client) {
        unset($this->userNames[$client->resourceId]);
        $this->clients->detach($client);
        $this->sendUserNames();
    }

    public function onError(ConnectionInterface $client, \Exception $e) {
        $client->close();
    }

    protected function sendToAll($fromClient, $msg) {
        foreach ($this->clients as $client) {
            if ($fromClient !== $client) $client->send($msg);
        }
    }

    protected function sendUserNames() {
        $userNames = [];

        foreach ($this->clients as $client) {
            $userNames[] = $client->userName;
        }

        $msg = json_encode([
            'type' => 'refreshUserNames',
            'userNames' => $this->userNames,
        ]);

        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8089
);

$server->run();
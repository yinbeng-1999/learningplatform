<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chatbar implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "server started\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $count = $this->clients->count() + 1 ;
        $conn->countClient = $count;
        $this->clients->attach($conn);

        foreach ($this->clients as $client) {
            $client->send($count);
        }

        echo "New connection! ({$conn->resourceId})\n" ;
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            // if ($from !== $client) {
            //     // The sender is not the receiver, send to each client connected
                $client->send($msg);
            //}
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $count = $this->clients->count() - 1 ;
        $conn->countClient = $count;
        $this->clients->detach($conn);

        foreach ($this->clients as $client) {
            $client->send($count);
        }

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
?>





<?php

use Predis\Command\CommandInterface;
use Predis\Connection\StreamConnection;

require_once __DIR__.'/../../vendor/autoload.php';

class SimpleDebuggableConnection2 extends StreamConnection
{
    private float $tstart = 0;
    private array $debugBuffer = [];

    public function connect()
    {
        $this->tstart = microtime(true);

        parent::connect();
    }

    private function storeDebug(CommandInterface $command, string $direction)
    {
        $firstArg = $command->getArgument(0);
        $timestamp = round(microtime(true) - $this->tstart, 4);

        $debug = $command->getId();
        $debug.= isset($firstArg) ? " $firstArg " : ' ';
        $debug.= "$direction $this";
        $debug.= " [{$timestamp}s]";

        $this->debugBuffer[] = $debug;
    }

    public function writeRequest(CommandInterface $command)
    {
        parent::writeRequest($command);

        $this->storeDebug($command, '->');
    }

    public function readResponse(CommandInterface $command)
    {
        $this->storeDebug($command, '<-');
        return parent::readResponse($command);
    }

    public function getDebugBuffer(): array
    {
        return $this->debugBuffer;
    }

}


$redis_param_single = [
    'host' => 'redis',
];

$redis_param = [
    [
        'host' => 'redis',
    ],
];

$options = [
    'connections' => [
        'tcp' => 'SimpleDebuggableConnection2',
    ],
    'cluster' => 'redis',
];


$redis = new \Predis\Client($redis_param, $options);
$redis->connect();
$varKey1 = $redis->get('key1');
echo "key1 => $varKey1".PHP_EOL;

$redis->set('foo', 'bar');
$redis->get('foo');
// $redis->info();

// var_export($redis->getConnection()->getDebugBuffer());
// var_export($redis->getConnection());

$connection = $redis->getConnection();
echo "connection class is ".get_class($connection).PHP_EOL;
if ($connection instanceof Traversable) {
    echo 'Traversable class'    .PHP_EOL;
    $cnt = count($connection);
    echo 'cnt:'.$cnt;
    foreach ($connection as $node) {
        echo "node class is ".get_class($node).PHP_EOL;
    }
}
echo 'hello '.__FILE__.PHP_EOL;

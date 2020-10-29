<?php

class NetworkPath
{
    /** @var integer[][] The graph, where $graph[node1][node2]=cost */
    public $graph;

    public $distance;

    public $previous;

    public $queue;

    /**
     * @param integer[][] $graph
     */
    public function __construct($graph)
    {
        $this->graph = $graph;
    }

    /**
     * Process the next (i.e. closest) entry in the queue.
     *
     * @param string[] $exclude A list of nodes to exclude - for calculating next-shortest paths.
     *
     * @return void
     */
    public function processNextNodeInQueue(array $exclude)
    {
        // Process the closest vertex
        $closest = array_search(min($this->queue), $this->queue);
        if (!empty($this->graph[$closest]) && !in_array($closest, $exclude)) {
            foreach ($this->graph[$closest] as $neighbor => $cost) {
                if (isset($this->distance[$neighbor])) {
                    if ($this->distance[$closest] + $cost < $this->distance[$neighbor]) {
                        // A shorter path was found
                        $this->distance[$neighbor] = $this->distance[$closest] + $cost;
                        $this->previous[$neighbor] = array($closest);
                        $this->queue[$neighbor] = $this->distance[$neighbor];
                    } elseif ($this->distance[$closest] + $cost === $this->distance[$neighbor]) {
                        // An equally short path was found
                        $this->previous[$neighbor][] = $closest;
                        $this->queue[$neighbor] = $this->distance[$neighbor];
                    }
                }
            }
        }
        unset($this->queue[$closest]);
    }

    /**
     * Extract all the paths from $source to $target as arrays of nodes.
     *
     * @param string $target The starting node (working backwards)
     *
     * @return string[][] One or more shortest paths, each represented by a list of nodes
     */
    public function extractPaths($target)
    {
        $paths = array(array($target));

        for ($key = 0; isset($paths[$key]); ++$key) {
            $path = $paths[$key];

            if (!empty($this->previous[$path[0]])) {
                foreach ($this->previous[$path[0]] as $previous) {
                    $copy = $path;
                    array_unshift($copy, $previous);
                    $paths[] = $copy;
                }
                unset($paths[$key]);
            }
        }

        return array_values($paths);
    }

    /**
     * Calculate the shortest path through a a graph, from $source to $target.
     *
     * @param string   $source  The starting node
     * @param string   $target  The ending node
     * @param string[] $exclude A list of nodes to exclude - for calculating next-shortest paths.
     *
     * @return string[][] Zero or more shortest paths, each represented by a list of nodes
     */
    public function shortestPaths($source, $target, array $exclude = array())
    {
        // The shortest distance to all nodes starts with infinity...
        $this->distance = array_fill_keys(array_keys($this->graph), INF);
        // ...except the start node
        $this->distance[$source] = 0;

        // The previously visited nodes
        $this->previous = array_fill_keys(array_keys($this->graph), array());

        // Process all nodes in order
        $this->queue = array($source => 0);
        while (!empty($this->queue)) {
            $this->processNextNodeInQueue($exclude);
        }

        if ($source === $target) {
            // A null path
            return array(array($source));
        } elseif (empty($this->previous[$target])) {
            // No path between $source and $target
            return array();
        } else {
            // One or more paths were found between $source and $target
            return $this->extractPaths($target);
        }
    }
}

$array = array_map('str_getcsv', file('csvData.csv'));
$header = array_shift($array);
array_walk($array, '_combine_array', $header);

function _combine_array(&$row, $key, $header)
{
    $row = array_combine($header, $row);
}

foreach ($array as $arrayKey => $value) {
    $firstNode = $value['DeviceFrom'];
    $secondNode = $value['DeviceTo'];
    $matrixArr[$firstNode][$secondNode] = $value['Latency'];
    $matrixArr[$secondNode][$firstNode] = $value['Latency'];
}

$input = fgets(STDIN);
$exploded = explode(" ", $input);
$first_node = $exploded[0];
$second_node = $exploded[1];
$latency_value = $exploded[2];

$algorithm = new NetworkPath($matrixArr);


$path = $algorithm->shortestPaths($first_node, $second_node);
$weight =  $algorithm->distance[$second_node];
$output = ($weight > $latency_value) ? "Path Not Found" : implode('=>', $path[0]) . "=>" . $weight;
echo $output;

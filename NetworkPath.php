<?php

class NetworkPath
{
    public $graph;

    public $distance;

    public $previous;

    public $queue;

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
    /**
     * Excution starts from here
     * Receives the input from the user
     */
    public function run()
    {
        echo "Enter Device From, Device To and Latency (eg: A B 10 followed by ENTER key): ";
        $input = trim(strtoupper(fgets(STDIN)));

        $result = $this->getResult($input);
        if ($result) {
            echo $result . PHP_EOL;
        }

        $this->checkSelection();
    }
    /**
     * wait for the user input
     */
    public function checkSelection()
    {
        echo "Do you want to continue? (Yes/Quit): ";
        $input = trim(strtoupper(fgets(STDIN)));
        if ($input == 'YES') {
            $this->run();
        } elseif ($input == 'QUIT') {
            exit('Have a nice day!');
        } else {
            echo 'Invalid selection !!' . PHP_EOL;
            $this->checkSelection();
        }
    }
    /**
     * Prepare the result
     * @param string  $input Soruce, destination and latency seperated by spaces 
     * 
     * @return string shortest path with value e.g. A=>C=>D=>E=>F=>1060
     */
    public function getResult($input)
    {
        $options = array('A', 'B', 'C', 'D', 'E', 'F');
        $exploded = explode(" ", $input);

        $first_node = in_array($exploded[0], $options) ? $exploded[0] : exit("Invalid data");
        $second_node = in_array($exploded[1], $options) ? $exploded[1] : exit("Invalid data");
        $latency_value = is_numeric($exploded[2]) ? $exploded[2] : exit("Invaild data");

        $path = $this->shortestPaths($first_node, $second_node);
        $weight =  $this->distance[$second_node];
        $output = ($weight > $latency_value) ? "Path Not Found" : implode('=>', $path[0]) . "=>" . $weight;

        return $output;
    }
}

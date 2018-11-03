<?php
namespace BrummelMW\core;

class ListenerJSONParser extends \JsonStreamingParser\Listener\InMemoryListener
{
    //control variable that allow us to know if is a child or parent object
    protected $level = 0;

    public function __construct()
    {
        ini_set('memory_limit', '256M');
    }

    protected function startComplexValue($type): void
    {
        //start complex value, increment our level
        $this->level++;
        parent::startComplexValue($type);
    }

    protected function endComplexValue(): void
    {
        //end complex value, decrement our level
        $this->level--;
        $obj = array_pop($this->stack);
        // If the value stack is now empty, we're done parsing the document, so we can
        // move the result into place so that getJson() can return it. Otherwise, we
        // associate the value
        if (empty($this->stack)) {
            $this->result = $obj['value'];
        } else {
            if ($obj['type'] == 'object') {
                //insert value to top object, author listener way
                $this->insertValue($obj['value']);
            } elseif ($obj['type'] == 'array') {
                $this->insertValue($obj['value']);
                //$this->insertObj($obj);
            } else {
                echo "TYPE:".$obj['type']."\n";

            }
        }
    }

    //custom function to do whatever
    protected function insertObj($obj)
    {
        echo "<pre>".print_r($obj,true)."</pre>";
    }
}
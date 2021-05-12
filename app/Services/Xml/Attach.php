<?php

namespace App\Services\Xml;

trait Attach
{
    public function attach(string $nodeName, $nodeValue = null, bool $fluent = false): Node
    {
        $node = new Node($nodeName, $nodeValue);
        $this->appendChild($node);
        /** @var Doc|Node $this */
        return $fluent ? $this : $node;
    }
    
    /*
     * @param string $name
     * @param string $value
     * @return self
     */
    public function attr(string $name, string $value): self
    {
        $this->setAttribute($name, $value);
        
        return $this;
    }
}

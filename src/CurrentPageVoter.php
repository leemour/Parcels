<?php

namespace Parcels;

use Knp\Menu\ItemInterface;

/**
 * Voter based on the uri
 */
class CurrentPageVoter extends \Knp\Menu\Matcher\Voter\UriVoter
{
    private $uri;

    public function __construct($uri = null)
    {
        $this->uri = $uri;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
    }
    public function matchItem(ItemInterface $item)
    {
        if (null === $this->uri || null === $item->getUri()) {
            return null;
        }
        
        $uri = $item->getUri();
        $uri = substr($uri, strrpos($uri, '/') + 1);
        
        if ($this->uri === $uri) {
            return true;
        }

        return null;
    }
}
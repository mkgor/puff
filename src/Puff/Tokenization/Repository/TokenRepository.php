<?php

namespace Puff\Tokenization\Repository;

use Puff\Tokenization\Entity\Token;

/**
 * Class TokenRepository
 *
 * @package Tokenization\Entity
 */
class TokenRepository implements TokenRepositoryInterface
{
    protected $container;

    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param mixed $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @param string   $parameter
     * @param callable $callback
     *
     * @return bool|mixed
     */
    public function findByCallback($parameter, callable $callback)
    {
        /** @var Token $item */
        foreach($this->getContainer() as $item) {
            foreach($item->asArray() as $key => $value) {
                if($parameter == $key) {
                    if($callback($value)) {
                        return $item;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param Token $token
     */
    public function push(Token $token)
    {
        $this->container[] = $token;
    }
}
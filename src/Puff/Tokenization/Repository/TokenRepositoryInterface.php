<?php

namespace Puff\Tokenization\Repository;

use Puff\Tokenization\Entity\Token;

interface TokenRepositoryInterface
{
    public function getContainer();

    public function setContainer($container);

    public function push(Token $token);
}
<?php

namespace Tokenization\Repository;

use PHPUnit\Framework\TestCase;
use Puff\Tokenization\Entity\Token;
use Puff\Tokenization\Repository\TokenRepository;

class TokenRepositoryTest extends TestCase
{

    public function testFindByCallback()
    {
        $tokenRepository = new TokenRepository();
        $tokenRepository->push(new Token('testToken', [
            'arg' => 'test'
        ],'test'));

        $this->assertInstanceOf(Token::class, $tokenRepository->findByCallback('tokenName', function($value) {
            return $value == 'testToken';
        }));

        $this->assertFalse($tokenRepository->findByCallback('tokenName', function($value) {
            return $value == 'unkownToken';
        }));
    }
}

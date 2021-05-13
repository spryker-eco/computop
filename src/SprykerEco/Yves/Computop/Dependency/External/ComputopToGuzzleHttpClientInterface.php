<?php

namespace SprykerEco\Yves\Computop\Dependency\External;

use Psr\Http\Message\ResponseInterface;

interface ComputopToGuzzleHttpClientInterface
{
    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @throws \Spryker\Yves\Computop\Http\Exception\ComputopHttpRequestException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface;
}

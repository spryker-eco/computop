<?php

namespace SprykerEco\Yves\Computop\Dependency\External;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Spryker\Yves\Computop\Http\Exception\ComputopHttpRequestException;

class ComputopToGuzzleHttpClientAdapter implements ComputopToGuzzleHttpClientInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $guzzleHttpClient;

    /**
     * @param \GuzzleHttp\ClientInterface $guzzleHttpClient
     */
    public function __construct(ClientInterface $guzzleHttpClient)
    {
        $this->guzzleHttpClient = $guzzleHttpClient;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @return ResponseInterface
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        try {
            return $this->guzzleHttpClient->request($method, $uri, $options);
        } catch (GuzzleException $exception) {
            throw new ComputopHttpRequestException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}

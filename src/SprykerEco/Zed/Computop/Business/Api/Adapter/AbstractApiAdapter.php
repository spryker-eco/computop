<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use SprykerEco\Zed\Computop\Business\Exception\ComputopHttpRequestException;
use SprykerEco\Zed\Computop\ComputopConfig;

abstract class AbstractApiAdapter implements AdapterInterface
{
    const DEFAULT_TIMEOUT = 45;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @return string
     */
    abstract protected function getUrl();

    /**
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     */
    public function __construct(ComputopConfig $config)
    {
        $this->client = new Client([
            RequestOptions::TIMEOUT => self::DEFAULT_TIMEOUT,
        ]);

        $this->config = $config;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function sendRequest(array $data)
    {
        $options[RequestOptions::FORM_PARAMS] = $data;

        return $this->send($options);
    }

    /**
     * @param array $options
     *
     * @throws \SprykerEco\Zed\Computop\Business\Exception\ComputopHttpRequestException
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    protected function send(array $options = [])
    {
        try {
            $response = $this->client->post(
                $this->getUrl(),
                $options
            );
        } catch (RequestException $requestException) {
            throw new ComputopHttpRequestException(
                $requestException->getMessage(),
                $requestException->getCode(),
                $requestException
            );
        }

        return $response->getBody();
    }
}

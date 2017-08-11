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

abstract class AbstractApiAdapter implements AdapterInterface
{

    const DEFAULT_TIMEOUT = 45;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            RequestOptions::TIMEOUT => self::DEFAULT_TIMEOUT,
        ]);
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
            throw new ComputopHttpRequestException($requestException->getMessage());
        }

        return $response->getBody();
    }

    /**
     * @return string
     */
    abstract protected function getUrl();

}

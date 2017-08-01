<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConstants;

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
            'timeout' => self::DEFAULT_TIMEOUT,
        ]);
    }

    /**
     * @param array|string $data
     *
     * @return string
     */
    public function sendRequest(array $data)
    {
        $request = $this->buildRequest();
        $options = [];

        return $this->send($request, $options);
    }

    /**
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function buildRequest()
    {
        return new Request(
            'POST',
            Config::get(ComputopConstants::COMPUTOP_CREDIT_CARD_AUTHORIZE_ACTION)
        );
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array $options
     *
     * @throws \Spryker\Zed\Payolution\Business\Exception\ApiHttpRequestException
     *
     * @return string
     */
    protected function send($request, array $options = [])
    {
        try {
            $response = $this->client->send($request, $options);
        } catch (RequestException $requestException) {
            throw new ApiHttpRequestException($requestException->getMessage());
        }

        return $response->getBody();
    }

    abstract protected function prepareData(array $data);

}

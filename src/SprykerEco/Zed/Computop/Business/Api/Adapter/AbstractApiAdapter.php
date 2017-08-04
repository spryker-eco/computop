<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConstants;
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
        $options[RequestOptions::FORM_PARAMS] = $this->prepareData($data);

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
                Config::get(ComputopConstants::COMPUTOP_CREDIT_CARD_AUTHORIZE_ACTION),
                $options
            );
        } catch (RequestException $requestException) {
            throw new ComputopHttpRequestException($requestException->getMessage());
        }

        return $response->getBody();
    }

    /**
     * @param array $data
     *
     * @return array
     */
    abstract protected function prepareData(array $data);

}

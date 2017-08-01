<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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
     * @param array $data
     *
     * @return string
     */
    public function sendRequest(array $data)
    {
        $options = $this->prepareData($data);
        $request = $this->buildRequest($options);

        return $this->send($request, $options);
    }

    /**
     * @param array $data
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function buildRequest(array $data)
    {
//        return new Request(
//            'POST',
//            Config::get(ComputopConstants::COMPUTOP_CREDIT_CARD_AUTHORIZE_ACTION),
//            [
//                'curl' => [
//                    CURLOPT_URL => Config::get(ComputopConstants::COMPUTOP_CREDIT_CARD_AUTHORIZE_ACTION),
//                    CURLOPT_HTTP_VERSION => 1.0,
//                    CURLOPT_POST => 1,
//                    CURLOPT_POSTFIELDS => $data,
//                    CURLOPT_HEADER => 0,
//                    CURLOPT_RETURNTRANSFER => 1,
//                    CURLOPT_SSL_VERIFYPEER => 0,
//                    CURLOPT_TIMEOUT => 120,
//                    CURLOPT_FAILONERROR => 1,
//                ]
//            ]
//        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, Config::get(ComputopConstants::COMPUTOP_CREDIT_CARD_AUTHORIZE_ACTION));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, 1.0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
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

    /**
     * @param array $data
     *
     * @return array
     */
    abstract protected function prepareData(array $data);

}

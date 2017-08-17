<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use GuzzleHttp\Psr7\Stream;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface;

abstract class AbstractConverter
{

    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface
     */
    protected $computopService;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * CreditCardMapper constructor.
     *
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface $computopService
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     */
    public function __construct(ComputopToComputopServiceInterface $computopService, ComputopConfig $config)
    {
        $this->computopService = $computopService;
        $this->config = $config;
    }

    /**
     * @param \GuzzleHttp\Psr7\Stream $response
     *
     * @return mixed
     */
    public function toTransactionResponseTransfer(Stream $response)
    {
        $decryptedArray = $this->getDecryptedArray($response);

        return $this->getResponseTransfer($decryptedArray);
    }

    /**
     * @param \GuzzleHttp\Psr7\Stream $response
     *
     * @return array
     */
    protected function getDecryptedArray(Stream $response)
    {
        parse_str($response->getContents(), $responseArray);

        $decryptedArray = $this
            ->computopService
            ->getDecryptedArray($responseArray, $this->config->getBlowfishPass());

        return $decryptedArray;
    }

    /**
     * @param array $decryptedArray
     *
     * @return mixed
     */
    abstract protected function getResponseTransfer($decryptedArray);

}

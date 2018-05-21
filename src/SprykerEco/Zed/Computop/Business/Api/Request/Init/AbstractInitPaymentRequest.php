<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Request\Init;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface;
use SprykerEco\Zed\Computop\Business\Api\Mapper\Init\ApiInitMapperInterface;

abstract class AbstractInitPaymentRequest implements InitRequestInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    protected $converter;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Mapper\PrePlace\ApiPrePlaceMapperInterface
     */
    protected $mapper;

    /**
     * @param \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface $adapter
     * @param \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface $converter
     * @param \SprykerEco\Zed\Computop\Business\Api\Mapper\Init\ApiInitMapperInterface $mapper
     */
    public function __construct(
        AdapterInterface $adapter,
        ConverterInterface $converter,
        ApiInitMapperInterface $mapper
    ) {
        $this->adapter = $adapter;
        $this->converter = $converter;
        $this->mapper = $mapper;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function request($computopPaymentTransfer)
    {
        $requestData = $this
            ->mapper
            ->buildRequest($computopPaymentTransfer);

        return $this->sendRequest($requestData);
    }

    /**
     * @param array $requestData
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function sendRequest(array $requestData)
    {
        $requestData = $this
            ->adapter
            ->sendRequest($requestData);

        $responseTransfer = $this
            ->converter
            ->toTransactionResponseTransfer(
                $requestData
            );

        return $responseTransfer;
    }
}

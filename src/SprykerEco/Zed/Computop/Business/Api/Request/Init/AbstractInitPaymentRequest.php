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
     * @param \SprykerEco\Zed\Computop\Business\Api\Mapper\PrePlace\ApiPrePlaceMapperInterface $mapper
     */
    public function __construct(
        AdapterInterface $adapter,
        ConverterInterface $converter,
        ApiPrePlaceMapperInterface $mapper
    ) {
        $this->adapter = $adapter;
        $this->converter = $converter;
        $this->mapper = $mapper;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function request($quoteTransfer, $computopHeaderPayment)
    {
        $requestData = $this
            ->mapper
            ->buildRequest($quoteTransfer, $computopHeaderPayment);

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

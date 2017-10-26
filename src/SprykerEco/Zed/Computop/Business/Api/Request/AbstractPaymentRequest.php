<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Request;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface;
use SprykerEco\Zed\Computop\Business\Api\Mapper\AbstractMapperInterface;
use SprykerEco\Zed\Computop\Business\Exception\ComputopMethodMapperException;

abstract class AbstractPaymentRequest
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
     * @var string
     */
    protected $paymentMethod;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Mapper\AbstractMapperInterface[]
     */
    protected $methodMappers = [];

    /**
     * @param \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface $adapter
     * @param \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface $converter
     * @param string $paymentMethod
     */
    public function __construct(
        AdapterInterface $adapter,
        ConverterInterface $converter,
        $paymentMethod
    ) {
        $this->adapter = $adapter;
        $this->converter = $converter;
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function request(OrderTransfer $orderTransfer)
    {
        $requestData = $this
            ->getMethodMapper($this->paymentMethod)
            ->buildRequest($orderTransfer);

        return $this->sendRequest($requestData);
    }

    /**
     * @param \SprykerEco\Zed\Computop\Business\Api\Mapper\AbstractMapperInterface $paymentMethod
     *
     * @return void
     */
    public function registerMapper(AbstractMapperInterface $paymentMethod)
    {
        $this->methodMappers[$paymentMethod->getMethodName()] = $paymentMethod;
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

    /**
     * @param string $methodName
     *
     * @throws \SprykerEco\Zed\Computop\Business\Exception\ComputopMethodMapperException
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\AbstractMapperInterface
     */
    protected function getMethodMapper($methodName)
    {
        if (isset($this->methodMappers[$methodName]) === false) {
            throw new ComputopMethodMapperException('The method mapper is not registered.');
        }

        return $this->methodMappers[$methodName];
    }
}

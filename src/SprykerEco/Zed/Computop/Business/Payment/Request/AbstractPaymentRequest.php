<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Request;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Computop\Business\Exception\ComputopMethodMapperException;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface;

abstract class AbstractPaymentRequest
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return mixed
     */
    abstract public function request(OrderTransfer $orderTransfer);

    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $paymentMethod;

    /**
     * @var array|\SprykerEco\Zed\Computop\Business\Payment\Manager\AbstractManagerInterface[]
     */
    protected $methodMappers = [];

    /**
     * @param \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface $adapter
     * @param string $paymentMethod
     */
    public function __construct(
        AdapterInterface $adapter,
        $paymentMethod
    ) {
        $this->adapter = $adapter;
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @param array $requestData
     *
     * @return mixed
     */
    protected function sendRequest(array $requestData)
    {
        $request = $this
            ->adapter
            ->sendRequest($requestData);

        return $request;
    }

    /**
     * @param \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface $paymentMethod
     *
     * @return void
     */
    public function registerMapper(AbstractMapperInterface $paymentMethod)
    {
        $this->methodMappers[$paymentMethod->getMethodName()] = $paymentMethod;
    }

    /**
     * @param string $methodName
     *
     * @throws \SprykerEco\Zed\Computop\Business\Exception\ComputopMethodMapperException
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Manager\AbstractManagerInterface|\SprykerEco\Zed\Computop\Business\Payment\Manager\Invoice\InvoiceManagerInterface
     */
    protected function getMethodMapper($methodName)
    {
        if (isset($this->methodMappers[$methodName]) === false) {
            throw new ComputopMethodMapperException('The method mapper is not registered.');
        }

        return $this->methodMappers[$methodName];
    }

}

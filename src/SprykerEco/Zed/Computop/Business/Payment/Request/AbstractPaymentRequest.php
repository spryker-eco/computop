<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Request;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface;

abstract class AbstractPaymentRequest
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    abstract public function request(OrderTransfer $orderTransfer);

    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @param \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface $adapter
     */
    public function __construct(
        AdapterInterface $adapter
    ) {
        $this->adapter = $adapter;
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

}

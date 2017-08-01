<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Zed\Computop\Business\Api\Adapter\AuthorizeApiAdapter;
use SprykerEco\Zed\Computop\Business\Order\OrderManager;
use SprykerEco\Zed\Computop\Business\Payment\Request\AuthorizationRequest;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer getQueryContainer()
 */
class ComputopBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\OrderManagerInterface
     */
    public function createOrderSaver()
    {
        return new OrderManager();
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Request\AuthorizationRequest
     */
    public function createAuthorizationPaymentRequest($paymentMethod)
    {
        return new AuthorizationRequest(
            $this->createPreauthorizeAdapter($paymentMethod)
        );
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected function createPreauthorizeAdapter($paymentMethod)
    {
        return new AuthorizeApiAdapter(
            $this->getConfig()
        );
    }

}

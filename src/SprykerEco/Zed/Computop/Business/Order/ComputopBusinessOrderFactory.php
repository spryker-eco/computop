<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order;

use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\Business\Order\Mapper\CreditCardMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\DirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\PaydirektMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\PayPalMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\SofortMapper;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer getQueryContainer()
 */
class ComputopBusinessOrderFactory extends ComputopBusinessFactory implements ComputopBusinessOrderFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createOrderCreditCardMapper()
    {
        return new CreditCardMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createOrderPayPalMapper()
    {
        return new PayPalMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createOrderDirectDebitMapper()
    {
        return new DirectDebitMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createOrderSofortMapper()
    {
        return new SofortMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createOrderPaydirektMapper()
    {
        return new PaydirektMapper();
    }
}

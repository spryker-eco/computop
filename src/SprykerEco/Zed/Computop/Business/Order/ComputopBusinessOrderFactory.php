<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order;

use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface;
use SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace\CreditCardMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace\DirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace\EasyCreditMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace\IdealMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace\PaydirektMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace\PayNowMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace\PayPalMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace\PayuCeeSingleMapper;
use SprykerEco\Zed\Computop\Business\Order\Mapper\PostPlace\SofortMapper;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 */
class ComputopBusinessOrderFactory extends ComputopBusinessFactory implements ComputopBusinessOrderFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitCreditCardMapper()
    {
        return new CreditCardMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitPayNowMapper()
    {
        return new PayNowMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitPayPalMapper()
    {
        return new PayPalMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitDirectDebitMapper()
    {
        return new DirectDebitMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitSofortMapper()
    {
        return new SofortMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitPaydirektMapper()
    {
        return new PaydirektMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitIdealMapper()
    {
        return new IdealMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitEasyCreditMapper()
    {
        return new EasyCreditMapper();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createPayuCeeSinglePostPlaceMapper(): MapperInterface
    {
        return new PayuCeeSingleMapper();
    }
}

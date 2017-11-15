<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order;

interface ComputopBusinessOrderFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitCreditCardMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitPayPalMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitDirectDebitMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitSofortMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitPaydirektMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitIdealMapper();
    
    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitEasyCreditMapper();
}

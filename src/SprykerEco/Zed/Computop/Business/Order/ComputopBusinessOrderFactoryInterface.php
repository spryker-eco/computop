<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Order;

use SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface;

interface ComputopBusinessOrderFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitCreditCardMapper(): MapperInterface;

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitPayNowMapper(): MapperInterface;

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitPayPalMapper(): MapperInterface;

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createPayPalExpressMapper(): MapperInterface;

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitDirectDebitMapper(): MapperInterface;

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitSofortMapper(): MapperInterface;

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitPaydirektMapper(): MapperInterface;

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitIdealMapper(): MapperInterface;

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createInitEasyCreditMapper(): MapperInterface;

    /**
     * @return \SprykerEco\Zed\Computop\Business\Order\Mapper\MapperInterface
     */
    public function createPayuCeeSinglePostPlaceMapper(): MapperInterface;
}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Computop\Payment\Mapper\CreditCard;

use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\ReverseCreditCardMapper;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Api
 * @group Mapper
 * @group ReverseCreditCardMapperTest
 */
class ReverseCreditCardMapperTest extends AbstractCreditCardMapperTest
{

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\AuthorizeCreditCardMapper
     */
    protected function createMapper()
    {
        return new ReverseCreditCardMapper($this->createComputopServiceMock(), $this->createComputopConfigMock());
    }

}

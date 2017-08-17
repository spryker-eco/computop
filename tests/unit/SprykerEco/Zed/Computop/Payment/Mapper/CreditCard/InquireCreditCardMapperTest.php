<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Computop\Payment\Mapper\CreditCard;

use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\InquireCreditCardMapper;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Api
 * @group Mapper
 * @group InquireCreditCardMapperTest
 */
class InquireCreditCardMapperTest extends AbstractCreditCardMapperTest
{

    /**
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\AuthorizeCreditCardMapper
     */
    protected function createMapper()
    {
        return new InquireCreditCardMapper($this->createComputopServiceMock(), $this->createComputopConfigMock());
    }

}

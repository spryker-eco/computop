<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Api\Mapper\CreditCard;

use SprykerEco\Zed\Computop\Business\Api\Mapper\CreditCard\InquireCreditCardMapper;

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
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    protected function createMapper()
    {
        return new InquireCreditCardMapper(
            $this->helper->createComputopServiceMock(),
            $this->helper->createComputopConfigMock(),
            $this->helper->createStoreMock()
        );
    }
}

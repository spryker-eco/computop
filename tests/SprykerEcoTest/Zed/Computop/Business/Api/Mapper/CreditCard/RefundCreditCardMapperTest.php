<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Api\Mapper\CreditCard;

use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\CreditCard\RefundCreditCardMapper;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Api
 * @group Mapper
 * @group RefundCreditCardMapperTest
 */
class RefundCreditCardMapperTest extends AbstractCreditCardMapperTest
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    protected function createMapper()
    {
        return new RefundCreditCardMapper(
            $this->helper->createComputopServiceMock(),
            $this->helper->createComputopConfigMock(),
            $this->helper->createStoreMock(),
            $this->helper->createQueryContainerMock()
        );
    }
}

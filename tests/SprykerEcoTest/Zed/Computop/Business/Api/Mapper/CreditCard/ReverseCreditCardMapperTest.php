<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Api\Mapper\CreditCard;

use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\CreditCard\ReverseCreditCardMapper;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;

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
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    protected function createMapper()
    {
        return new ReverseCreditCardMapper(
            $this->helper->createComputopServiceMock(),
            $this->helper->createComputopConfigMock(),
            $this->helper->createStoreMock(),
            $this->helper->createQueryContainerMock()
        );
    }
}

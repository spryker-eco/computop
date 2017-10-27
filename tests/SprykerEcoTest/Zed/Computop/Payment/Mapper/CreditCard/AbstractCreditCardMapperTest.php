<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Payment\Mapper\CreditCard;

use Codeception\TestCase\Test;
use SprykerEcoTest\Zed\Computop\Payment\Mapper\CreditCard\CreditCardMapperTestConstants;
use SprykerEcoTest\Zed\Computop\Payment\Mapper\CreditCard\CreditCardMapperTestHelper;
use SprykerEco\Shared\Computop\Config\ComputopFieldName;

abstract class AbstractCreditCardMapperTest extends Test
{
    /**
     * Return needed mapper
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    abstract protected function createMapper();

    /**
     * @var \SprykerEcoTest\Zed\Computop\Payment\Mapper\CreditCard\CreditCardMapperTestHelper
     */
    protected $helper;

    /**
     * @param \SprykerEcoTest\Zed\Computop\Payment\Mapper\CreditCard\CreditCardMapperTestHelper $helper
     *
     * @return void
     */
    protected function _inject(CreditCardMapperTestHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return void
     */
    public function testBuildRequest()
    {
        $orderTransferMock = $this->helper->createOrderTransferMock();

        $service = $this->createMapper();
        $mappedData = $service->buildRequest($orderTransferMock);

        $this->assertEquals(CreditCardMapperTestConstants::DATA_VALUE, $mappedData[ComputopFieldName::DATA]);
        $this->assertEquals(CreditCardMapperTestConstants::LENGTH_VALUE, $mappedData[ComputopFieldName::LENGTH]);
    }
}

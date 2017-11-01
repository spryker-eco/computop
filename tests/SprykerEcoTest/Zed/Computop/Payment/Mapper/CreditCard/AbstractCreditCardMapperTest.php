<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Payment\Mapper\CreditCard;

use Codeception\TestCase\Test;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

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

        $this->assertEquals(CreditCardMapperTestConstants::DATA_VALUE, $mappedData[ComputopApiConfig::DATA]);
        $this->assertEquals(CreditCardMapperTestConstants::LENGTH_VALUE, $mappedData[ComputopApiConfig::LENGTH]);
    }
}

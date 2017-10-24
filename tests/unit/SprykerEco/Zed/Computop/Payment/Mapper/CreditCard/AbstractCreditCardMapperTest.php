<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Computop\Payment\Mapper\CreditCard;

use Codeception\TestCase\Test;
use Computop\Helper\Unit\Zed\Payment\Mapper\CreditCard\CreditCardMapperTestConstants;
use Computop\Helper\Unit\Zed\Payment\Mapper\CreditCard\CreditCardMapperTestHelper;
use SprykerEco\Shared\Computop\Config\ComputopFieldNameConstants;

abstract class AbstractCreditCardMapperTest extends Test
{
    /**
     * Return needed mapper
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    abstract protected function createMapper();

    /**
     * @var \Computop\Helper\Unit\Zed\Payment\Mapper\CreditCard\CreditCardMapperTestHelper
     */
    protected $helper;

    /**
     * @param \Computop\Helper\Unit\Zed\Payment\Mapper\CreditCard\CreditCardMapperTestHelper $helper
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

        $this->assertEquals(CreditCardMapperTestConstants::DATA_VALUE, $mappedData[ComputopFieldNameConstants::DATA]);
        $this->assertEquals(CreditCardMapperTestConstants::LENGTH_VALUE, $mappedData[ComputopFieldNameConstants::LENGTH]);
    }
}

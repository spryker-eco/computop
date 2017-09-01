<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Computop\Payment\Mapper\CreditCard;

use Codeception\TestCase\Test;
use Computop\Helper\Unit\Zed\Payment\Mapper\CreditCard\CreditCardMapperHelper;
use SprykerEco\Shared\Computop\ComputopConstants;

abstract class AbstractCreditCardMapperTest extends Test
{

    /**
     * Return needed mapper
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\CreditCardMapperInterface
     */
    abstract protected function createMapper();

    /**
     * @var \Computop\Helper\Unit\Zed\Payment\Mapper\CreditCard\CreditCardMapperHelper
     */
    protected $helper;

    /**
     * @param \Computop\Helper\Unit\Zed\Payment\Mapper\CreditCard\CreditCardMapperHelper $helper
     *
     * @return void
     */
    protected function _inject(CreditCardMapperHelper $helper)
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

        $this->assertEquals($this->helper->getDataValue(), $mappedData[ComputopConstants::DATA_F_N]);
        $this->assertEquals($this->helper->getLengthValue(), $mappedData[ComputopConstants::LENGTH_F_N]);
        $this->assertEquals($this->helper->getMerchantIdValue(), $mappedData[ComputopConstants::MERCHANT_ID_F_N]);
    }

}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business;

use Generated\Shared\Transfer\ComputopCreditCardCaptureResponseTransfer;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\ComputopFacade;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Business
 * @group CapturePaymentTest
 */
class CapturePaymentTest extends AbstractPaymentTest
{

    const PAY_ID_VALUE = 'e1ebdc5bd6054263a52ef33ecae1ccda';
    const TRANS_ID_VALUE = '585e330ea1fe696ffb0bad71db503504';
    const X_ID_VALUE = 'cb077bef0c434b5ba58fbd700cf66ea8';
    const STATUS_VALUE = 'OK';
    const CODE_VALUE = '00000000';

    /**
     * @return void
     */
    public function testCapturePaymentSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());

        /** @var \Generated\Shared\Transfer\ComputopCreditCardCaptureResponseTransfer $response */
        $response = $service->capturePaymentRequest($this->createOrderTransfer());

        $this->assertInstanceOf(ComputopCreditCardCaptureResponseTransfer::class, $response);
        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $response->getHeader());

        $this->assertEquals(self::TRANS_ID_VALUE, $response->getHeader()->getTransId());
        $this->assertEquals(self::PAY_ID_VALUE, $response->getHeader()->getPayId());
        $this->assertEquals(self::X_ID_VALUE, $response->getHeader()->getXId());
        $this->assertEquals(self::STATUS_VALUE, $response->getHeader()->getStatus());
        $this->assertEquals(self::CODE_VALUE, $response->getHeader()->getCode());

        $this->assertTrue($response->getHeader()->getIsSuccess());
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setComputopCreditCard($this->createComputopPaymentTransfer());
        $orderTransfer->setTotals(new TotalsTransfer());
        $orderTransfer->setCustomer(new CustomerTransfer());
        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createComputopPaymentTransfer()
    {
        $payment = new ComputopCreditCardPaymentTransfer();
        $payment->setPaymentMethod(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD);
        $payment->setPayId($this->getPayIdValue());

        return $payment;
    }

    /**
     * @return string
     */
    protected function getPayIdValue()
    {
        return self::PAY_ID_VALUE;
    }

}

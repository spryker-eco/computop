<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business;

use Generated\Shared\Transfer\ComputopCreditCardInquireResponseTransfer;
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
 * @group InquirePaymentTest
 */
class InquirePaymentTest extends AbstractPaymentTest
{

    const PAY_ID_VALUE = '43a10d3593cf473ea59f1f852f151227';
    const TRANS_ID_VALUE = 'afa4e781d22f1aa09ccea5935edb6a4c';
    const STATUS_VALUE = 'OK';
    const CODE_VALUE = '00000000';
    const DESCRIPTION_VALUE = 'OK';
    const AMOUNT_AUTH_VALUE = '171';
    const AMOUNT_CAP_VALUE = '0';
    const AMOUNT_CRED_VALUE = '0';
    const LAST_STATUS_VALUE = 'OK';

    /**
     * @return void
     */
    public function testAuthorizePaymentSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());

        /** @var \Generated\Shared\Transfer\ComputopCreditCardInquireResponseTransfer $response */
        $response = $service->inquirePaymentRequest($this->createOrderTransfer());

        $this->assertInstanceOf(ComputopCreditCardInquireResponseTransfer::class, $response);
        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $response->getHeader());

        $this->assertEquals(self::TRANS_ID_VALUE, $response->getHeader()->getTransId());
        $this->assertEquals(self::PAY_ID_VALUE, $response->getHeader()->getPayId());
        $this->assertEquals(self::STATUS_VALUE, $response->getHeader()->getStatus());
        $this->assertEquals(self::CODE_VALUE, $response->getHeader()->getCode());
        $this->assertEquals(self::DESCRIPTION_VALUE, $response->getHeader()->getDescription());
        $this->assertEquals(self::AMOUNT_AUTH_VALUE, $response->getAmountAuth());
        $this->assertEquals(self::AMOUNT_CAP_VALUE, $response->getAmountCap());
        $this->assertEquals(self::AMOUNT_CRED_VALUE, $response->getAmountCred());
        $this->assertEquals(self::LAST_STATUS_VALUE, $response->getLastStatus());

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

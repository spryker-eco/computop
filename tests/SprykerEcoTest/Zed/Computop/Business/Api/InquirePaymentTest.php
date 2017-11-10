<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Api;

use Generated\Shared\Transfer\ComputopInquireResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Zed\Computop\Business\Api\Adapter\InquireApiAdapter;
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
    const DATA_INQUIRE_VALUE = '492afd99f603108b3998128e790fc435cdd38592f603102737ca7782bb700c36ec34d4d073713bb49736aafc69b426e0124e98ede95bd239f0bf038e4b2732ae88423ab8bce448fb3b3d42e15a3314b2e4ef78e79acb21dbaa1bdc8395ef298b96dcf5ae9cc279366836c4f649db46cdeb04c0dd0ef7ed21aa23ab71017659d8709af32899fa98adb85882e97aa3c0e13a8d48db6a302de52d5c082fe5b69371a5a4e7a57e96828b971f91bdb4fbd46422209d872212ab4517d7463bb91a3f4f';
    const LEN_INQUIRE_VALUE = 189;

    const PAY_ID_VALUE = 'f36b78ed6f424ab391a671b9f59c600d';
    const TRANS_ID_VALUE = 'f93263381e0db204a923484ec54d0967';
    const STATUS_VALUE = 'OK';
    const CODE_VALUE = '00000000';
    const DESCRIPTION_VALUE = 'OK';
    const AMOUNT_AUTH_VALUE = '137';
    const AMOUNT_CAP_VALUE = '0';
    const AMOUNT_CRED_VALUE = '0';
    const LAST_STATUS_VALUE = 'OK';

    /**
     * @return void
     */
    public function testInquirePaymentSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $orderTransfer = $this->apiHelper->createOrderTransfer();
        $computopHeaderPaymentTransfer = $this->apiHelper->createComputopHeaderPaymentTransfer($this->getPayIdValue(), self::TRANS_ID_VALUE);

        //todo: update test
        /** @var \Generated\Shared\Transfer\ComputopInquireResponseTransfer $response */
//        $response = $service->inquirePaymentRequest($orderTransfer, $computopHeaderPaymentTransfer);
//
//        $this->assertInstanceOf(ComputopInquireResponseTransfer::class, $response);
//        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $response->getHeader());
//
//        $this->assertSame(self::TRANS_ID_VALUE, $response->getHeader()->getTransId());
//        $this->assertSame(self::PAY_ID_VALUE, $response->getHeader()->getPayId());
//        $this->assertSame(self::STATUS_VALUE, $response->getHeader()->getStatus());
//        $this->assertSame(self::CODE_VALUE, $response->getHeader()->getCode());
//        $this->assertSame(self::DESCRIPTION_VALUE, $response->getHeader()->getDescription());
//        $this->assertSame(self::AMOUNT_AUTH_VALUE, $response->getAmountAuth());
//        $this->assertSame(self::AMOUNT_CAP_VALUE, $response->getAmountCap());
//        $this->assertSame(self::AMOUNT_CRED_VALUE, $response->getAmountCred());
//        $this->assertSame(self::LAST_STATUS_VALUE, $response->getLastStatus());
//
//        $this->assertTrue($response->getHeader()->getIsSuccess());
    }

    /**
     * @return string
     */
    protected function getPayIdValue()
    {
        return self::PAY_ID_VALUE;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getApiAdapter()
    {
        $mock = $this->createPartialMock(InquireApiAdapter::class, ['sendRequest']);

        $mock->method('sendRequest')
            ->willReturn($this->getStream(self::DATA_INQUIRE_VALUE, self::LEN_INQUIRE_VALUE));

        return $mock;
    }

    /**
     * @return string
     */
    protected function getApiAdapterFunctionName()
    {
        return 'createInquireAdapter';
    }
}

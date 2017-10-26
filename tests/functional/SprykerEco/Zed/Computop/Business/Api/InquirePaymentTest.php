<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business\Api;

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
    const DATA_INQUIRE_VALUE = 'A23501461AA31D2A7DE3ABDBAB085C6938916FD8E29AB866F1A20FC03A17576AFCA8C5B645E5D191F781B4240908B5312427DBF4B95A90E6622991FD3247ADD1E6814FAC6B202DBF6B1AD8C1A64D8CF39D0C511883F14B9FDFE0B5F4DFBE6FECC1C36306121044872989FF3F065FE3FCF9A82F950BE564E97B0DD3E834F33D28ADD81D7485794BAFA4067217FCD5FBBE532F86D88BFCCF78B75C2C2C8876C11749CA85AFB166C2A3A06B38551659693F99591F5BB556954A5D1CC745E54980F7';
    const LEN_INQUIRE_VALUE = 189;

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
    public function testInquirePaymentSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $orderTransfer = $this->apiHelper->createOrderTransfer();
        $computopHeaderPaymentTransfer = $this->apiHelper->createComputopHeaderPaymentTransfer($this->getPayIdValue(), self::TRANS_ID_VALUE);

        /** @var \Generated\Shared\Transfer\ComputopInquireResponseTransfer $response */
        $response = $service->inquirePaymentRequest($orderTransfer, $computopHeaderPaymentTransfer);

        $this->assertInstanceOf(ComputopInquireResponseTransfer::class, $response);
        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $response->getHeader());

        $this->assertSame(self::TRANS_ID_VALUE, $response->getHeader()->getTransId());
        $this->assertSame(self::PAY_ID_VALUE, $response->getHeader()->getPayId());
        $this->assertSame(self::STATUS_VALUE, $response->getHeader()->getStatus());
        $this->assertSame(self::CODE_VALUE, $response->getHeader()->getCode());
        $this->assertSame(self::DESCRIPTION_VALUE, $response->getHeader()->getDescription());
        $this->assertSame(self::AMOUNT_AUTH_VALUE, $response->getAmountAuth());
        $this->assertSame(self::AMOUNT_CAP_VALUE, $response->getAmountCap());
        $this->assertSame(self::AMOUNT_CRED_VALUE, $response->getAmountCred());
        $this->assertSame(self::LAST_STATUS_VALUE, $response->getLastStatus());

        $this->assertTrue($response->getHeader()->getIsSuccess());
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
        return 'getInquireAdapter';
    }
}

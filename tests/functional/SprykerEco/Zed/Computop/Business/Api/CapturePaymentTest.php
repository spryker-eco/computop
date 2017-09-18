<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business\Api;

use Generated\Shared\Transfer\ComputopCaptureResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Zed\Computop\Business\Api\Adapter\CaptureApiAdapter;
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

    const DATA_CAPTURE_VALUE = 'A23501461AA31D2A7DE3ABDBAB085C69D64A63583A9918823EE25F984198B24416307CC9F716E339447048AAFA6DAEAF1FE56931868EC6658FEC353615B935F22C66BC6A762E02F1D9D1692A94044C37B12C35E04B693BF8FC482FC0DB76A8EEF64BBDA30D301B0E20EBAD824394932159A025D5FAD81F3059E75755A9D2DF460D45A61E2D011772F07857CD3937652E8610531FC7A8D301559CEED3BC0FA14971922F5D33B6964034477F769880CD30EF3E6BC7B3D2F199DB124B974F304C742DF697E4A3F784EBB63EFF9FA71CC905703A4AA73E1C751D1B4EB02D449C8AFA';
    const LEN_CAPTURE_VALUE = 223;

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
        $orderTransfer = $this->apiHelper->createOrderTransfer();
        $computopHeaderPaymentTransfer = $this->apiHelper->createComputopHeaderPaymentTransfer($this->getPayIdValue(), self::TRANS_ID_VALUE);

        /** @var \Generated\Shared\Transfer\ComputopCaptureResponseTransfer $response */
        $response = $service->capturePaymentRequest($orderTransfer, $computopHeaderPaymentTransfer);

        $this->assertInstanceOf(ComputopCaptureResponseTransfer::class, $response);
        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $response->getHeader());

        $this->assertSame(self::TRANS_ID_VALUE, $response->getHeader()->getTransId());
        $this->assertSame(self::PAY_ID_VALUE, $response->getHeader()->getPayId());
        $this->assertSame(self::X_ID_VALUE, $response->getHeader()->getXId());
        $this->assertSame(self::STATUS_VALUE, $response->getHeader()->getStatus());
        $this->assertSame(self::CODE_VALUE, $response->getHeader()->getCode());

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
        $mock = $this->createPartialMock(CaptureApiAdapter::class, ['sendRequest']);

        $mock->method('sendRequest')
            ->willReturn($this->getStream(self::DATA_CAPTURE_VALUE, self::LEN_CAPTURE_VALUE));

        return $mock;
    }

    /**
     * @return string
     */
    protected function getApiAdapterFunctionName()
    {
        return 'createCaptureAdapter';
    }

}

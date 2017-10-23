<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business\Api;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopReverseResponseTransfer;
use SprykerEco\Zed\Computop\Business\Api\Adapter\ReverseApiAdapter;
use SprykerEco\Zed\Computop\Business\ComputopFacade;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Business
 * @group ReversePaymentTest
 */
class ReversePaymentTest extends AbstractPaymentTest
{
    const DATA_REVERSE_VALUE = 'A23501461AA31D2A7DE3ABDBAB085C69079995CF003AAE802B1F6ED88960C444DA38BF186609D77937F48C789D95C374240BD049073027CAD356AF0EDD2D2D1EC5BFE71E5D72BF0C6896F77DDB488245DCDC858B4A88BB77F7E15FA3471C7A616BC07B6B5EEC3121AE739182FEA6B87DE18DA776D8C306BC7CC1110200055E956C7A8664D870F3ABF07857CD3937652E8610531FC7A8D301559CEED3BC0FA14971922F5D33B6964034477F769880CD30EF3E6BC7B3D2F199DB124B974F304C742DF697E4A3F784EBB63EFF9FA71CC905703A4AA73E1C751D1B4EB02D449C8AFA';
    const LEN_REVERSE_VALUE = 223;

    const PAY_ID_VALUE = '73723e2fbab14767914d044b9f540b72';
    const TRANS_ID_VALUE = 'f5187f1690d228ff74bc89346b7ffc2f';
    const X_ID_VALUE = '0aa09e11a3224a319026a51e3ef4d557';
    const STATUS_VALUE = 'OK';
    const CODE_VALUE = '00000000';

    /**
     * @return void
     */
    public function testReversePaymentSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $orderTransfer = $this->apiHelper->createOrderTransfer();
        $computopHeaderPaymentTransfer = $this->apiHelper->createComputopHeaderPaymentTransfer($this->getPayIdValue(), self::TRANS_ID_VALUE);

        /** @var \Generated\Shared\Transfer\ComputopReverseResponseTransfer $response */
        $response = $service->reversePaymentRequest($orderTransfer, $computopHeaderPaymentTransfer);

        $this->assertInstanceOf(ComputopReverseResponseTransfer::class, $response);
        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $response->getHeader());

        $this->assertSame(self::TRANS_ID_VALUE, $response->getHeader()->getTransId());
        $this->assertSame(self::PAY_ID_VALUE, $response->getHeader()->getPayId());
        $this->assertSame(self::STATUS_VALUE, $response->getHeader()->getStatus());
        $this->assertSame(self::CODE_VALUE, $response->getHeader()->getCode());
        $this->assertSame(self::X_ID_VALUE, $response->getHeader()->getXId());

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
        $mock = $this->createPartialMock(ReverseApiAdapter::class, ['sendRequest']);

        $mock->method('sendRequest')
            ->willReturn($this->getStream(self::DATA_REVERSE_VALUE, self::LEN_REVERSE_VALUE));

        return $mock;
    }

    /**
     * @return string
     */
    protected function getApiAdapterFunctionName()
    {
        return 'createReverseAdapter';
    }
}

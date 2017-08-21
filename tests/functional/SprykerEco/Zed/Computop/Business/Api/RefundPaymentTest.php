<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business\Api;

use Generated\Shared\Transfer\ComputopCreditCardRefundResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Zed\Computop\Business\Api\Adapter\RefundApiAdapter;
use SprykerEco\Zed\Computop\Business\ComputopFacade;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Business
 * @group RefundPaymentTest
 */
class RefundPaymentTest extends AbstractPaymentTest
{

    const DATA_REFUND_VALUE = 'A23501461AA31D2A7DE3ABDBAB085C69BA9E56B96CDF8D7E1D11289D7BBB9EA91C89BCEE9A870EF5E79EDC8EB013A02B04F268FFE54CAF2AEC985BF06992E8FBCFF6DB02BE9227731AD59EF6B3D24C1BB6285960A37E7FFA69ED6D8D2D4972BC03BA1611F8C8797832EFA0E00DCE63BA84275716D47B5431B25F09A5BA1C42A8EAFE2D3B67FD5C7BF07857CD3937652E8610531FC7A8D301559CEED3BC0FA14971922F5D33B6964034477F769880CD30EF3E6BC7B3D2F199DB124B974F304C742DF697E4A3F784EBB63EFF9FA71CC905703A4AA73E1C751D1B4EB02D449C8AFA';
    const LEN_REFUND_VALUE = 223;

    const PAY_ID_VALUE = '6ec94adcf5b44fb3bd614abcb6c0481a';
    const TRANS_ID_VALUE = 'cab95627e0e76651f0fe5d1bf3acda1b';
    const X_ID_VALUE = 'cb5d13432c244526b02af43d5e0dd308';
    const STATUS_VALUE = 'OK';
    const CODE_VALUE = '00000000';

    /**
     * @return void
     */
    public function testRefundPaymentSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());

        /** @var \Generated\Shared\Transfer\ComputopCreditCardRefundResponseTransfer $response */
        $response = $service->refundPaymentRequest($this->createOrderTransfer($this->getPayIdValue()));

        $this->assertInstanceOf(ComputopCreditCardRefundResponseTransfer::class, $response);
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
        $mock = $this->createPartialMock(RefundApiAdapter::class, ['sendRequest']);

        $mock->method('sendRequest')
            ->willReturn($this->getStream(self::DATA_REFUND_VALUE, self::LEN_REFUND_VALUE));

        return $mock;
    }

    /**
     * @return string
     */
    protected function getApiAdapterFunctionName()
    {
        return 'createRefundAdapter';
    }

}

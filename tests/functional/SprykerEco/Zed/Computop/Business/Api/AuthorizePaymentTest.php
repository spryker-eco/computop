<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business\Api;

use Generated\Shared\Transfer\ComputopAuthorizeResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Zed\Computop\Business\Api\Adapter\AuthorizeApiAdapter;
use SprykerEco\Zed\Computop\Business\ComputopFacade;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Business
 * @group AuthorizePaymentTest
 */
class AuthorizePaymentTest extends AbstractPaymentTest
{
    const DATA_AUTHORIZE_VALUE = 'A23501461AA31D2A7DE3ABDBAB085C698893913332CB67E1A807FD59E1F69EB838219E36BBA905CA9085CECB6F9719E1CDA2CE46F17120C106A58D32E2130F61199DE0A73DA1DD7BFD571C4C5DE099825FB9F264DBFA52BF863B3941BC327A15A99FF527422CE878508ACC02E13D9851024D23B99285545222833BCB861E6471E5712BDA3A493433F07857CD3937652E8610531FC7A8D301559CEED3BC0FA14971922F5D33B6964034477F769880CD30EF3E6BC7B3D2F199DB124B974F304C742DF697E4A3F784EBB63EFF9FA71CC905703A4AA73E1C751D1B4EB02D449C8AFA';
    const LEN_AUTHORIZE_VALUE = 223;

    const PAY_ID_VALUE = 'b5e798a99d5440e88ba487960f3f0cdc';
    const X_ID_VALUE = '09b0bf76bb4145d8bbe1bb752a736d6d';
    const TRANS_ID_VALUE = '0e1f2ee1a171fecdfa59833c2a0c0685';
    const STATUS_VALUE = 'OK';
    const CODE_VALUE = '00000000';

    const X_ID_ERROR_VALUE = '41810fbfb4e74e7cb05d06eb7fb7436c';
    const STATUS_ERROR_VALUE = 'FAILED';
    const CODE_ERROR_VALUE = '21000068';

    /**
     * @return void
     */
    public function testAuthorizePaymentSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $orderTransfer = $this->apiHelper->createOrderTransfer();
        $computopHeaderPaymentTransfer = $this->apiHelper->createComputopHeaderPaymentTransfer($this->getPayIdValue(), self::TRANS_ID_VALUE);

        /** @var \Generated\Shared\Transfer\ComputopAuthorizeResponseTransfer $response */
        $response = $service->authorizationPaymentRequest($orderTransfer, $computopHeaderPaymentTransfer);

        $this->assertInstanceOf(ComputopAuthorizeResponseTransfer::class, $response);
        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $response->getHeader());

        $this->assertSame(self::TRANS_ID_VALUE, $response->getHeader()->getTransId());
        $this->assertSame(self::X_ID_VALUE, $response->getHeader()->getXId());
        $this->assertSame(self::PAY_ID_VALUE, $response->getHeader()->getPayId());
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
        $mock = $this->createPartialMock(AuthorizeApiAdapter::class, ['sendRequest']);

        $mock->method('sendRequest')
            ->willReturn($this->getStream(self::DATA_AUTHORIZE_VALUE, self::LEN_AUTHORIZE_VALUE));

        return $mock;
    }

    /**
     * @return string
     */
    protected function getApiAdapterFunctionName()
    {
        return 'getAuthorizeAdapter';
    }
}

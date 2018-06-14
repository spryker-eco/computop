<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Oms;

use Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer;
use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use SprykerEco\Zed\Computop\Business\ComputopFacade;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeBridge;

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
    const DATA_AUTHORIZE_VALUE = '492afd99f603108b3998128e790fc435197fb8dd317e06a5b10e77122bdc3db2d47f841a0b131de5bca366820e7350c0c2d2baf5b06122397e65ebb120b65057b89c8889daa58fd506e6d5a09e150536966df7ddd21af27fddb5bb24f6023ec6c6868e6abb97eacc4523f3f4ae8048e3e3d2690f51d7df2ebf7706348bba60a1541ea2a7fee9dad76853f50e5eacd5542d58eb725583d75e016f3f100db3782ac3b2962593c378d52b9940ae715118e93781025825faae37f44968987cf23d216e5af2cca55dbd001bf5ba7f2883399a522c4a27b1f194337c0a57a277f24b08';
    const LEN_AUTHORIZE_VALUE = 223;

    const PAY_ID_VALUE = '5dd8384e42d340d092d51bb49eee46d5';
    const TRANS_ID_VALUE = 'e67c3a4d9691eda715bd689f62e6a912';
    const X_ID_VALUE = '2613ebb3c56d4b329e7f96c314e83720';
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
        $orderTransfer = $this->createOrderTransfer();
        $orderItems = $this->omsHelper->createOrderItems();

        /** @var \Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer $response */
        $response = $service->authorizeCommandHandle($orderItems, $orderTransfer);

        $this->assertInstanceOf(ComputopApiAuthorizeResponseTransfer::class, $response);
        $this->assertInstanceOf(ComputopApiResponseHeaderTransfer::class, $response->getHeader());

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
     * @return string
     */
    protected function getTransIdValue()
    {
        return self::TRANS_ID_VALUE;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|ComputopToComputopApiFacadeBridge
     */
    protected function createComputopApiFacade()
    {
        $stub = $this
            ->createPartialMock(
                ComputopToComputopApiFacadeBridge::class,
                [
                    'performAuthorizationRequest',
                ]
            );

        $stub->method('performAuthorizationRequest')
            ->willReturn($this->createAuhtorizeResponseTransfer());

        return $stub;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopApiAuthorizeResponseTransfer
     */
    protected function createAuhtorizeResponseTransfer()
    {
        return (new ComputopApiAuthorizeResponseTransfer())
            ->setHeader(
                (new ComputopApiResponseHeaderTransfer())
                    ->setTransId(self::TRANS_ID_VALUE)
                    ->setPayId(self::PAY_ID_VALUE)
                    ->setXId(self::X_ID_VALUE)
                    ->setCode(self::CODE_VALUE)
                    ->setIsSuccess(true)
                    ->setStatus(self::STATUS_VALUE)
            );
    }
}

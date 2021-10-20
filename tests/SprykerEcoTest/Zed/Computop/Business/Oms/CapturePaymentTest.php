<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Oms;

use Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer;
use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use SprykerEco\Zed\Computop\Business\ComputopFacade;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeBridge;

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
    /**
     * @var string
     */
    public const DATA_CAPTURE_VALUE = '492afd99f603108b3998128e790fc435197fb8dd317e06a5b10e77122bdc3db2d47f841a0b131de5bca366820e7350c0c2d2baf5b06122397e65ebb120b65057b89c8889daa58fd506e6d5a09e150536966df7ddd21af27fddb5bb24f6023ec6c6868e6abb97eacc4523f3f4ae8048e3e3d2690f51d7df2ebf7706348bba60a1541ea2a7fee9dad76853f50e5eacd5542d58eb725583d75e016f3f100db3782ac3b2962593c378d52b9940ae715118e93781025825faae37f44968987cf23d216e5af2cca55dbd001bf5ba7f2883399a522c4a27b1f194337c0a57a277f24b08';

    /**
     * @var int
     */
    public const LEN_CAPTURE_VALUE = 223;

    /**
     * @var string
     */
    public const PAY_ID_VALUE = '5dd8384e42d340d092d51bb49eee46d5';

    /**
     * @var string
     */
    public const TRANS_ID_VALUE = 'e67c3a4d9691eda715bd689f62e6a912';

    /**
     * @var string
     */
    public const X_ID_VALUE = '2613ebb3c56d4b329e7f96c314e83720';

    /**
     * @var string
     */
    public const STATUS_VALUE = 'OK';

    /**
     * @var string
     */
    public const CODE_VALUE = '00000000';

    /**
     * @return void
     */
    public function testCapturePaymentSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->createFactory());
        $orderTransfer = $this->createOrderTransfer();
        $orderItems = $this->omsHelper->createOrderItems();

        //todo: update test
        /** @var \Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer $response */
        $response = $service->captureCommandHandle($orderItems, $orderTransfer);

        $this->assertInstanceOf(ComputopApiCaptureResponseTransfer::class, $response);
        $this->assertInstanceOf(ComputopApiResponseHeaderTransfer::class, $response->getHeader());

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
     * @return string
     */
    protected function getTransIdValue()
    {
        return self::TRANS_ID_VALUE;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeBridge
     */
    protected function createComputopApiFacade(): ComputopToComputopApiFacadeBridge
    {
        $stub = $this
            ->createPartialMock(
                ComputopToComputopApiFacadeBridge::class,
                [
                    'performCaptureRequest',
                ]
            );

        $stub->method('performCaptureRequest')
            ->willReturn($this->createCaptureResponseTransfer());

        return $stub;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer
     */
    protected function createCaptureResponseTransfer()
    {
        return (new ComputopApiCaptureResponseTransfer())
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

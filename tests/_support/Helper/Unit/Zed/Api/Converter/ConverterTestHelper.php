<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Computop\Helper\Unit\Zed\Api\Converter;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use GuzzleHttp\Psr7;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceBridge;

class ConverterTestHelper extends Test
{

    /**
     * @return \GuzzleHttp\Psr7\Stream
     */
    public function prepareResponse()
    {
        $expectedResponse = '';
        $stream = Psr7\stream_for($expectedResponse);

        return $stream;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createComputopConfigMock()
    {
        $configMock = $this->createPartialMock(
            ComputopConfig::class,
            ['getBlowfishPass']
        );

        return $configMock;
    }

    /**
     * @return array
     */
    public function getMainDecryptedArray()
    {
        $decryptedArray = [
            ComputopConstants::MID_F_N => 'mid',
            ComputopConstants::PAY_ID_F_N => 'PayID',
            ComputopConstants::XID_F_N => 'XID',
            ComputopConstants::TRANS_ID_F_N => 'TransID',
            ComputopConstants::STATUS_F_N => 'OK',
            ComputopConstants::CODE_F_N => '00000000',
            ComputopConstants::DESCRIPTION_F_N => 'Description',
        ];

        return $decryptedArray;
    }

    /**
     * @param array $decryptedArray
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createComputopServiceMock(array $decryptedArray)
    {
        $computopServiceMock = $this->createPartialMock(
            ComputopToComputopServiceBridge::class,
            ['getDecryptedArray', 'extractHeader']
        );
        $computopServiceMock->method('getDecryptedArray')
            ->willReturn($decryptedArray);

        $computopServiceMock->method('extractHeader')
            ->willReturn($this->getHeaderResponseTransfer($decryptedArray));

        return $computopServiceMock;
    }

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    protected function getHeaderResponseTransfer(array $decryptedArray)
    {
        $method = ComputopConstants::AUTHORIZE_METHOD;

        $header = new ComputopResponseHeaderTransfer();
        $header->fromArray($decryptedArray, true);

        //different naming style
        $header->setMId($decryptedArray[ComputopConstants::MID_F_N]);
        $header->setTransId($decryptedArray[ComputopConstants::TRANS_ID_F_N]);
        $header->setPayId($decryptedArray[ComputopConstants::PAY_ID_F_N]);
        $header->setIsSuccess($header->getStatus() === ComputopConstants::SUCCESS_STATUS);
        $header->setMethod($method);
        $header->setXId($decryptedArray[ComputopConstants::XID_F_N]);

        return $header;
    }

}

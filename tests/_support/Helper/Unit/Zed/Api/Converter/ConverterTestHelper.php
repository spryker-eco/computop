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
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceBridge;

class ConverterTestHelper extends Test
{

    const AUTHORIZE_METHOD = 'AUTHORIZE';

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
            ComputopFieldNameConstants::MID => 'mid',
            ComputopFieldNameConstants::PAY_ID => 'PayID',
            ComputopFieldNameConstants::XID => 'XID',
            ComputopFieldNameConstants::TRANS_ID => 'TransID',
            ComputopFieldNameConstants::STATUS => 'OK',
            ComputopFieldNameConstants::CODE => '00000000',
            ComputopFieldNameConstants::DESCRIPTION => 'Description',
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
            ['getDecryptedArray', 'extractHeader', 'getResponseValue']
        );
        $computopServiceMock->method('getDecryptedArray')
            ->willReturn($decryptedArray);

        $computopServiceMock->method('extractHeader')
            ->willReturn($this->getHeaderResponseTransfer($decryptedArray));

        $computopServiceMock->method('getResponseValue')
            ->willReturn('testValue');

        return $computopServiceMock;
    }

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    protected function getHeaderResponseTransfer(array $decryptedArray)
    {
        $method = self::AUTHORIZE_METHOD;

        $header = new ComputopResponseHeaderTransfer();
        $header->fromArray($decryptedArray, true);

        //different naming style
        $header->setMId($decryptedArray[ComputopFieldNameConstants::MID]);
        $header->setTransId($decryptedArray[ComputopFieldNameConstants::TRANS_ID]);
        $header->setPayId($decryptedArray[ComputopFieldNameConstants::PAY_ID]);
        $header->setIsSuccess($header->getStatus() === ComputopConstants::SUCCESS_STATUS);
        $header->setMethod($method);
        $header->setXId($decryptedArray[ComputopFieldNameConstants::XID]);

        return $header;
    }

}

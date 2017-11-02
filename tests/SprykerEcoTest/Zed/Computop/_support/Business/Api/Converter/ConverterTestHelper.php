<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Api\Converter;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use GuzzleHttp\Psr7;
use SprykerEco\Service\Computop\ComputopService;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\ComputopConfig;

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
            ComputopApiConfig::MERCHANT_ID_SHORT => 'mid',
            ComputopApiConfig::PAY_ID => 'PayID',
            ComputopApiConfig::X_ID => 'XID',
            ComputopApiConfig::TRANS_ID => 'TransID',
            ComputopApiConfig::STATUS => 'OK',
            ComputopApiConfig::CODE => '00000000',
            ComputopApiConfig::DESCRIPTION => 'Description',
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
            ComputopService::class,
            ['getDecryptedArray', 'extractHeader', 'getResponseValue']
        );
        $computopServiceMock->method('getDecryptedArray')
            ->willReturn($decryptedArray);

        $computopServiceMock->method('extractHeader')
            ->willReturn($this->getHeaderResponseTransfer($decryptedArray));

        $computopServiceMock->method('getResponseValue')
            ->willReturnArgument(1);

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
        $header->setMId($decryptedArray[ComputopApiConfig::MERCHANT_ID_SHORT]);
        $header->setTransId($decryptedArray[ComputopApiConfig::TRANS_ID]);
        $header->setPayId($decryptedArray[ComputopApiConfig::PAY_ID]);
        $header->setIsSuccess($header->getStatus() === ComputopSharedConfig::SUCCESS_STATUS);
        $header->setMethod($method);
        $header->setXId($decryptedArray[ComputopApiConfig::X_ID]);

        return $header;
    }
}

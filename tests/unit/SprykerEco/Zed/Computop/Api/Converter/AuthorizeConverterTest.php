<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Computop\Api\Converter;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;

use GuzzleHttp\Psr7;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\Api\Converter\AuthorizeConverter;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceBridge;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Api
 * @group Converter
 * @group AuthorizeConverterTest
 */
class AuthorizeConverterTest extends Test
{

    const REF_NR_VALUE = 'RefNr';

    /**
     * @return void
     */
    public function testToTransactionResponseTransfer()
    {
        $response = $this->prepareResponse();
        $service = $this->createConverter();

        $responseTransfer = $service->toTransactionResponseTransfer($response);

        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $responseTransfer->getHeader());
        $this->assertEquals(self::REF_NR_VALUE, $responseTransfer->getRefNr());
    }

    /**
     * @return \GuzzleHttp\Psr7\Stream
     */
    protected function prepareResponse()
    {
        $expectedResponse = '';
        $stream = Psr7\stream_for($expectedResponse);

        return $stream;
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\AuthorizeConverter
     */
    protected function createConverter()
    {
        $computopServiceMock = $this->createComputopServiceMock();
        $configMock = $this->createComputopConfigMock();

        $converter = new AuthorizeConverter($computopServiceMock, $configMock);

        return $converter;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createComputopConfigMock()
    {
        $configMock = $this->createPartialMock(
            ComputopConfig::class,
            ['getBlowfishPass']
        );

        return $configMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createComputopServiceMock()
    {
        $computopServiceMock = $this->createPartialMock(
            ComputopToComputopServiceBridge::class,
            ['getDecryptedArray', 'extractHeader']
        );
        $computopServiceMock->method('getDecryptedArray')
            ->willReturn($this->getDecryptedArray());

        $computopServiceMock->method('extractHeader')
            ->willReturn($this->getHeaderResponseTransfer());

        return $computopServiceMock;
    }

    /**
     * @return array
     */
    protected function getDecryptedArray()
    {
        $decryptedArray = [
            'mid' => 'mid',
            'PayID' => 'PayID',
            'XID' => 'XID',
            'TransID' => 'TransID',
            'Status' => 'OK',
            'Code' => '00000000',
            'Description' => 'Description',
            'RefNr' => self::REF_NR_VALUE,
        ];

        return $decryptedArray;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    protected function getHeaderResponseTransfer()
    {
        $decryptedArray = $this->getDecryptedArray();
        $method = ComputopConstants::AUTHORIZE_METHOD;

        $header = new ComputopResponseHeaderTransfer();
        $header->fromArray($decryptedArray, true);

        //different naming style
        $header->setMId($decryptedArray[ComputopConstants::MID_F_N]);
        $header->setTransId($decryptedArray[ComputopConstants::TRANS_ID_F_N]);
        $header->setPayId($decryptedArray[ComputopConstants::PAY_ID_F_N]);
        $header->setIsSuccess($header->getStatus() == ComputopConstants::SUCCESS_STATUS);
        $header->setMethod($method);
        $header->setXId($decryptedArray[ComputopConstants::XID_F_N]);

        return $header;
    }

}

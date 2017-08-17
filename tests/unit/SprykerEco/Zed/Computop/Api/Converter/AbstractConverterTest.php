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
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceBridge;

abstract class AbstractConverterTest extends Test
{

    const REF_NR_VALUE = 'RefNr';
    const A_ID_VALUE = 'AID';
    const TRANSACTION_ID_VALUE = 'TransactionID';
    const AMOUNT_VALUE_ZERO = '0';
    const AMOUNT_VALUE_NOT_ZERO = '1';
    const CODE_EXT_VALUE = 'CodeExt';
    const ERROR_TEXT_VALUE = 'ErrorText';

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

    /**
     * @return array
     */
    protected function getMainDecryptedArray()
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

}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Computop\Api\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\Api\Converter\CaptureConverter;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Api
 * @group Converter
 * @group CaptureConverterTest
 */
class CaptureConverterTest extends AbstractConverterTest
{

    /**
     * @return void
     */
    public function testToTransactionResponseTransfer()
    {
        $response = $this->prepareResponse();
        $service = $this->createConverter();

        /** @var \Generated\Shared\Transfer\ComputopCreditCardCaptureResponseTransfer $responseTransfer */
        $responseTransfer = $service->toTransactionResponseTransfer($response);

        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $responseTransfer->getHeader());
        $this->assertEquals(self::A_ID_VALUE, $responseTransfer->getAId());
        $this->assertEquals(self::TRANSACTION_ID_VALUE, $responseTransfer->getTransactionId());
        $this->assertEquals(self::AMOUNT_VALUE_NOT_ZERO, $responseTransfer->getAmount());
        $this->assertEquals(self::CODE_EXT_VALUE, $responseTransfer->getCodeExt());
        $this->assertEquals(self::ERROR_TEXT_VALUE, $responseTransfer->getErrorText());
        $this->assertEquals(self::REF_NR_VALUE, $responseTransfer->getRefNr());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\CaptureConverter
     */
    protected function createConverter()
    {
        $computopServiceMock = $this->createComputopServiceMock();
        $configMock = $this->createComputopConfigMock();

        $converter = new CaptureConverter($computopServiceMock, $configMock);

        return $converter;
    }

    /**
     * @return array
     */
    protected function getDecryptedArray()
    {
        $decryptedArray = $this->getMainDecryptedArray();

        $decryptedArray[ComputopConstants::A_ID_F_N] = self::A_ID_VALUE;
        $decryptedArray[ComputopConstants::TRANSACTION_ID_F_N] = self::TRANSACTION_ID_VALUE;
        $decryptedArray[ComputopConstants::AMOUNT_F_N] = self::AMOUNT_VALUE_NOT_ZERO;
        $decryptedArray[ComputopConstants::CODE_EXT_F_N] = self::CODE_EXT_VALUE;
        $decryptedArray[ComputopConstants::ERROR_TEXT_F_N] = self::ERROR_TEXT_VALUE;
        $decryptedArray[ComputopConstants::REF_NR_F_N] = self::REF_NR_VALUE;

        return $decryptedArray;
    }

}

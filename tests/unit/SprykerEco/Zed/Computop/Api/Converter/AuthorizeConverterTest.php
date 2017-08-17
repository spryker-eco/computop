<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Computop\Api\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\Api\Converter\AuthorizeConverter;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Api
 * @group Converter
 * @group AuthorizeConverterTest
 */
class AuthorizeConverterTest extends AbstractConverterTest
{

    /**
     * @return void
     */
    public function testToTransactionResponseTransfer()
    {
        $response = $this->prepareResponse();
        $service = $this->createConverter();

        /** @var \Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer $responseTransfer */
        $responseTransfer = $service->toTransactionResponseTransfer($response);

        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $responseTransfer->getHeader());
        $this->assertEquals(self::REF_NR_VALUE, $responseTransfer->getRefNr());
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
     * @return array
     */
    protected function getDecryptedArray()
    {
        $decryptedArray = $this->getMainDecryptedArray();

        $decryptedArray[ComputopConstants::REF_NR_F_N] = self::REF_NR_VALUE;

        return $decryptedArray;
    }

}
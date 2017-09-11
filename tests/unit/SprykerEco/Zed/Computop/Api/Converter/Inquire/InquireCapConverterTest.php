<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Computop\Api\Converter\Inquire;

use Computop\Helper\Unit\Zed\Api\Converter\ConverterTestConstants;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Api
 * @group Converter
 * @group InquireConverterTest
 */
class InquireCapConverterTest extends AbstractInquireConverterTest
{

    /**
     * @return void
     */
    public function testToTransactionResponseTransfer()
    {
        $response = $this->helper->prepareResponse();
        $service = $this->createConverter();

        /** @var \Generated\Shared\Transfer\ComputopInquireResponseTransfer $responseTransfer */
        $responseTransfer = $service->toTransactionResponseTransfer($response);

        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $responseTransfer->getHeader());
        $this->assertTrue($responseTransfer->getIsCapLast());
    }

    /**
     * @return array
     */
    protected function getDecryptedArray()
    {
        $decryptedArray = $this->helper->getMainDecryptedArray();

        $decryptedArray[ComputopConstants::AMOUNT_AUTH_F_N] = ConverterTestConstants::AMOUNT_VALUE_NOT_ZERO;
        $decryptedArray[ComputopConstants::AMOUNT_CAP_F_N] = ConverterTestConstants::AMOUNT_VALUE_NOT_ZERO;
        $decryptedArray[ComputopConstants::AMOUNT_CRED_F_N] = ConverterTestConstants::AMOUNT_VALUE_ZERO;

        return $decryptedArray;
    }

}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Service\Computop\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Service\Computop\Exception\ComputopConverterException;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;
use Unit\SprykerEco\Service\Computop\AbstractComputopTest;

/**
 * @group Unit
 * @group SprykerEco
 * @group Service
 * @group Computop
 * @group Api
 * @group Converter
 * @group ComputopConverterTest
 */
class ComputopConverterTest extends AbstractComputopTest
{

    const METHOD = 'AUTHORIZE';

    const PAY_ID_VALUE = 'PAY_ID_VALUE';
    const TRANS_ID_VALUE = 'TRANS_ID_VALUE';
    const MERCHANT_ID_VALUE = 'MERCHANT_ID_VALUE';
    const X_ID_VALUE = 'X_ID_VALUE';
    const CODE_VALUE = 'CODE_VALUE';
    const STATUS_ERROR_VALUE = 'STATUS_ERROR_VALUE';

    /**
     * @return void
     */
    public function testExtractIsSuccessHeader()
    {
        $converter = $this->helper->createConverter();
        $decryptedArray = $this->getDecryptedArray(ComputopConstants::SUCCESS_STATUS);

        /** @var \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header */
        $header = $converter->extractHeader($decryptedArray, self::METHOD);

        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $header);
        $this->assertTrue($header->getIsSuccess());

        //todo:update test
        $this->assertSame(ComputopFieldNameConstants::MID, $header->getMId());
        $this->assertSame(ComputopFieldNameConstants::TRANS_ID, $header->getTransId());
        $this->assertSame(ComputopFieldNameConstants::PAY_ID, $header->getPayId());
        $this->assertSame(ComputopConstants::SUCCESS_STATUS, $header->getStatus());
        $this->assertSame(ComputopFieldNameConstants::CODE, $header->getCode());
        $this->assertSame(ComputopFieldNameConstants::XID, $header->getXId());
    }

    /**
     * @return void
     */
    public function testExtractIsErrorHeader()
    {
        $converter = $this->helper->createConverter();
        $decryptedArray = $this->getDecryptedArray(self::STATUS_ERROR_VALUE);

        /** @var \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header */
        $header = $converter->extractHeader($decryptedArray, self::METHOD);

        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $header);
        $this->assertFalse($header->getIsSuccess());

        $this->assertSame(ComputopFieldNameConstants::MID, $header->getMId());
        $this->assertSame(ComputopFieldNameConstants::TRANS_ID_VALUE, $header->getTransId());
        $this->assertSame(ComputopFieldNameConstants::PAY_ID_VALUE, $header->getPayId());
        $this->assertSame(self::STATUS_ERROR_VALUE, $header->getStatus());
        $this->assertSame(ComputopFieldNameConstants::CODE, $header->getCode());
        $this->assertSame(ComputopFieldNameConstants::X_ID_VALUE, $header->getXId());
    }

    /**
     * @return void
     */
    public function testCheckEncryptedResponse()
    {
        $converter = $this->helper->createConverter();

        $responseArray = [
            ComputopFieldNameConstants::DATA => 'data',
            ComputopFieldNameConstants::LENGTH => 4,
        ];

        $converter->checkEncryptedResponse($responseArray);
    }

    /**
     * @return void
     */
    public function testCheckEncryptedResponseError()
    {
        $this->expectException(ComputopConverterException::class);

        $converter = $this->helper->createConverter();
        $converter->checkEncryptedResponse([]);
    }

    /**
     * @param string $status
     *
     * @return array
     */
    protected function getDecryptedArray($status)
    {
        $decryptedArray = [
            ComputopFieldNameConstants::MID => ComputopFieldNameConstants::MID,
            ComputopFieldNameConstants::TRANS_ID => ComputopFieldNameConstants::TRANS_ID,
            ComputopFieldNameConstants::PAY_ID => ComputopFieldNameConstants::PAY_ID,
            ComputopFieldNameConstants::STATUS => $status,
            ComputopFieldNameConstants::CODE => ComputopFieldNameConstants::CODE,
            ComputopFieldNameConstants::XID => ComputopFieldNameConstants::XID,
        ];

        return $decryptedArray;
    }

}

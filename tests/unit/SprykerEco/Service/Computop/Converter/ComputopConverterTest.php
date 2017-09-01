<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Service\Computop\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Service\Computop\Exception\ComputopConverterException;
use SprykerEco\Shared\Computop\ComputopConstants;
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
    const CODE_VALUE = '000000';
    const STATUS_ERROR_VALUE = 'ERROR';

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

        $this->assertSame(self::MERCHANT_ID_VALUE, $header->getMId());
        $this->assertSame(self::TRANS_ID_VALUE, $header->getTransId());
        $this->assertSame(self::PAY_ID_VALUE, $header->getPayId());
        $this->assertSame(ComputopConstants::SUCCESS_STATUS, $header->getStatus());
        $this->assertSame(self::CODE_VALUE, $header->getCode());
        $this->assertSame(self::X_ID_VALUE, $header->getXId());
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

        $this->assertSame(self::MERCHANT_ID_VALUE, $header->getMId());
        $this->assertSame(self::TRANS_ID_VALUE, $header->getTransId());
        $this->assertSame(self::PAY_ID_VALUE, $header->getPayId());
        $this->assertSame(self::STATUS_ERROR_VALUE, $header->getStatus());
        $this->assertSame(self::CODE_VALUE, $header->getCode());
        $this->assertSame(self::X_ID_VALUE, $header->getXId());
    }

    /**
     * @return void
     */
    public function testCheckEncryptedResponse()
    {
        $converter = $this->helper->createConverter();

        $responseArray = [
            ComputopConstants::DATA_F_N => 'data',
            ComputopConstants::LENGTH_F_N => 4,
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
            ComputopConstants::MID_F_N => self::MERCHANT_ID_VALUE,
            ComputopConstants::TRANS_ID_F_N => self::TRANS_ID_VALUE,
            ComputopConstants::PAY_ID_F_N => self::PAY_ID_VALUE,
            ComputopConstants::STATUS_F_N => $status,
            ComputopConstants::CODE_F_N => self::CODE_VALUE,
            ComputopConstants::XID_F_N => self::X_ID_VALUE,
        ];

        return $decryptedArray;
    }

}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Service\Computop\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Service\Computop\Exception\ComputopConverterException;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEcoTest\Service\Computop\AbstractComputopTest;

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
        $decryptedArray = $this->getDecryptedArray(ComputopConfig::SUCCESS_STATUS);

        /** @var \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header */
        $header = $converter->extractHeader($decryptedArray, self::METHOD);

        $this->assertInstanceOf(ComputopResponseHeaderTransfer::class, $header);
        $this->assertTrue($header->getIsSuccess());

        //todo:update test
        $this->assertSame(ComputopApiConfig::MERCHANT_ID_SHORT, $header->getMId());
        $this->assertSame(ComputopApiConfig::TRANS_ID, $header->getTransId());
        $this->assertSame(ComputopApiConfig::PAY_ID, $header->getPayId());
        $this->assertSame(ComputopConfig::SUCCESS_STATUS, $header->getStatus());
        $this->assertSame(ComputopApiConfig::CODE, $header->getCode());
        $this->assertSame(ComputopApiConfig::X_ID, $header->getXId());
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

        $this->assertSame(ComputopApiConfig::MERCHANT_ID_SHORT, $header->getMId());
        $this->assertSame(ComputopApiConfig::TRANS_ID, $header->getTransId());
        $this->assertSame(ComputopApiConfig::PAY_ID, $header->getPayId());
        $this->assertSame(self::STATUS_ERROR_VALUE, $header->getStatus());
        $this->assertSame(ComputopApiConfig::CODE, $header->getCode());
        $this->assertSame(ComputopApiConfig::X_ID, $header->getXId());
    }

    /**
     * @return void
     */
    public function testCheckEncryptedResponse()
    {
        $converter = $this->helper->createConverter();

        $responseArray = [
            ComputopApiConfig::DATA => 'data',
            ComputopApiConfig::LENGTH => 4,
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
            ComputopApiConfig::MERCHANT_ID_SHORT => ComputopApiConfig::MERCHANT_ID_SHORT,
            ComputopApiConfig::TRANS_ID => ComputopApiConfig::TRANS_ID,
            ComputopApiConfig::PAY_ID => ComputopApiConfig::PAY_ID,
            ComputopApiConfig::STATUS => $status,
            ComputopApiConfig::CODE => ComputopApiConfig::CODE,
            ComputopApiConfig::X_ID => ComputopApiConfig::X_ID,
        ];

        return $decryptedArray;
    }
}

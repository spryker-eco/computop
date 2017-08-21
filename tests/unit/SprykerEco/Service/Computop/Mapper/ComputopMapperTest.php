<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Service\Computop\Mapper;

/**
 * @group Unit
 * @group SprykerEco
 * @group Service
 * @group Computop
 * @group Api
 * @group Mapper
 * @group ComputopMapperTest
 */
class ComputopMapperTest extends AbstractMapperTest
{

    /**
     * @return void
     */
    public function testMacValueMapper()
    {
        $mapper = $this->createMapper();
        $cardPaymentTransfer = $this->createComputopPaymentTransfer();
        $macValue = $mapper->getMacEncryptedValue($cardPaymentTransfer);

        $this->assertSame(self::EXPECTED_MAC, $macValue);
    }

    /**
     * @return void
     */
    public function testMacResponseValueMapper()
    {
        $mapper = $this->createMapper();
        $cardPaymentResponseTransfer = $this->createComputopResponseHeaderTransfer();
        $macValue = $mapper->getMacResponseEncryptedValue($cardPaymentResponseTransfer);

        $this->assertSame(self::EXPECTED_MAC_RESPONSE, $macValue);
    }

    /**
     * @return void
     */
    public function testPlaintextMapper()
    {
        $mapper = $this->createMapper();
        $arrayForPlaintext = ['test_key' => 'test_value'];
        $plaintext = $mapper->getDataPlaintext($arrayForPlaintext);

        $this->assertSame(self::EXPECTED_PLAINTEXT, $plaintext);
    }

}

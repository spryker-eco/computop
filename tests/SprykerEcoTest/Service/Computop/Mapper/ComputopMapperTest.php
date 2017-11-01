<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Service\Computop\Mapper;

use SprykerEcoTest\Service\Computop\AbstractComputopTest;

/**
 * @group Unit
 * @group SprykerEco
 * @group Service
 * @group Computop
 * @group Api
 * @group Mapper
 * @group ComputopMapperTest
 */
class ComputopMapperTest extends AbstractComputopTest
{
    /**
     * @return void
     */
    public function testMacValueMapper()
    {
        $mapper = $this->helper->createMapper();
        $cardPaymentTransfer = $this->mapperHelper->createComputopPaymentTransfer();
        $macValue = $mapper->getMacEncryptedValue($cardPaymentTransfer);

        $this->assertSame(ComputopMapperTestConstants::EXPECTED_MAC, $macValue);
    }

    /**
     * @return void
     */
    public function testMacResponseValueMapper()
    {
        $mapper = $this->helper->createMapper();
        $cardPaymentResponseTransfer = $this->mapperHelper->createComputopResponseHeaderTransfer();
        $macValue = $mapper->getMacResponseEncryptedValue($cardPaymentResponseTransfer);

        $this->assertSame(ComputopMapperTestConstants::EXPECTED_MAC_RESPONSE, $macValue);
    }

    /**
     * @return void
     */
    public function testPlaintextMapper()
    {
        $mapper = $this->helper->createMapper();
        $arrayForPlaintext = ['test_key' => 'test_value'];
        $plaintext = $mapper->getDataPlainText($arrayForPlaintext);

        $this->assertSame(ComputopMapperTestConstants::EXPECTED_PLAINTEXT, $plaintext);
    }
}

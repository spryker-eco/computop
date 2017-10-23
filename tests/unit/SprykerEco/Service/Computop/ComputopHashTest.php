<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Service\Computop;

use SprykerEco\Service\Computop\Model\HmacHasher;

/**
 * @group Unit
 * @group SprykerEco
 * @group Service
 * @group Computop
 * @group Api
 * @group ComputopHashTest
 */
class ComputopHashTest extends AbstractComputopTest
{
    const VALUE = 'value';
    const EXPECTED_VALUE = '18B40402BBF3FEED824E9F762B412F1108C4D678B2A067425B99963CD68B1D6A';

    /**
     * @return void
     */
    public function testHashCrypt()
    {
        $service = $this->createService();
        $encryptedValue = $service->getEncryptedValue(self::VALUE);

        $this->assertSame(self::EXPECTED_VALUE, $encryptedValue);
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\HmacHasherInterface
     */
    public function createService()
    {
        return new HmacHasher($this->helper->createComputopConfigMock());
    }
}

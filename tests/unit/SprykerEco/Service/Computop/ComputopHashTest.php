<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Service\Computop;

use Codeception\TestCase\Test;
use SprykerEco\Service\Computop\Model\HashHmac;

/**
 * @group Unit
 * @group SprykerEco
 * @group Service
 * @group Computop
 * @group Api
 * @group ComputopHashTest
 */
class ComputopHashTest extends Test
{

    const VALUE = 'value';
    const EXPECTED_VALUE = '4BA185559569C2D0660D1D06782893D5436F4F98AD64A3CE8BAA8E7919DD919E';

    /**
     * @return void
     */
    public function testHashCrypt()
    {
        $service = $this->createService();
        $encryptedValue = $service->getHashHmacValue(self::VALUE);

        $this->assertSame(self::EXPECTED_VALUE, $encryptedValue);
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\HashHmac
     */
    public function createService()
    {
        return new HashHmac();
    }

}

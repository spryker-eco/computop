<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Service\Computop;

use SprykerEco\Service\Computop\Exception\BlowfishException;
use SprykerEco\Service\Computop\Model\BlowfishHasher;

/**
 * @group Unit
 * @group SprykerEco
 * @group Service
 * @group Computop
 * @group Api
 * @group ComputopBlowfishTest
 */
class ComputopBlowfishTest extends AbstractComputopTest
{

    const PLAINTEXT_VALUE = 'plaintext';
    const LENGTH_VALUE = 9;
    const PASSWORD_VALUE = 'password';

    const CIPHER_VALUE = '14ec7a6da0fbb3e50a84b47302443328';
    const CIPHER_LENGTH_VALUE = 16;

    /**
     * @return void
     */
    public function testBlowfishCrypt()
    {
        $service = $this->createService();
        $encryptedValue = $service->getBlowfishEncryptedValue(
            self::PLAINTEXT_VALUE,
            self::LENGTH_VALUE,
            self::PASSWORD_VALUE
        );

        $this->assertSame(self::CIPHER_VALUE, $encryptedValue);
    }

    /**
     * @return void
     */
    public function testBlowfishCryptError()
    {
        $this->expectException(BlowfishException::class);

        $service = $this->createService();
        $service->getBlowfishEncryptedValue(
            self::PLAINTEXT_VALUE,
            (self::LENGTH_VALUE - 1),
            self::PASSWORD_VALUE
        );
    }

    /**
     * @return void
     */
    public function testBlowfishDecrypt()
    {
        $service = $this->createService();
        $decryptedValue = $service->getBlowfishDecryptedValue(
            self::CIPHER_VALUE,
            self::CIPHER_LENGTH_VALUE,
            self::PASSWORD_VALUE
        );

        $this->assertSame(self::PLAINTEXT_VALUE, trim($decryptedValue));
    }

    /**
     * @return void
     */
    public function testBlowfishDecryptError()
    {
        $this->expectException(BlowfishException::class);

        $service = $this->createService();
        $service->getBlowfishDecryptedValue(
            self::CIPHER_VALUE,
            self::CIPHER_LENGTH_VALUE + 1,
            self::PASSWORD_VALUE
        );
    }

    /**
     * @return \SprykerEco\Service\Computop\Model\BlowfishHasherInterface
     */
    public function createService()
    {
        return new BlowfishHasher();
    }

}

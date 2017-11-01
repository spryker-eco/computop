<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

interface BlowfishHasherInterface
{
    /**
     * @param string $plainText
     * @param int $length
     * @param string $password
     *
     * @throws \SprykerEco\Service\Computop\Exception\BlowfishException
     *
     * @return string
     */
    public function getBlowfishEncryptedValue($plainText, $length, $password);

    /**
     * @param string $cipher
     * @param int $length
     * @param string $password
     *
     * @throws \SprykerEco\Service\Computop\Exception\BlowfishException
     *
     * @return string
     */
    public function getBlowfishDecryptedValue($cipher, $length, $password);
}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

interface BlowfishInterface
{

    /**
     * @param string $plaintext
     * @param int $len
     * @param string $password
     *
     * @throws \SprykerEco\Service\Computop\Exception\BlowfishException
     *
     * @return string
     */
    public function getBlowfishEncryptedValue($plaintext, $len, $password);

    /**
     * @param string $cipher
     * @param int $len
     * @param string $password
     *
     * @throws \SprykerEco\Service\Computop\Exception\BlowfishException
     *
     * @return string
     */
    public function getBlowfishDecryptedValue($cipher, $len, $password);

}

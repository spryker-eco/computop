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
     * @param string $password
     *
     * @return string
     */
    public function blowfishEncryptedValue($plaintext, $password);

}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

interface ComputopConfigInterface
{
    /**
     * @param string $method
     *
     * @return bool
     */
    public function isMacRequired($method);

    /**
     * @return string
     */
    public function getHmacPassword();

    /**
     * @return bool
     */
    public function isTestMode();
}

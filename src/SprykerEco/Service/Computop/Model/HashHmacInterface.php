<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

interface HashHmacInterface
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function getHashHmacValue($value);

}

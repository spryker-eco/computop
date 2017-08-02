<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Converter;

interface ComputopInterface
{

    /**
     * @param array $computopResponseArray
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardResponseTransfer
     */
    public function getComputopResponseTransfer($computopResponseArray);

}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;

interface ComputopInterface
{

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function extractHeader($decryptedArray);

    /**
     * @param string $decryptedString
     *
     * @return array
     */
    public function getResponseDecryptedArray($decryptedString);

    /**
     * @param array $responseArray
     *
     * @throws \SprykerEco\Service\Computop\Exception\ComputopConverterException
     *
     * @return void
     */
    public function checkEncryptedResponse($responseArray);

    /**
     * @param $responseMac
     * @param $neededMac
     *
     * @throws \SprykerEco\Service\Computop\Exception\ComputopConverterException
     *
     * @return void
     */
    public function checkMacResponse($responseMac, $neededMac);

}

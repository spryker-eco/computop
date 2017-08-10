<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Converter;

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

}

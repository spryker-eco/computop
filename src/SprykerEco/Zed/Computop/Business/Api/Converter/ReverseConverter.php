<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopCreditCardReverseResponseTransfer;
use GuzzleHttp\Psr7\Stream;
use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConstants;

class ReverseConverter extends AbstractConverter implements ConverterInterface
{

    /**
     * @param \GuzzleHttp\Psr7\Stream $response
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardReverseResponseTransfer
     */
    public function toTransactionResponseTransfer(Stream $response)
    {
        parse_str($response->getContents(), $responseArray);

        $decryptedArray = $this
            ->computopService
            ->getDecryptedArray($responseArray, Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD_KEY));

        return $this->getResponseTransfer($decryptedArray);
    }

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardReverseResponseTransfer
     */
    protected function getResponseTransfer($decryptedArray)
    {
        $computopCreditCardResponseTransfer = new ComputopCreditCardReverseResponseTransfer();

        $computopCreditCardResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray)
        );

        return $computopCreditCardResponseTransfer;
    }

}

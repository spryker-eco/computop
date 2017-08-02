<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use GuzzleHttp\Psr7\Stream;

class AuthorizeConverter extends AbstractConverter
{

    /**
     * @param \GuzzleHttp\Psr7\Stream $response
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardResponseTransfer
     */
    public function toTransactionResponseTransfer(Stream $response)
    {
        parse_str($response->getContents(), $computopResponseArray);
        $computopCreditCardResponseTransfer = $this->computopService->getComputopResponseTransfer($computopResponseArray);

        return $computopCreditCardResponseTransfer;
    }

}

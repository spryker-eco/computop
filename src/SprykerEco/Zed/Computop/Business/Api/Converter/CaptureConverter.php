<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer;
use GuzzleHttp\Psr7\Stream;
use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConstants;

class CaptureConverter extends AbstractConverter implements AuthorizeConverterInterface
{

    /**
     * @param \GuzzleHttp\Psr7\Stream $response
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer
     */
    public function toTransactionResponseTransfer(Stream $response)
    {
        parse_str($response->getContents(), $responseArray);

        $decryptedArray = $this
            ->computopService
            ->getDecryptedArray($responseArray, Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD));

        return $this->getResponseTransfer($decryptedArray);
    }

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardAuthorizeResponseTransfer
     */
    protected function getResponseTransfer($decryptedArray)
    {
        $computopCreditCardResponseTransfer = new ComputopCreditCardAuthorizeResponseTransfer();

        $computopCreditCardResponseTransfer->setMid($decryptedArray[ComputopConstants::MID_F_N]);
        $computopCreditCardResponseTransfer->setPayId($decryptedArray[ComputopConstants::PAY_ID_F_N]);
        $computopCreditCardResponseTransfer->setTransId($decryptedArray[ComputopConstants::TRANS_ID_F_N]);
        $computopCreditCardResponseTransfer->setStatus($decryptedArray[ComputopConstants::STATUS_F_N]);
        $computopCreditCardResponseTransfer->setDescription($decryptedArray[ComputopConstants::DESCRIPTION_F_N]);
        $computopCreditCardResponseTransfer->setCode($decryptedArray[ComputopConstants::CODE_F_N]);

        //optional
        $computopCreditCardResponseTransfer->setRefNr(
            isset($decryptedDataArray[ComputopConstants::REF_NR_F_N]) ? $decryptedDataArray[ComputopConstants::REF_NR_F_N] : null
        );

        //only if success
        $computopCreditCardResponseTransfer->setXid(
            isset($decryptedDataArray[ComputopConstants::XID_F_N]) ? $decryptedDataArray[ComputopConstants::XID_F_N] : null
        );

        $computopCreditCardResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray)
        );

        return $computopCreditCardResponseTransfer;
    }

}

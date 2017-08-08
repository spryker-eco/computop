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

class CaptureConverter extends AbstractConverter implements ConverterInterface
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
            ->getDecryptedArray($responseArray, Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD_KEY));

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

        $computopCreditCardResponseTransfer->fromArray($decryptedArray, true);

        //different naming style
        $computopCreditCardResponseTransfer->setMId($decryptedArray[ComputopConstants::MID_F_N]);
        $computopCreditCardResponseTransfer->setPayId($decryptedArray[ComputopConstants::PAY_ID_F_N]);
        $computopCreditCardResponseTransfer->setTransId($decryptedArray[ComputopConstants::TRANS_ID_F_N]);
        $computopCreditCardResponseTransfer->setAId($decryptedArray[ComputopConstants::TRANS_ID_F_N]);

        //only if success
        $computopCreditCardResponseTransfer->setXId(
            isset($decryptedDataArray[ComputopConstants::XID_F_N]) ? $decryptedDataArray[ComputopConstants::XID_F_N] : null
        );

        //optional fields
        $computopCreditCardResponseTransfer->setAId(
            isset($decryptedDataArray[ComputopConstants::A_ID_F_N]) ? $decryptedDataArray[ComputopConstants::A_ID_F_N] : null
        );
        $computopCreditCardResponseTransfer->setTransactionId(
            isset($decryptedDataArray[ComputopConstants::TRANSACTION_ID_F_N]) ? $decryptedDataArray[ComputopConstants::TRANSACTION_ID_F_N] : null
        );

        $computopCreditCardResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray)
        );

        return $computopCreditCardResponseTransfer;
    }

}

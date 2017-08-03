<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Service\Computop\Model\AbstractComputop;
use SprykerEco\Shared\Computop\ComputopConstants;

class Computop extends AbstractComputop implements ComputopInterface
{

    const SUCCESS_STATUS = 'OK';

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function extractHeader($decryptedArray)
    {
        $header = new ComputopResponseHeaderTransfer();

        $header->setStatus($decryptedArray[ComputopConstants::STATUS_F_N]);
        $header->setDescription($decryptedArray[ComputopConstants::DESCRIPTION_F_N]);
        $header->setCode($decryptedArray[ComputopConstants::CODE_F_N]);
        $header->setTransId($decryptedArray[ComputopConstants::TRANS_ID_F_N]);
        $header->setPayId($decryptedArray[ComputopConstants::PAY_ID_F_N]);
        $header->setXid($decryptedArray[ComputopConstants::XID_F_N]);

        $header->setIsSuccess($header->getStatus() == self::SUCCESS_STATUS);

        return $header;
    }

    /**
     * @param string $decryptedString
     *
     * @return array
     */
    public function getResponseDecryptedArray($decryptedString)
    {
        $decryptedDataArray = [];
        $decryptedDataSubArray = explode(self::DATA_SEPARATOR, $decryptedString);
        foreach ($decryptedDataSubArray as $value) {
            $data = explode(self::DATA_SUB_SEPARATOR, $value);
            $decryptedDataArray[array_shift($data)] = array_shift($data);
        }

        return $decryptedDataArray;
    }

}

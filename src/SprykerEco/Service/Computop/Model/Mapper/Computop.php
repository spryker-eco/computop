<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Mapper;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Spryker\Shared\Config\Config;
use SprykerEco\Service\Computop\Model\AbstractComputop;
use SprykerEco\Shared\Computop\ComputopConstants;

class Computop extends AbstractComputop implements ComputopInterface
{

    const ITEMS_SEPARATOR = '|';
    const ATTRIBUTES_SEPARATOR = ':';

    /**
     * @inheritdoc
     */
    public function getMacEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $macDataArray = [
            $cardPaymentTransfer->getPayId(),
            $cardPaymentTransfer->getTransId(),
            $cardPaymentTransfer->getMerchantId(),
            $cardPaymentTransfer->getAmount(),
            $cardPaymentTransfer->getCurrency(),
        ];

        return implode(self::MAC_SEPARATOR, $macDataArray);
    }

    /**
     * @inheritdoc
     */
    public function getMacResponseEncryptedValue(ComputopResponseHeaderTransfer $header)
    {
        $macDataArray = [
            $header->getPayId(),
            $header->getTransId(),
            $header->getMId(),
            $header->getStatus(),
            $header->getCode(),
        ];

        return implode(self::MAC_SEPARATOR, $macDataArray);
    }

    /**
     * @inheritdoc
     */
    public function getDataPlaintext(array $dataSubArray)
    {
        $dataArray = [];
        foreach ($dataSubArray as $key => $value) {
            $dataArray[] = implode(self::DATA_SUB_SEPARATOR, [$key, $value]);
        }

        return implode(self::DATA_SEPARATOR, $dataArray);
    }

    /**
     * @inheritdoc
     */
    public function getDescriptionValue(array $items)
    {
        $description = '';

        if (Config::get(ComputopConstants::COMPUTOP_TEST_ENABLED_KEY)) {
            $description = ComputopConstants::ORDER_DESC_SUCCESS;
        }

        foreach ($items as $item) {
            $description .= self::ITEMS_SEPARATOR;
            $description .= 'Name:' . $item->getName();
            $description .= self::ATTRIBUTES_SEPARATOR . 'Sku:' . $item->getSku();
            $description .= self::ATTRIBUTES_SEPARATOR . 'Quantity:' . $item->getQuantity();
        }

        return $description;
    }

}

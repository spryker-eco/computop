<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Mapper;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Service\Computop\Model\AbstractComputop;
use SprykerEco\Shared\Computop\ComputopConstants;

class Computop extends AbstractComputop implements ComputopInterface
{

    const ITEMS_SEPARATOR = '|';
    const ATTRIBUTES_SEPARATOR = '-';

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacEncryptedValue(TransferInterface $cardPaymentTransfer)
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
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return string
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
     * @param array $dataSubArray
     *
     * @return string
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
     * @param array $items
     *
     * @return string
     */
    public function getTestModeDescriptionValue(array $items)
    {
        $description = '';

        if ($this->config->isTestMode()) {
            $description = ComputopConstants::ORDER_DESC_SUCCESS;
            $description .= self::ITEMS_SEPARATOR;
        }

        $description .= $this->getDescriptionValue($items);

        return $description;
    }

    /**
     * @param array $items
     *
     * @return string
     */
    public function getDescriptionValue(array $items)
    {
        $description = '';

        foreach ($items as $item) {
            $description .= 'Name:' . $item->getName();
            $description .= self::ATTRIBUTES_SEPARATOR . 'Sku:' . $item->getSku();
            $description .= self::ATTRIBUTES_SEPARATOR . 'Quantity:' . $item->getQuantity();
        }

        return $description;
    }

}

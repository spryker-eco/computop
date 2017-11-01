<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Mapper;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Service\Computop\Model\AbstractComputop;

class ComputopMapper extends AbstractComputop implements ComputopMapperInterface
{
    const ITEMS_SEPARATOR = '|';
    const ATTRIBUTES_SEPARATOR = '-';

    const ORDER_DESC_SUCCESS = 'Test:0000';
    const ORDER_DESC_ERROR = 'Test:0305';

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacEncryptedValue(TransferInterface $cardPaymentTransfer)
    {
        $macDataArray = [
            $cardPaymentTransfer->getPayId(),
            $cardPaymentTransfer->requireTransId()->getTransId(),
            $cardPaymentTransfer->requireMerchantId()->getMerchantId(),
            $cardPaymentTransfer->requireAmount()->getAmount(),
            $cardPaymentTransfer->requireCurrency()->getCurrency(),
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
            $header->requirePayId()->getPayId(),
            $header->requireTransId()->getTransId(),
            $header->requireMId()->getMId(),
            $header->requireStatus()->getStatus(),
            $header->requireCode()->getCode(),
        ];

        return implode(self::MAC_SEPARATOR, $macDataArray);
    }

    /**
     * @param array $dataSubArray
     *
     * @return string
     */
    public function getDataPlainText(array $dataSubArray)
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
            $description = self::ORDER_DESC_SUCCESS;
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
            $description .= self::ITEMS_SEPARATOR;
        }

        return $description;
    }
}
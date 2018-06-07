<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Mapper;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Service\UtilText\Model\Hash;
use Spryker\Service\UtilText\UtilTextServiceInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Service\Computop\ComputopConfigInterface;
use SprykerEco\Service\Computop\Model\AbstractComputop;

class ComputopMapper extends AbstractComputop implements ComputopMapperInterface
{
    const ITEMS_SEPARATOR = '|';
    const ATTRIBUTES_SEPARATOR = '-';
    const REQ_ID_LENGTH = 32;

    protected $textService;

    /**
     * @param \SprykerEco\Service\Computop\ComputopConfigInterface $config
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $textService
     */
    public function __construct(
        ComputopConfigInterface $config,
        UtilTextServiceInterface $textService
    ) {
        parent::__construct($config);

        $this->textService = $textService;
    }

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

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return string
     */
    public function generateReqId(TransferInterface $transfer)
    {
        $parameters = [
            $this->createUniqueSalt(),
            $transfer->getTotals()->getHash(),
            $transfer->getCustomer()->getCustomerReference(),
        ];
        $string = $this->textService->hashValue(implode(self::ATTRIBUTES_SEPARATOR, $parameters), Hash::SHA256);

        return substr($string, 0, self::REQ_ID_LENGTH);
    }

    /**
     * @return int
     */
    protected function createUniqueSalt()
    {
        return time();
    }
}

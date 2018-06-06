<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\RiskCheck;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class CrifMapper extends AbstractRiskCheckMapper implements ApiRiskCheckMapperInterface
{
    const TRANS_ID_LIMIT = 64;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getDataSubArray(QuoteTransfer $quoteTransfer)
    {
        $dataSubArray[ComputopApiConfig::MERCHANT_ID] = $this->config->getMerchantId();
        $dataSubArray[ComputopApiConfig::TRANS_ID] = $this->getLimitedTransId($quoteTransfer, self::TRANS_ID_LIMIT);
        $dataSubArray[ComputopApiConfig::ORDER_DESC] = $this->computopService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy());
        $dataSubArray[ComputopApiConfig::AMOUNT] = $quoteTransfer->getTotals()->getPriceToPay();
        $dataSubArray[ComputopApiConfig::CURRENCY] = $quoteTransfer->getCurrency()->getCode();
        $dataSubArray[ComputopApiConfig::MAC] = $this->getMac($quoteTransfer, $dataSubArray[ComputopApiConfig::TRANS_ID]);
        $dataSubArray[ComputopApiConfig::PRODUCT_NAME] = 'IdentCheckConsumer';
        $dataSubArray[ComputopApiConfig::CUSTOMER_ID] = $quoteTransfer->getCustomer()->getCustomerReference();
        $dataSubArray[ComputopApiConfig::LEGAL_FORM] = 'PERSON';
        $dataSubArray[ComputopApiConfig::FIRST_NAME] = $quoteTransfer->getShippingAddress()->getFirstName();
        $dataSubArray[ComputopApiConfig::LAST_NAME] = $quoteTransfer->getShippingAddress()->getLastName();
        $dataSubArray[ComputopApiConfig::ADDRESS_STREET] = $quoteTransfer->getShippingAddress()->getAddress1();
        $dataSubArray[ComputopApiConfig::ADDRESS_STREET_NR] = $quoteTransfer->getShippingAddress()->getAddress2();
        $dataSubArray[ComputopApiConfig::ADDRESS_ADDITIONAL] = $quoteTransfer->getShippingAddress()->getAddress3();
        $dataSubArray[ComputopApiConfig::ADDRESS_CITY] = $quoteTransfer->getShippingAddress()->getCity();
        $dataSubArray[ComputopApiConfig::ADDR_ZIP] = $quoteTransfer->getShippingAddress()->getZipCode();
        $dataSubArray[ComputopApiConfig::ADDR_EMAIL] = 'phalfmann@email.com';//$quoteTransfer->getCustomer()->getEmail();
        $dataSubArray[ComputopApiConfig::PHONE] = $quoteTransfer->getShippingAddress()->getPhone();

        return $dataSubArray;
    }
}

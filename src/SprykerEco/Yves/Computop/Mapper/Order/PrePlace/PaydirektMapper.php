<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order\PrePlace;

use Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\Config\ComputopFieldName;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class PaydirektMapper extends AbstractPrePlaceMapper
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(TransferInterface $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopPaydirektPaymentTransfer();

        $computopPaymentTransfer->setTransId($this->getTransId($quoteTransfer));
        $computopPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::PAY_PAL_SUCCESS_PATH_NAME))
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        $computopPaymentTransfer->setShippingAmount($quoteTransfer->getTotals()->getExpenseTotal());
        $computopPaymentTransfer->setShoppingBasketAmount($quoteTransfer->getTotals()->getSubtotal());
        $computopPaymentTransfer->setShippingFirstName($quoteTransfer->getShippingAddress()->getFirstName());
        $computopPaymentTransfer->setShippingLastName($quoteTransfer->getShippingAddress()->getLastName());
        $computopPaymentTransfer->setShippingZip($quoteTransfer->getShippingAddress()->getZipCode());
        $computopPaymentTransfer->setShippingCity($quoteTransfer->getShippingAddress()->getCity());
        $computopPaymentTransfer->setShippingCountryCode($quoteTransfer->getShippingAddress()->getIso2Code());

        return $computopPaymentTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(TransferInterface $cardPaymentTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer $cardPaymentTransfer */
        $dataSubArray[ComputopFieldName::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopFieldName::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopFieldName::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopFieldName::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopFieldName::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopFieldName::CAPTURE] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopFieldName::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopFieldName::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopFieldName::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();

        //todo: check this part after set up account
//        $dataSubArray[ComputopFieldName::SHOP_API_KEY] = 'Test';

        $dataSubArray[ComputopFieldName::SHIPPING_FIRST_NAME] = $cardPaymentTransfer->getShippingFirstName();
        $dataSubArray[ComputopFieldName::SHIPPING_LAST_NAME] = $cardPaymentTransfer->getShippingLastName();
        $dataSubArray[ComputopFieldName::SHIPPING_ZIP] = $cardPaymentTransfer->getShippingZip();
        $dataSubArray[ComputopFieldName::SHIPPING_CITY] = $cardPaymentTransfer->getShippingCity();
        $dataSubArray[ComputopFieldName::SHIPPING_COUNTRY_CODE] = $cardPaymentTransfer->getShippingCountryCode();
        $dataSubArray[ComputopFieldName::SHIPPING_AMOUNT] = $cardPaymentTransfer->getShippingAmount();
        $dataSubArray[ComputopFieldName::SHOPPING_BASKET_AMOUNT] = $cardPaymentTransfer->getShoppingBasketAmount();

        return $dataSubArray;
    }

    /**
     * @return string
     */
    protected function getActionUrl()
    {
        return Config::get(ComputopConstants::PAYDIREKT_ORDER_ACTION);
    }
}

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
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;
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
        $dataSubArray[ComputopFieldNameConstants::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopFieldNameConstants::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopFieldNameConstants::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopFieldNameConstants::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopFieldNameConstants::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopFieldNameConstants::CAPTURE] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopFieldNameConstants::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopFieldNameConstants::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopFieldNameConstants::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();

        //todo: check this part after set up account
//        $dataSubArray[ComputopFieldNameConstants::SHOP_API_KEY] = 'Test';

        $dataSubArray[ComputopFieldNameConstants::SHIPPING_FIRST_NAME] = $cardPaymentTransfer->getShippingFirstName();
        $dataSubArray[ComputopFieldNameConstants::SHIPPING_LAST_NAME] = $cardPaymentTransfer->getShippingLastName();
        $dataSubArray[ComputopFieldNameConstants::SHIPPING_ZIP] = $cardPaymentTransfer->getShippingZip();
        $dataSubArray[ComputopFieldNameConstants::SHIPPING_CITY] = $cardPaymentTransfer->getShippingCity();
        $dataSubArray[ComputopFieldNameConstants::SHIPPING_COUNTRY_CODE] = $cardPaymentTransfer->getShippingCountryCode();
        $dataSubArray[ComputopFieldNameConstants::SHIPPING_AMOUNT] = $cardPaymentTransfer->getShippingAmount();
        $dataSubArray[ComputopFieldNameConstants::SHOPPING_BASKET_AMOUNT] = $cardPaymentTransfer->getShoppingBasketAmount();

        return $dataSubArray;
    }

    /**
     * @return string
     */
    protected function getActionUrl()
    {
        return Config::get(ComputopConstants::COMPUTOP_PAYDIREKT_ORDER_ACTION);
    }
}

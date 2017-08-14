<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\CreditCard;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class OrderCreditCardMapper extends AbstractCreditCardMapper
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(AbstractTransfer $quoteTransfer)
    {
        $computopCreditCardPaymentTransfer = new ComputopCreditCardPaymentTransfer();

        $computopCreditCardPaymentTransfer->setTransId($this->getTransId($quoteTransfer));
        $computopCreditCardPaymentTransfer->setMerchantId(Config::get(ComputopConstants::COMPUTOP_MERCHANT_ID_KEY));
        $computopCreditCardPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopCreditCardPaymentTransfer->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $computopCreditCardPaymentTransfer->setCapture(ComputopConstants::CAPTURE_MANUAL_TYPE);
        $computopCreditCardPaymentTransfer->setResponse(ComputopConstants::RESPONSE_TYPE);
        $computopCreditCardPaymentTransfer->setTxType(ComputopConstants::TX_TYPE);
        $computopCreditCardPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::SUCCESS_PATH_NAME))
        );
        $computopCreditCardPaymentTransfer->setUrlFailure(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::FAILURE_PATH_NAME))
        );

        $computopCreditCardPaymentTransfer->setClientIp($this->getClientIp());
        $computopCreditCardPaymentTransfer->setOrderDesc(ComputopConstants::ORDER_DESC_SUCCESS);

        return $computopCreditCardPaymentTransfer;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getAbsoluteUrl($path)
    {
        return Config::get(ApplicationConstants::BASE_URL_SSL_YVES) . $path;
    }

    /**
     * @return string
     */
    protected function getClientIp()
    {
        return $this->application['request']->getClientIp();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getTransId(AbstractTransfer $quoteTransfer)
    {
        $parameters = [
            time(),
            rand(100, 1000),
            $quoteTransfer->getCustomer()->getCustomerReference(),
        ];

        return md5(implode('-', $parameters));
    }

    /**
     * TODO:remove after test if need
     *
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     * @param string $data
     * @param int $len
     *
     * @return string
     */
    protected function getUrlToComputop(ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer, $data, $len)
    {
        return Config::get(ComputopConstants::COMPUTOP_CREDIT_CARD_ORDER_ACTION_KEY) . '?' . http_build_query([
                ComputopConstants::MERCHANT_ID_F_N => $computopCreditCardPaymentTransfer->getMerchantId(),
                ComputopConstants::DATA_F_N => $data,
                ComputopConstants::LEN_F_N => $len,
            ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $dataSubArray[ComputopConstants::TRANS_ID_F_N] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopConstants::AMOUNT_F_N] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopConstants::CURRENCY_F_N] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopConstants::URL_SUCCESS_F_N] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopConstants::URL_FAILURE_F_N] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopConstants::CAPTURE_F_N] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopConstants::RESPONSE_F_N] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopConstants::MAC_F_N] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopConstants::TX_TYPE_F_N] = $cardPaymentTransfer->getTxType();
        $dataSubArray[ComputopConstants::ORDER_DESC_F_N] = $cardPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopConstants::ETI_ID_F_N] = ComputopConstants::ETI_ID;

        return $dataSubArray;
    }

}

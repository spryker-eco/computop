<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form\DataProvider;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Silex\Application;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteBridge;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class CreditCardFormDataProvider implements StepEngineFormDataProviderInterface
{

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceInterface
     */
    protected $computopService;

    /**
     * @var \Silex\Application
     */
    protected $application;

    /**
     * @var \Spryker\Client\Quote\QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceInterface $computopService
     * @param \Silex\Application $application
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteBridge $quoteClient
     */
    public function __construct(ComputopToComputopServiceInterface $computopService, Application $application, ComputopToQuoteBridge $quoteClient)
    {
        $this->computopService = $computopService;
        $this->application = $application;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment() === null || $quoteTransfer->getPayment()->getComputopCreditCard() === null) {
            $paymentTransfer = new PaymentTransfer();
            $computop = $this->createComputopCreditCardPaymentTransfer($quoteTransfer);
            $paymentTransfer->setComputopCreditCard($computop);
            $quoteTransfer->setPayment($paymentTransfer);

            //TODO: check save Quote to session
            $this->quoteClient->setQuote($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        return [];
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createComputopCreditCardPaymentTransfer(AbstractTransfer $quoteTransfer)
    {
        $computopCreditCardPaymentTransfer = $this->createTransferWithUnencryptedValues($quoteTransfer);

        $computopCreditCardPaymentTransfer->setMac(
            $this->computopService->getComputopMacHashHmacValue($computopCreditCardPaymentTransfer)
        );

        $decryptedValues = $this->computopService->getEncryptedArray(
            $this->getDataSubArray($computopCreditCardPaymentTransfer),
            Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD_KEY)
        );

        $len = $decryptedValues['Len'];
        $data = $decryptedValues['Data'];

        $computopCreditCardPaymentTransfer->setData($data);
        $computopCreditCardPaymentTransfer->setLen($len);
        $computopCreditCardPaymentTransfer->setUrl($this->getUrlToComputop($computopCreditCardPaymentTransfer, $data, $len));

        return $computopCreditCardPaymentTransfer;
    }

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

        $computopCreditCardPaymentTransfer->setClientIp($this->application['request']->getClientIp());
        $computopCreditCardPaymentTransfer->setOrderDesc(ComputopConstants::ORDER_DESC_SUCCESS);

        return $computopCreditCardPaymentTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getTransId(AbstractTransfer $quoteTransfer)
    {
        //TODO: check this trans Id
        $parameters = [
            time(),
            rand(100, 1000),
            $quoteTransfer->getCustomer()->getCustomerReference(),
            ];

        return implode('-', $parameters);
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
                'MerchantID' => $computopCreditCardPaymentTransfer->getMerchantId(),
                'Data' => $data,
                'Len' => $len,
            ]);
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
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    public function getDataSubArray(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
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

        return $dataSubArray;
    }

}

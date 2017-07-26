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
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class CreditCardFormDataProvider implements StepEngineFormDataProviderInterface
{

    const RESPONSE = 'encrypt';

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceInterface
     */
    protected $computopService;

    /**
     * @var \Silex\Application
     */
    protected $application;

    /**
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceInterface $computopService
     * @param \Silex\Application $application
     */
    public function __construct(ComputopToComputopServiceInterface $computopService, Application $application)
    {
        $this->computopService = $computopService;
        $this->application = $application;
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
        $computopCreditCardPaymentTransfer = new ComputopCreditCardPaymentTransfer();

        $computopCreditCardPaymentTransfer->setTransId($this->getTransId($quoteTransfer));
        $computopCreditCardPaymentTransfer->setMerchantId(Config::get(ComputopConstants::COMPUTOP_MERCHANT_ID_KEY));
        $computopCreditCardPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopCreditCardPaymentTransfer->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $computopCreditCardPaymentTransfer->setCapture(ComputopConstants::CAPTURE_MANUAL_TYPE);
        $computopCreditCardPaymentTransfer->setMac(
            $this->computopService->computopMacHashHmacValue($computopCreditCardPaymentTransfer)
        );
        $computopCreditCardPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::SUCCESS_PATH_NAME))
        );
        $computopCreditCardPaymentTransfer->setUrlFailure(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::FAILURE_PATH_NAME))
        );

        $len = $this->getLen($computopCreditCardPaymentTransfer);
        $data = $this->getDataAttribute($computopCreditCardPaymentTransfer);

        $computopCreditCardPaymentTransfer->setData($data);
        $computopCreditCardPaymentTransfer->setLen($len);
        $computopCreditCardPaymentTransfer->setResponse(self::RESPONSE);

        $computopCreditCardPaymentTransfer->setUrl($this->getUrlToComputop($computopCreditCardPaymentTransfer, $data, $len));

        return $computopCreditCardPaymentTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getTransId(AbstractTransfer $quoteTransfer)
    {
        //TODO: check this trans Id
        return time() . '-' . $quoteTransfer->getCustomer()->getCustomerReference();
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
        return Config::get(ComputopConstants::COMPUTOP_CREDIT_CARD_ACTION) . '?' . http_build_query([
                'MerchantID' => $computopCreditCardPaymentTransfer->getMerchantId(),
                'Data' => $data,
                'Len' => $len,
            ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     *
     * @return bool|string
     */
    protected function getDataAttribute(ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer)
    {
        $plaintext = $this->getDataAttributeEncrypted($computopCreditCardPaymentTransfer);
        $len = $this->getLen($computopCreditCardPaymentTransfer);

        return $this->blowfishEncrypt($plaintext, $len, Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD));
    }

    /**
     * TODO: check and relocate if need
     *
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     *
     * @return string
     */
    protected function getDataAttributeEncrypted(ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer)
    {
        $pTransID = "TransID=" . $computopCreditCardPaymentTransfer->getTransId();
        $pAmount = "Amount=" . $computopCreditCardPaymentTransfer->getAmount();
        $pCurrency = "Currency=" . $computopCreditCardPaymentTransfer->getCurrency();
        $pURLSuccess = "URLSuccess=" . $computopCreditCardPaymentTransfer->getUrlSuccess();
        $pURLFailure = "URLFailure=" . $computopCreditCardPaymentTransfer->getUrlFailure();
        $pCapture = "Capture=" . $computopCreditCardPaymentTransfer->getCapture();
        $pResponse = "Response=" . self::RESPONSE;
        $pMAC = "MAC=" . $computopCreditCardPaymentTransfer->getMac();

        $query = [$pTransID, $pAmount, $pCurrency, $pURLSuccess, $pURLFailure, $pCapture, $pResponse, $pMAC];

        return implode("&", $query);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     *
     * @return int
     */
    protected function getLen(ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer)
    {
        return strlen($this->getDataAttributeEncrypted($computopCreditCardPaymentTransfer));
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
     * Encrypt the passed text (any encoding) with Blowfish.
     *
     * @param string $plaintext
     * @param integer $len
     * @param string $password
     *
     * @return bool|string
     */
    protected function blowfishEncrypt($plaintext, $len, $password)
    {
        return $this->computopService->blowfishEncryptedValue($plaintext, $len, $password);
    }

}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order;

use Silex\Application;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceInterface;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

abstract class AbstractMapper implements MapperInterface
{

    const TRANS_ID_SEPARATOR = '-';

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $cardPaymentTransfer
     *
     * @return array
     */
    abstract protected function getDataSubArray(TransferInterface $cardPaymentTransfer);

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer|\Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    abstract protected function createTransferWithUnencryptedValues(TransferInterface $quoteTransfer);

    /**
     * @return string
     */
    abstract protected function getActionUrl();

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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createComputopPaymentTransfer(TransferInterface $quoteTransfer)
    {
        $computopPaymentTransfer = $this->createTransferWithUnencryptedValues($quoteTransfer);

        $computopPaymentTransfer->setMerchantId(Config::get(ComputopConstants::COMPUTOP_MERCHANT_ID));
        $computopPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopPaymentTransfer->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $computopPaymentTransfer->setCapture(ComputopConstants::CAPTURE_MANUAL_TYPE);
        $computopPaymentTransfer->setResponse(ComputopConstants::RESPONSE_TYPE);
        $computopPaymentTransfer->setClientIp($this->getClientIp());
        $computopPaymentTransfer->setUrlFailure(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::FAILURE_PATH_NAME))
        );
        $computopPaymentTransfer->setMac(
            $this->computopService->getMacEncryptedValue($computopPaymentTransfer)
        );

        $decryptedValues = $this->computopService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD)
        );

        $length = $decryptedValues[ComputopFieldNameConstants::LENGTH];
        $data = $decryptedValues[ComputopFieldNameConstants::DATA];

        $computopPaymentTransfer->setData($data);
        $computopPaymentTransfer->setLen($length);
        $computopPaymentTransfer->setUrl($this->getUrlToComputop($computopPaymentTransfer->getMerchantId(), $data, $length));

        return $computopPaymentTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getTransId(TransferInterface $quoteTransfer)
    {
        $parameters = [
            time(),
            rand(100, 1000),
            $quoteTransfer->getCustomer()->getCustomerReference(),
        ];

        return md5(implode(self::TRANS_ID_SEPARATOR, $parameters));
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
     * @param string $merchantId
     * @param string $data
     * @param int $length
     *
     * @return string
     */
    protected function getUrlToComputop($merchantId, $data, $length)
    {
        return $this->getActionUrl() . '?' . http_build_query([
                ComputopFieldNameConstants::MERCHANT_ID => $merchantId,
                ComputopFieldNameConstants::DATA => $data,
                ComputopFieldNameConstants::LENGTH => $length,
            ]);
    }

}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init;

use Silex\Application;
use Spryker\Service\UtilText\Model\Hash;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Service\Computop\ComputopServiceInterface;
use SprykerEco\Yves\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\ComputopConfigInterface;
use SprykerEco\Yves\Computop\Dependency\ComputopToStoreInterface;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

abstract class AbstractMapper implements MapperInterface
{
    const TRANS_ID_SEPARATOR = '-';

    /**
     * @var \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    protected $computopService;

    /**
     * @var \Silex\Application
     */
    protected $application;
    
    /**
     * @var \SprykerEco\Yves\Computop\Dependency\ComputopToStoreInterface
     */
    protected $store;

    /**
     * @var \SprykerEco\Yves\Computop\ComputopConfigInterface
     */
    protected $config;

    /**
     * @var \Spryker\Service\UtilText\UtilTextService
     */
    protected $textService;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer|\Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    abstract protected function createTransferWithUnencryptedValues(TransferInterface $quoteTransfer);

    /**
     * @param \SprykerEco\Service\Computop\ComputopServiceInterface $computopService
     * @param \Silex\Application $application
     * @param \SprykerEco\Yves\Computop\Dependency\ComputopToStoreInterface $store
     * @param \SprykerEco\Yves\Computop\ComputopConfigInterface $config
     * @param \Spryker\Service\UtilText\UtilTextService $textService
     */
    public function __construct(
        ComputopServiceInterface $computopService,
        Application $application,
        ComputopToStoreInterface $store,
        ComputopConfigInterface $config,
        UtilTextService $textService
    ) {
        $this->computopService = $computopService;
        $this->application = $application;
        $this->store = $store;
        $this->config = $config;
        $this->textService = $textService;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer|\Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    public function createComputopPaymentTransfer(TransferInterface $quoteTransfer)
    {
        $computopPaymentTransfer = $this->createTransferWithUnencryptedValues($quoteTransfer);
        $computopPaymentTransfer->setMerchantId($this->config->getMerchantId());
        $computopPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopPaymentTransfer->setCurrency($this->store->getCurrencyIsoCode());
        $computopPaymentTransfer->setResponse(ComputopConfig::RESPONSE_ENCRYPT_TYPE);
        $computopPaymentTransfer->setClientIp($this->getClientIp());
        $computopPaymentTransfer->setReqId($this->generateReqId($quoteTransfer));
        $computopPaymentTransfer->setUrlFailure(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::FAILURE_PATH_NAME))
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateTransId(TransferInterface $quoteTransfer)
    {
        $parameters = [
            time(),
            rand(100, 1000),
            $quoteTransfer->getCustomer()->getCustomerReference(),
        ];

        return $this->textService->hashValue(implode(self::TRANS_ID_SEPARATOR, $parameters), Hash::MD5);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateReqId(TransferInterface $quoteTransfer)
    {
        $parameters = [
            $quoteTransfer->getTotals()->getHash(),
            $quoteTransfer->getCustomer()->getCustomerReference(),
        ];

        return $this->textService->hashValue(implode(self::TRANS_ID_SEPARATOR, $parameters), Hash::SHA256);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getAbsoluteUrl($path)
    {
        return $this->config->getBaseUrlSsl() . $path;
    }

    /**
     * @return string
     */
    protected function getClientIp()
    {
        return $this->application['request']->getClientIp();
    }
}

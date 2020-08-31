<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init;

use Generated\Shared\Transfer\ComputopApiRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Yves\Router\Router\RouterInterface;
use SprykerEco\Service\ComputopApi\ComputopApiServiceInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\ComputopConfigInterface;
use SprykerEco\Yves\Computop\Dependency\ComputopToStoreInterface;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

abstract class AbstractMapper implements MapperInterface
{
    /**
     * @var \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface
     */
    protected $computopApiService;

    /**
     * @var \Spryker\Yves\Router\Router\RouterInterface
     */
    protected $router;

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\ComputopToStoreInterface
     */
    protected $store;

    /**
     * @var \SprykerEco\Yves\Computop\ComputopConfigInterface
     */
    protected $config;

    /**
     * @var array
     */
    protected $decryptedValues;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    abstract protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer);

    /**
     * @param \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface $computopApiService
     * @param \Spryker\Yves\Router\Router\RouterInterface $router
     * @param \SprykerEco\Yves\Computop\Dependency\ComputopToStoreInterface $store
     * @param \SprykerEco\Yves\Computop\ComputopConfigInterface $config
     */
    public function __construct(
        ComputopApiServiceInterface $computopApiService,
        RouterInterface $router,
        ComputopToStoreInterface $store,
        ComputopConfigInterface $config
    ) {
        $this->computopApiService = $computopApiService;
        $this->router = $router;
        $this->store = $store;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        $computopPaymentTransfer = $this->createTransferWithUnencryptedValues($quoteTransfer);
        $computopPaymentTransfer->setMerchantId($this->config->getMerchantId());
        $computopPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopPaymentTransfer->setCurrency($this->store->getCurrencyIsoCode());
        $computopPaymentTransfer->setResponse(ComputopConfig::RESPONSE_ENCRYPT_TYPE);
        $computopPaymentTransfer->setClientIp($this->getClientIp());
        $computopPaymentTransfer->setReqId($this->computopApiService->generateReqIdFromQuoteTransfer($quoteTransfer));
        $computopPaymentTransfer->setUrlFailure(
            $this->router->generate(ComputopRouteProviderPlugin::FAILURE_PATH_NAME)
        );
        $computopPaymentTransfer->setShippingZip($this->getZipCode($quoteTransfer));

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateTransId(QuoteTransfer $quoteTransfer)
    {
        return $this->computopApiService->generateTransId($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $limit
     *
     * @return string
     */
    protected function getLimitedTransId(QuoteTransfer $quoteTransfer, $limit)
    {
        return substr($this->generateTransId($quoteTransfer), 0, $limit);
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
        return '127.0.0.1';//$this->application['request']->getClientIp();
    }

    /**
     * @param string $url
     * @param array $queryData
     *
     * @return string
     */
    protected function getActionUrl($url, $queryData)
    {
        return $url . '?' . http_build_query($queryData);
    }

    /**
     * @param string $merchantId
     * @param string $data
     * @param int $length
     *
     * @return array
     */
    protected function getQueryParameters($merchantId, $data, $length)
    {
        return [
            ComputopApiConfig::MERCHANT_ID => $merchantId,
            ComputopApiConfig::DATA => $data,
            ComputopApiConfig::LENGTH => $length,
            ComputopApiConfig::URL_BACK => $this->router->generate($this->config->getCallbackFailureRedirectPath()),
        ];
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiRequestTransfer
     */
    protected function createRequestTransfer(TransferInterface $computopPaymentTransfer)
    {
        return (new ComputopApiRequestTransfer())
            ->fromArray($computopPaymentTransfer->toArray(), true);
    }

    /**
     * @param string $method
     *
     * @return string
     */
    protected function getCaptureType(string $method): string
    {
        $paymentMethodsCaptureTypes = $this->config->getPaymentMethodsCaptureTypes();

        return $paymentMethodsCaptureTypes[$method] ?? '';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getZipCode(QuoteTransfer $quoteTransfer): string
    {
        if ($quoteTransfer->getBillingSameAsShipping()) {
            return $quoteTransfer->getBillingAddress()->getZipCode();
        }

        return $quoteTransfer->getShippingAddress()->getZipCode();
    }
}

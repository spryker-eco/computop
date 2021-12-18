<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ComputopAddressLineTransfer;
use Generated\Shared\Transfer\ComputopAddressTransfer;
use Generated\Shared\Transfer\ComputopApiRequestTransfer;
use Generated\Shared\Transfer\ComputopCountryTransfer;
use Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer;
use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Yves\Router\Router\Router;
use Spryker\Yves\Router\Router\RouterInterface;
use SprykerEco\Service\ComputopApi\ComputopApiServiceInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\ComputopConfigInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToCountryClientInterface;
use SprykerEco\Yves\Computop\Dependency\ComputopToStoreInterface;
use SprykerEco\Yves\Computop\Dependency\Service\ComputopToUtilEncodingServiceInterface;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;
use Symfony\Component\HttpFoundation\Request;

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
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Service\ComputopToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Client\ComputopToCountryClientInterface
     */
    protected $countryClient;

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \SprykerEco\Yves\Computop\Dependency\Service\ComputopToUtilEncodingServiceInterface $utilEncodingService
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToCountryClientInterface $countryClient
     */
    public function __construct(
        ComputopApiServiceInterface $computopApiService,
        RouterInterface $router,
        ComputopToStoreInterface $store,
        ComputopConfigInterface $config,
        Request $request,
        ComputopToUtilEncodingServiceInterface $utilEncodingService,
        ComputopToCountryClientInterface $countryClient
    ) {
        $this->computopApiService = $computopApiService;
        $this->router = $router;
        $this->store = $store;
        $this->config = $config;
        $this->request = $request;
        $this->utilEncodingService = $utilEncodingService;
        $this->countryClient = $countryClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = $this->createTransferWithUnencryptedValues($quoteTransfer);
        $computopPaymentTransfer->setMerchantId($this->config->getMerchantId());
        $computopPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopPaymentTransfer->setCurrency($this->store->getCurrencyIsoCode());
        if ($quoteTransfer->getCurrency() && $quoteTransfer->getCurrency()->getCode() !== $computopPaymentTransfer->getCurrency()) {
            $computopPaymentTransfer->setCurrency($quoteTransfer->getCurrency()->getCode());
        }
        $computopPaymentTransfer->setResponse(ComputopConfig::RESPONSE_ENCRYPT_TYPE);
        $computopPaymentTransfer->setClientIp($this->getClientIp());
        $computopPaymentTransfer->setReqId($this->computopApiService->generateReqIdFromQuoteTransfer($quoteTransfer));
        $computopPaymentTransfer->setUrlFailure(
            $this->router->generate(ComputopRouteProviderPlugin::FAILURE_PATH_NAME, [], Router::ABSOLUTE_URL),
        );
        $computopPaymentTransfer->setUrlNotify(
            $this->router->generate(ComputopRouteProviderPlugin::NOTIFY_PATH_NAME, [], Router::ABSOLUTE_URL),
        );

        if (!$computopPaymentTransfer instanceof ComputopPayPalExpressPaymentTransfer) {
            $computopPaymentTransfer->setShippingZip($this->getZipCode($quoteTransfer));
        }

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateTransId(QuoteTransfer $quoteTransfer): string
    {
        return $this->computopApiService->generateTransId($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $limit
     *
     * @return string
     */
    protected function getLimitedTransId(QuoteTransfer $quoteTransfer, int $limit): string
    {
        return substr($this->generateTransId($quoteTransfer), 0, $limit);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getAbsoluteUrl(string $path): string
    {
        return $this->config->getBaseUrlSsl() . $path;
    }

    /**
     * @return string
     */
    protected function getClientIp(): string
    {
        return $this->request->getClientIp();
    }

    /**
     * @param string $url
     * @param array $queryData
     *
     * @return string
     */
    protected function getActionUrl(string $url, array $queryData): string
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
    protected function getQueryParameters(string $merchantId, string $data, int $length): array
    {
        return [
            ComputopApiConfig::MERCHANT_ID => $merchantId,
            ComputopApiConfig::DATA => $data,
            ComputopApiConfig::LENGTH => $length,
            ComputopApiConfig::URL_BACK => $this->router->generate(
                $this->config->getCallbackFailureRedirectPath(),
                [],
                Router::ABSOLUTE_URL,
            ),
        ];
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiRequestTransfer
     */
    protected function createRequestTransfer(TransferInterface $computopPaymentTransfer): ComputopApiRequestTransfer
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

        $addressTransfer = $this->getShippingAddressFromQuote($quoteTransfer);

        return $addressTransfer->getZipCode();
    }

    /**
     * @param array $requestParameterData
     *
     * @return string
     */
    protected function encodeRequestParameterData(array $requestParameterData): string
    {
        $requestParameterData = $this->removeRedundantParams($requestParameterData);

        return base64_encode((string)$this->utilEncodingService->encodeJson($requestParameterData));
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function removeRedundantParams(array $data): array
    {
        $data = array_filter($data);

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->removeRedundantParams($value);
            }
        }

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getShippingAddressFromQuote(QuoteTransfer $quoteTransfer): AddressTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() && $itemTransfer->getShipment()->getShippingAddress()) {
                return $itemTransfer->getShipment()->getShippingAddress();
            }
        }

        return $quoteTransfer->getShippingAddressOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCountryTransfer
     */
    protected function getComputopCountryTransferFromQuote(AddressTransfer $addressTransfer): ComputopCountryTransfer
    {
        $countryTransfer = (new CountryTransfer())
            ->setIso2Code($addressTransfer->getIso2Code());

        $countryCollectionTransfer = (new CountryCollectionTransfer())
            ->addCountries($countryTransfer);

        $countryCollectionTransfer = $this->countryClient->findCountriesByIso2Codes($countryCollectionTransfer);

        return (new ComputopCountryTransfer())
            ->setCountryA3($countryCollectionTransfer->getCountries()->offsetGet(0)->getIso3Code());
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopAddressTransfer
     */
    protected function getComputopAddressTransferByAddressTransfer(AddressTransfer $addressTransfer): ComputopAddressTransfer
    {
        $computopAddressLineTransfer = (new ComputopAddressLineTransfer())
            ->setStreet($addressTransfer->getAddress1())
            ->setStreetNumber($addressTransfer->getAddress2());

        return (new ComputopAddressTransfer())
            ->setCity($addressTransfer->getCity())
            ->setCountry($this->getComputopCountryTransferFromQuote($addressTransfer))
            ->setAddressLine1($computopAddressLineTransfer)
            ->setPostalCode($addressTransfer->getZipCode());
    }
}

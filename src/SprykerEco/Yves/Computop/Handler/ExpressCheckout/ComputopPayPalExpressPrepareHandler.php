<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\ExpressCheckout;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Service\ComputopApi\ComputopApiServiceInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\ComputopConfigInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface;
use SprykerEco\Yves\Computop\Dependency\External\ComputopToGuzzleHttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

class ComputopPayPalExpressPrepareHandler implements ComputopPayPalExpressPrepareHandlerInterface
{
    /**
     * @var \Spryker\Client\Quote\QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    protected $stepEngineFormDataProvider;

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\External\ComputopToGuzzleHttpClientInterface
     */
    protected $computopToGuzzleHttpClient;

    /**
     * @var \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface
     */
    protected $computopApiService;

    /**
     * @var \SprykerEco\Yves\Computop\ComputopConfigInterface
     */
    protected $computopConfig;

    public function __construct(
        ComputopToQuoteClientInterface $quoteClient,
        StepEngineFormDataProviderInterface $stepEngineFormDataProvider,
        ComputopToGuzzleHttpClientInterface $computopToGuzzleHttpClient,
        ComputopApiServiceInterface $computopApiService,
        ComputopConfigInterface $computopConfig
    ) {
        $this->quoteClient = $quoteClient;
        $this->stepEngineFormDataProvider = $stepEngineFormDataProvider;
        $this->computopToGuzzleHttpClient = $computopToGuzzleHttpClient;
        $this->computopApiService = $computopApiService;
        $this->computopConfig = $computopConfig;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @return array
     * @throws \SprykerEco\Service\ComputopApi\Exception\ComputopApiConverterException
     * @throws \Spryker\Yves\Computop\Http\Exception\ComputopHttpRequestException
     */
    public function handle(QuoteTransfer $quoteTransfer): array
    {
//        Customer kostyl
        $customer = new CustomerTransfer();
        $customer->setCustomerReference('123');
        $customer->setIsGuest(true);
        $quoteTransfer->setCustomer($customer);

        //zip kostyl
        $address = new AddressTransfer();
        $address->setZipCode(65000);
        $quoteTransfer->setShippingAddress($address);

        $quote = $this->stepEngineFormDataProvider->getData($quoteTransfer);

        $payment = $quote->getPayment()->getComputopPayPalExpress();

        $response = $this->computopToGuzzleHttpClient->request(
            Request::METHOD_POST,
            $this->computopConfig->getPaypalExpressInitActionUrl(),
            [
                'query' => [
                    ComputopApiConfig::MERCHANT_ID => $payment->getMerchantId(),
                    ComputopApiConfig::DATA => $payment->getData(),
                    ComputopApiConfig::LENGTH => $payment->getLen(),
                ],
            ]
        );

        $responseContents = $response->getBody()->getContents();

        $respData = [];
        parse_str($responseContents, $respData);

        $this->quoteClient->setQuote($quote);

        // move to trasnfer object
        return $this->computopApiService->decryptResponseHeader($respData, $this->computopConfig->getBlowfishPassword());
    }
}

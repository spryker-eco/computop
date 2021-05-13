<?php

namespace SprykerEco\Yves\Computop\Handler\ExpressCheckout;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Quote\QuoteClientInterface;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Service\ComputopApi\ComputopApiServiceInterface;
use SprykerEco\Yves\Computop\ComputopConfigInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface;
use SprykerEco\Yves\Computop\Dependency\External\ComputopToGuzzleHttpClientInterface;

class ComputopPayPalExpressPrepareHandler implements ComputopPayPalExpressPrepareHandlerInterface
{
    /**
     * @var QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var StepEngineFormDataProviderInterface
     */
    protected $stepEngineFormDataProvider;

    /**
     * @var ComputopToGuzzleHttpClientInterface
     */
    protected $computopToGuzzleHttpClient;

    /**
     * @var ComputopApiServiceInterface
     */
    protected $computopApiService;

    /**
     * @var ComputopConfigInterface
     */
    protected $computopConfig;

    /**
     * ComputopPayPalExpressPrepareHandler constructor.
     */
    public function __construct(
        ComputopToQuoteClientInterface $quoteClient,
        StepEngineFormDataProviderInterface $stepEngineFormDataProvider,
        ComputopToGuzzleHttpClientInterface $computopToGuzzleHttpClient,
        ComputopApiServiceInterface $computopApiService,
        ComputopConfigInterface $computopConfig
    )
    {
        $this->quoteClient = $quoteClient;
        $this->stepEngineFormDataProvider = $stepEngineFormDataProvider;
        $this->computopToGuzzleHttpClient = $computopToGuzzleHttpClient;
        $this->computopApiService = $computopApiService;
        $this->computopConfig = $computopConfig;
    }

    public function handle()
    {
        $quoteTransfer = $this->quoteClient->getQuote();

//        $handler = $this->getFactory()->createPayPalExpressInitHandler()

        //Customer kostyl
        $customer = new CustomerTransfer();
        $customer->setCustomerReference('123');
        $customer->setIsGuest(true);
        $quoteTransfer->setCustomer($customer);

        //zip kostyl
        $address = new AddressTransfer();
        $address->setZipCode(65000);
        $quoteTransfer->setShippingAddress($address);


        $quote = $this->stepEngineFormDataProvider->getData($quoteTransfer);
        $this->quoteClient->setQuote($quote);

        $payment = $quote->getPayment()->getComputopPayPalExpress();

        $response = $this->computopToGuzzleHttpClient->request(
            'post',
            'https://www.computop-paygate.com/ExternalServices/paypalorders.aspx',
            [
                'query' => [
                    'MerchantID' => $payment->getMerchantId(),
                    'Data' => $payment->getData(),
                    'Len' => $payment->getLen()
                ]
            ]
        );

        $responseContents = $response->getBody()->getContents();

        $respData = [];
        parse_str($responseContents, $respData);

        return $this->computopApiService->decryptResponseHeader($respData, $this->computopConfig->getBlowfishPassword());
    }
}

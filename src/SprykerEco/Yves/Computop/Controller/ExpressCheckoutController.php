<?php

namespace SprykerEco\Yves\Computop\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\ComputopFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Yves\Computop\ComputopFactory getFactory()
 * @method \SprykerEco\Client\Computop\ComputopClientInterface getClient()
 */
class ExpressCheckoutController extends AbstractController
{
    /** @see CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PLACE_ORDER */
    protected const ROUTE_NAME_CHECKOUT_PLACE_ORDER = 'checkout-place-order';

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function preparePayPalExpressAction(): JsonResponse
    {
        $handler = $this->getFactory()->createPayPalExpressPrepareHandler();
        $decryptedArray = $handler->handle();

        return new JsonResponse(['id' => $decryptedArray['token']]);
    }


    /**
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function placeOrderPayPalExpressAction(Request $request)
    {
        $request1 = $this->mockRequest();

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $handler = (new ComputopFactory())->createPayPalExpressInitHandler();

        $quoteTransfer = $handler->handle($quoteTransfer, $request1->query->all());

        return $this->redirectResponseInternal(static::ROUTE_NAME_CHECKOUT_PLACE_ORDER);
    }











    private function mockRequest(): Request
    {
        $request = new Request();

        $data = [
            ComputopApiConfig::MERCHANT_ID_SHORT => 'spryker_test',
            ComputopApiConfig::PAY_ID => 123,
            ComputopApiConfig::MAC => '299BEB7656F5B1ED2FDE6D50C8F2CA38196F8658F853CE6471730726CB8FD0F9',
            ComputopApiConfig::TRANS_ID => 12355,
            ComputopApiConfig::CODE => 0,

            ComputopApiConfig::STATUS => 'AUTHORIZE_REQUEST',
            ComputopApiConfig::FIRST_NAME => 'Kos',
            ComputopApiConfig::LAST_NAME => 'Spryker',
            ComputopApiConfig::EMAIL => 'kos@spryker.local',


//            ComputopApiConfig::ADDRESS_STREET => 'test street',
            ComputopApiConfig::ADDRESS_COUNTRY_CODE => 'UA',
            ComputopApiConfig::ADDRESS_CITY => 'Odessa1',
            ComputopApiConfig::ADDR_ZIP => 65001,


            ComputopApiConfig::BILLING_NAME => 'KosBilling',
            ComputopApiConfig::BILLING_ADDRESS_STREET => 'test billing street',
            ComputopApiConfig::BILLING_ADDRESS_COUNTRY_CODE => 'US',
            ComputopApiConfig::BILLING_ADDRESS_CITY => 'Odessa2',
            ComputopApiConfig::BILLING_ADDRESS_ZIP => 65002,
        ];

        $encrData = (new ComputopFactory())->getComputopApiService()
            ->getEncryptedArray($data, (new ComputopFactory())->getConfig()->getBlowfishPassword());

        $request->query->set('Data', $encrData['Data']);
        $request->query->set('Len', $encrData['Len']);

        return $request;
    }
}

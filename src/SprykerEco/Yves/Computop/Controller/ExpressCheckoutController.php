<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\ComputopFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Yves\Computop\ComputopFactory getFactory()
 * @method \SprykerEco\Client\Computop\ComputopClientInterface getClient()
 */
class ExpressCheckoutController extends AbstractController
{
    /**
     * @see CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PLACE_ORDER
     */
    protected const ROUTE_NAME_CHECKOUT_PLACE_ORDER = 'checkout-place-order';

    /**
     * @see CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUCCESS
     */
    protected const ROUTE_NAME_CHECKOUT_SUCCESS = 'checkout-success';

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function preparePayPalExpressAction(): JsonResponse
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $payPalExpressPrepareHandler = $this->getFactory()->createPayPalExpressPrepareHandler();
        $decryptedArray = $payPalExpressPrepareHandler->handle($quoteTransfer);

        return new JsonResponse(['id' => $decryptedArray['token']]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function placeOrderPayPalExpressAction(Request $request)
    {
        $request1 = $this->mockRequest();

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $payPalExpressInitHandler = $this->getFactory()->createPayPalExpressInitHandler();

        $payPalExpressInitHandler->handle($quoteTransfer, $request1->query->all());

        return $this->redirectResponseInternal(static::ROUTE_NAME_CHECKOUT_PLACE_ORDER);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function completeOrderPayPalExpressAction(Request $request): RedirectResponse
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $payPalExpressCompleteHandler = $this->getFactory()->createPayPalExpressCompleteHandler();

        $payPalExpressCompleteHandler->handle($quoteTransfer);

        return $this->redirectResponseInternal(static::ROUTE_NAME_CHECKOUT_SUCCESS);
    }



    private function mockRequest(): Request
    {
        $request = new Request();

        $data = [
            ComputopApiConfig::MERCHANT_ID_SHORT => 'spryker_test',
            ComputopApiConfig::PAY_ID => 123,
            ComputopApiConfig::MAC => '00FAE23A97B13EB2A5C701B0AD4EB69641802B9C8BE1B0714E4F13F232A19393',
            ComputopApiConfig::TRANS_ID => '4787e38abec06282a826ec76cbdfb95e',
            ComputopApiConfig::CODE => 0,

            ComputopApiConfig::STATUS => 'AUTHORIZE_REQUEST',
            ComputopApiConfig::FIRST_NAME => 'Kos',
            ComputopApiConfig::LAST_NAME => 'Spryker',
            ComputopApiConfig::EMAIL => 'kos@spryker.local',

            ComputopApiConfig::ADDRESS_STREET => 'test street',
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

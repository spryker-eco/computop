<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
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
        $computopApiPayPalExpressPrepareResponseTransfer = $payPalExpressPrepareHandler->handle($quoteTransfer);

        return new JsonResponse(['id' => $computopApiPayPalExpressPrepareResponseTransfer->getToken()]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function placeOrderPayPalExpressAction(Request $request): RedirectResponse
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $payPalExpressInitHandler = $this->getFactory()->createPayPalExpressInitHandler();
        $payPalExpressInitHandler->handle($quoteTransfer, $request->query->all());

        return $this->redirectResponseInternal(static::ROUTE_NAME_CHECKOUT_PLACE_ORDER);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function completeOrderPayPalExpressAction(Request $request): RedirectResponse
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $payPalExpressCompleteHandler = $this->getFactory()->createPayPalExpressCompleteHandler();

        $payPalExpressCompleteHandler->handle($quoteTransfer);

        return $this->redirectResponseInternal(static::ROUTE_NAME_CHECKOUT_SUCCESS);
    }
}
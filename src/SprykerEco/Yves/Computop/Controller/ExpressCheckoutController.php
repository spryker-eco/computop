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
     * @uses CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PLACE_ORDER
     *
     * @var string
     */
    protected const ROUTE_NAME_CHECKOUT_PLACE_ORDER = 'checkout-place-order';

    /**
     * @uses CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUCCESS
     *
     * @var string
     */
    protected const ROUTE_NAME_CHECKOUT_SUCCESS = 'checkout-success';

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function preparePayPalExpressAction(): JsonResponse
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $payPalExpressPrepareHandler = $this->getFactory()->createComputopPayPalExpressPrepareAggregator();
        $computopApiPayPalExpressPrepareResponseTransfer = $payPalExpressPrepareHandler->aggregate($quoteTransfer);

        return new JsonResponse([
            'orderId' => $computopApiPayPalExpressPrepareResponseTransfer->getOrderId(),
            'merchantId' => $computopApiPayPalExpressPrepareResponseTransfer->getMid(),
            'payId' => $computopApiPayPalExpressPrepareResponseTransfer->getPayID(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function placeOrderPayPalExpressAction(Request $request): RedirectResponse
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $payPalExpressInitHandler = $this->getFactory()->createComputopPayPalExpressInitAggregator();
        $payPalExpressInitHandler->aggregate($quoteTransfer, $request->query->all());

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
        $payPalExpressCompleteHandler = $this->getFactory()->createComputopPayPalExpressCompleteAggregator();

        $payPalExpressCompleteHandler->aggregate($quoteTransfer);

        return $this->redirectResponseInternal(static::ROUTE_NAME_CHECKOUT_SUCCESS);
    }
}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Yves\Computop\Handler\Exception\PaymentMethodNotFoundException;
use SprykerEco\Zed\Computop\ComputopConfig;

class ComputopPostSaveHook
{

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     */
    public function __construct(ComputopConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($this->config->isNeededRedirectAfterPlaceOrder($quoteTransfer->getPayment()->getPaymentSelection())) {
            $this->setRedirect($quoteTransfer, $checkoutResponseTransfer);
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @throws \SprykerEco\Yves\Computop\Handler\Exception\PaymentMethodNotFoundException
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function setRedirect(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();

        $paymentMethod = ucfirst($paymentSelection);
        $method = 'get' . $paymentMethod;
        $paymentTransfer = $quoteTransfer->getPayment();

        if (!method_exists($paymentTransfer, $method) || ($quoteTransfer->getPayment()->$method() === null)) {
            throw new PaymentMethodNotFoundException(sprintf('Selected payment method "%s" not found in PaymentTransfer', $paymentMethod));
        }
        $computopPaymentTransfer = $quoteTransfer->getPayment()->$method();

        $checkoutResponseTransfer
            ->setIsExternalRedirect(true)
            ->setRedirectUrl($computopPaymentTransfer->getUrl());
    }

}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Handler\Exception\PaymentMethodNotFoundException;

class ComputopPaymentHandler
{

    /**
     * @var array
     */
    protected static $paymentMethods = [
        ComputopConfig::PAYMENT_METHOD_SOFORT => ComputopConfig::PAYMENT_METHOD_SOFORT,
    ];

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(QuoteTransfer $quoteTransfer)
    {
        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();

        $this->setPaymentProviderAndMethod($quoteTransfer, $paymentSelection);
        $this->setComputopPayment($quoteTransfer, $paymentSelection);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setPaymentProviderAndMethod(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $quoteTransfer
            ->getPayment()
            ->setPaymentProvider(ComputopConfig::PROVIDER_NAME)
            ->setPaymentMethod(self::$paymentMethods[$paymentSelection])
            ->setPaymentSelection(self::$paymentMethods[$paymentSelection]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setComputopPayment(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $computopPaymentTransfer = $this->getComputopPaymentTransfer($quoteTransfer, $paymentSelection);

        $this->setComputopPaymentToQuote($quoteTransfer, $paymentSelection, clone $computopPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @throws \SprykerEco\Yves\Computop\Handler\Exception\PaymentMethodNotFoundException
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function getComputopPaymentTransfer(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $paymentMethod = ucfirst($paymentSelection);
        $method = 'get' . $paymentMethod;
        $paymentTransfer = $quoteTransfer->getPayment();

        if (!method_exists($paymentTransfer, $method) || ($quoteTransfer->getPayment()->$method() === null)) {
            throw new PaymentMethodNotFoundException(sprintf('Selected payment method "%s" not found in PaymentTransfer', $paymentMethod));
        }
        $computopPaymentTransfer = $quoteTransfer->getPayment()->$method();
        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $computopPaymentTransfer
     *
     * @throws \SprykerEco\Yves\Computop\Handler\Exception\PaymentMethodNotFoundException
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setComputopPaymentToQuote($quoteTransfer, $paymentSelection, $computopPaymentTransfer)
    {
        $paymentMethod = ucfirst($paymentSelection);
        $method = 'set' . $paymentMethod;
        $paymentTransfer = $quoteTransfer->getPayment();

        if (!method_exists($paymentTransfer, $method) || ($quoteTransfer->getPayment()->$method() === null)) {
            throw new PaymentMethodNotFoundException(sprintf('Selected payment method "%s" not found in PaymentTransfer', $paymentMethod));
        }

        $quoteTransfer->getPayment()->$method($computopPaymentTransfer);

        return $quoteTransfer;
    }

}

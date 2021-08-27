<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\ComputopConfigInterface;
use SprykerEco\Yves\Computop\Exception\PaymentMethodNotFoundException;

class ComputopPaymentHandler implements ComputopPaymentHandlerInterface
{
    /**
     * @var array
     */
    protected $paymentMethods = [];

    /**
     * @var \SprykerEco\Yves\Computop\ComputopConfigInterface
     */
    protected $config;

    /**
     * @param \SprykerEco\Yves\Computop\ComputopConfigInterface $config
     */
    public function __construct(ComputopConfigInterface $config)
    {
        $this->config = $config;

        $paymentMethods = $this->config->getPaymentMethodsWithoutOrderCall();
        $this->paymentMethods = array_combine($paymentMethods, $paymentMethods);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(QuoteTransfer $quoteTransfer)
    {
        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();
        if ($paymentSelection) {
            $quoteTransfer = $this->setPaymentProviderMethodSelection($quoteTransfer, $paymentSelection);
            $quoteTransfer = $this->setComputopPayment($quoteTransfer, $paymentSelection);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setPaymentProviderMethodSelection(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $quoteTransfer
            ->getPayment()
            ->setPaymentProvider(ComputopConfig::PROVIDER_NAME)
            ->setPaymentMethod($this->paymentMethods[$paymentSelection])
            ->setPaymentSelection($this->paymentMethods[$paymentSelection]);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setComputopPayment(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $computopPaymentTransfer = $this->getComputopPaymentTransfer($quoteTransfer, $paymentSelection);

        return $this->setComputopPaymentToQuote($quoteTransfer, $paymentSelection, clone $computopPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @throws \SprykerEco\Yves\Computop\Exception\PaymentMethodNotFoundException
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
     * @throws \SprykerEco\Yves\Computop\Exception\PaymentMethodNotFoundException
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setComputopPaymentToQuote(QuoteTransfer $quoteTransfer, $paymentSelection, AbstractTransfer $computopPaymentTransfer)
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

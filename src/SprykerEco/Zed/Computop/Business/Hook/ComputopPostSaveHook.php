<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Yves\Computop\Handler\Exception\PaymentMethodNotFoundException;
use SprykerEco\Zed\Computop\Business\Exception\ComputopMethodMapperException;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\AbstractMapperInterface;
use SprykerEco\Zed\Computop\ComputopConfig;

class ComputopPostSaveHook
{
    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\AbstractMapperInterface[]
     */
    protected $methodMappers = [];

    /**
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     */
    public function __construct(ComputopConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\AbstractMapperInterface $paymentMethod
     *
     * @return void
     */
    public function registerMapper(AbstractMapperInterface $paymentMethod)
    {
        $this->methodMappers[$paymentMethod->getMethodName()] = $paymentMethod;
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
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function setRedirect(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $computopPaymentTransfer = $this->getPaymentTransfer($quoteTransfer);

        $checkoutResponseTransfer
            ->setIsExternalRedirect(true)
            ->setRedirectUrl($computopPaymentTransfer->getUrl());
    }

    /**
     * @param string $methodName
     *
     * @throws \SprykerEco\Zed\Computop\Business\Exception\ComputopMethodMapperException
     *
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\AbstractMapperInterface
     */
    protected function getMethodMapper($methodName)
    {
        if (isset($this->methodMappers[$methodName]) === false) {
            throw new ComputopMethodMapperException('The method mapper is not registered.');
        }

        return $this->methodMappers[$methodName];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \SprykerEco\Yves\Computop\Handler\Exception\PaymentMethodNotFoundException
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function getPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();

        $paymentMethod = ucfirst($paymentSelection);
        $method = 'get' . $paymentMethod;
        $paymentTransfer = $quoteTransfer->getPayment();

        if (!method_exists($paymentTransfer, $method) || ($quoteTransfer->getPayment()->$method() === null)) {
            throw new PaymentMethodNotFoundException(sprintf('Selected payment method "%s" not found in PaymentTransfer', $paymentMethod));
        }

        $computopPaymentTransfer = $quoteTransfer->getPayment()->$method();
        $computopPaymentTransfer = $this->getMethodMapper($paymentSelection)->updateComputopPaymentTransfer($quoteTransfer, $computopPaymentTransfer);

        return $computopPaymentTransfer;
    }
}

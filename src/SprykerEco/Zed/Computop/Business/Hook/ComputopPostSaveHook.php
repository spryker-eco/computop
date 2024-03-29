<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ComputopInitPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Zed\Computop\Business\Exception\ComputopMethodMapperException;
use SprykerEco\Zed\Computop\Business\Exception\PaymentMethodNotFoundException;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface;
use SprykerEco\Zed\Computop\ComputopConfig;

class ComputopPostSaveHook implements ComputopPostSaveHookInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @var array<\SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface>
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
     * @param \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface $paymentMethod
     *
     * @return void
     */
    public function registerMapper(InitMapperInterface $paymentMethod): void
    {
        $this->methodMappers[$paymentMethod->getMethodName()] = $paymentMethod;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): CheckoutResponseTransfer
    {
        $paymentTransfer = $quoteTransfer->getPayment();
        if (!$paymentTransfer || $paymentTransfer->getPaymentProvider() !== ComputopSharedConfig::PROVIDER_NAME) {
            return $checkoutResponseTransfer;
        }

        $quoteTransfer->setOrderReference($checkoutResponseTransfer->getSaveOrderOrFail()->getOrderReference());
        try {
            $computopPaymentTransfer = $this->getPaymentTransfer($quoteTransfer);
        } catch (PaymentMethodNotFoundException $exception) {
            return $checkoutResponseTransfer;
        }

        if ($this->isPaymentInitRequired($paymentTransfer)) {
            $checkoutResponseTransfer->setComputopInitPayment(
                (new ComputopInitPaymentTransfer())
                    ->setData($computopPaymentTransfer->getData())
                    ->setLen($computopPaymentTransfer->getLen()),
            );

            return $checkoutResponseTransfer;
        }

        return $this->setRedirect($computopPaymentTransfer, $checkoutResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $computopPaymentTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function setRedirect(
        TransferInterface $computopPaymentTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): CheckoutResponseTransfer {
        $checkoutResponseTransfer
            ->setIsExternalRedirect(true)
            ->setRedirectUrl($computopPaymentTransfer->getUrl());

        return $checkoutResponseTransfer;
    }

    /**
     * @param string $methodName
     *
     * @throws \SprykerEco\Zed\Computop\Business\Exception\ComputopMethodMapperException
     *
     * @return \SprykerEco\Zed\Computop\Business\Hook\Mapper\Init\InitMapperInterface
     */
    protected function getMethodMapper(string $methodName): InitMapperInterface
    {
        if (isset($this->methodMappers[$methodName]) === false) {
            throw new ComputopMethodMapperException('The method mapper is not registered.');
        }

        return $this->methodMappers[$methodName];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \SprykerEco\Zed\Computop\Business\Exception\PaymentMethodNotFoundException
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function getPaymentTransfer(QuoteTransfer $quoteTransfer): TransferInterface
    {
        $paymentTransfer = $quoteTransfer->getPaymentOrFail();
        $paymentSelection = $paymentTransfer->getPaymentSelectionOrFail();
        $paymentMethod = ucfirst($paymentSelection);
        $method = 'get' . $paymentMethod;

        if (!method_exists($paymentTransfer, $method) || ($quoteTransfer->getPayment()->$method() === null)) {
            throw new PaymentMethodNotFoundException(sprintf('Selected payment method "%s" not found in PaymentTransfer', $paymentMethod));
        }

        $computopPaymentTransfer = $paymentTransfer->$method();
        $methodMapper = $this->getMethodMapper($paymentSelection);

        return $methodMapper->updateComputopPaymentTransfer($quoteTransfer, $computopPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return bool
     */
    protected function isPaymentInitRequired(PaymentTransfer $paymentTransfer): bool
    {
        return in_array($paymentTransfer->getPaymentSelection(), [
            ComputopSharedConfig::PAYMENT_METHOD_PAY_NOW,
            ComputopSharedConfig::PAYMENT_METHOD_EASY_CREDIT,
            ComputopSharedConfig::PAYMENT_METHOD_CREDIT_CARD,
        ], true);
    }
}

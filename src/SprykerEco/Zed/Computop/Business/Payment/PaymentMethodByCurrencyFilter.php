<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Computop\ComputopConfig;

class PaymentMethodByCurrencyFilter implements PaymentMethodFilterInterface
{
    protected const COMPUTOP_PAYMENT_METHOD = 'computop';

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
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer): PaymentMethodsTransfer
    {
        $currencyCode = $quoteTransfer->getCurrency()->getCode();
        $availableCurrencies = $this->config->getComputopPaymentMethodCurrencyFilterMap();

        $applicablePaymentMethods = new ArrayObject();
        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethod) {
            if ($this->isComputopAvailableMethod($paymentMethod, $currencyCode, $availableCurrencies)) {
                $applicablePaymentMethods->append($paymentMethod);
            }
        }

        return $paymentMethodsTransfer->setMethods($applicablePaymentMethods);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param string $currencyCode
     * @param array $availableCurrencies
     *
     * @return bool
     */
    protected function isComputopAvailableMethod(
        PaymentMethodTransfer $paymentMethodTransfer,
        string $currencyCode,
        array $availableCurrencies
    ): bool {
        if (strpos($paymentMethodTransfer->getPaymentMethodKey(), static::COMPUTOP_PAYMENT_METHOD) === false) {
            return true;
        }

        if (!isset($availableCurrencies[$paymentMethodTransfer->getPaymentMethodKey()])) {
            return true;
        }

        if (in_array(strtoupper($currencyCode), $availableCurrencies[$paymentMethodTransfer->getPaymentMethodKey()], true)) {
            return true;
        }

        return false;
    }
}

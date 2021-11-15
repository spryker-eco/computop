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
    /**
     * @var string
     */
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
        $currencyCode = $quoteTransfer->getCurrencyOrFail()->getCodeOrFail();
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
        if (substr($paymentMethodTransfer->getPaymentMethodKeyOrFail(), 0, 8) !== static::COMPUTOP_PAYMENT_METHOD) {
            return true;
        }

        $availableCurrencyForSelectedPaymentMethod = $availableCurrencies[$paymentMethodTransfer->getPaymentMethodKey()] ?? null;
        if (!$availableCurrencyForSelectedPaymentMethod) {
            return true;
        }

        return in_array(strtoupper($currencyCode), $availableCurrencyForSelectedPaymentMethod, true);
    }
}

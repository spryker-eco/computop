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

class PaymentMethodFilter implements PaymentMethodFilterInterface
{
    /**
     * @var string
     */
    protected const COMPUTOP_PAYMENT_METHOD = 'computop';

    /**
     * @var string
     */
    protected const CONFIG_METHOD_PART_GET_CRIF = 'getCrif';

    /**
     * @var string
     */
    protected const CONFIG_METHOD_PART_PAYMENT_METHODS = 'PaymentMethods';

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
    public function filterPaymentMethods(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        QuoteTransfer $quoteTransfer
    ): PaymentMethodsTransfer {
        if (!$this->config->isCrifEnabled()) {
            return $paymentMethodsTransfer;
        }

        $availableMethods = $this->getAvailablePaymentMethods($quoteTransfer);

        $result = new ArrayObject();

        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethod) {
            if ($this->isPaymentMethodComputop($paymentMethod) && !$this->isAvailable($paymentMethod, $availableMethods)) {
                continue;
            }

            $result->append($paymentMethod);
        }

        $paymentMethodsTransfer->setMethods($result);

        return $paymentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    protected function getAvailablePaymentMethods(QuoteTransfer $quoteTransfer): array
    {
        $method = static::CONFIG_METHOD_PART_GET_CRIF .
            ucfirst(strtolower($quoteTransfer->getComputopCrif()->getResult())) .
            static::CONFIG_METHOD_PART_PAYMENT_METHODS;

        if (method_exists($this->config, $method)) {
            return $this->config->$method();
        }

        return $this->config->getCrifRedPaymentMethods();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param string[] $availableMethods
     *
     * @return bool
     */
    protected function isAvailable(PaymentMethodTransfer $paymentMethodTransfer, $availableMethods): bool
    {
        return in_array($paymentMethodTransfer->getMethodName(), $availableMethods);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return bool
     */
    protected function isPaymentMethodComputop(PaymentMethodTransfer $paymentMethodTransfer): bool
    {
        return strpos($paymentMethodTransfer->getMethodName(), static::COMPUTOP_PAYMENT_METHOD) !== false;
    }
}

<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\CheckoutWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CheckoutWidget\Widget\ProceedToCheckoutButtonWidget as SprykerProceedToCheckoutButtonWidgetAlias;

/**
 * @method \SprykerEco\Yves\Computop\ComputopConfig getConfig()
 */
class ProceedToCheckoutButtonWidget extends SprykerProceedToCheckoutButtonWidgetAlias
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        parent::__construct($quoteTransfer);
        $this->addParameter('clientId', $this->getPayPalClientId());
    }

    /**
     * @return string
     */
    protected function getPayPalClientId(): string
    {
        return $this->getConfig()->getPayPalClientId();
    }
}

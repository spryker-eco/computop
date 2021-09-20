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
     * @var string
     */
    protected const PARAMETER_CLIENT_ID = 'clientId';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        parent::__construct($quoteTransfer);
        $this->addClientId();
    }

    /**
     * @return void
     */
    protected function addClientId(): void
    {
        $this->addParameter(static::PARAMETER_CLIENT_ID, $this->getConfig()->getPayPalClientId());
    }
}

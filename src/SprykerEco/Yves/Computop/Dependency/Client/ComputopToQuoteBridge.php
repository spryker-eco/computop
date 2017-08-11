<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\QuoteClientInterface;

class ComputopToQuoteBridge implements ComputopToQuoteInterface
{

    /**
     * @var \Spryker\Client\Quote\QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\Quote\QuoteClientInterface $quoteClient
     */
    public function __construct(QuoteClientInterface $quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @inheritdoc
     */
    public function getQuote()
    {
        return $this->quoteClient->getQuote();
    }

    /**
     * @inheritdoc
     */
    public function setQuote(QuoteTransfer $quoteTransfer)
    {
        $this->quoteClient->setQuote($quoteTransfer);
    }

}

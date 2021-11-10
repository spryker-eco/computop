<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface;
use SprykerEco\Yves\Computop\Mapper\Init\MapperInterface;

abstract class AbstractFormDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerEco\Yves\Computop\Mapper\Init\MapperInterface
     */
    protected $mapper;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    abstract protected function getComputopPayment(QuoteTransfer $quoteTransfer);

    /**
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteClientInterface $quoteClient
     * @param \SprykerEco\Yves\Computop\Mapper\Init\MapperInterface $mapper
     */
    public function __construct(ComputopToQuoteClientInterface $quoteClient, MapperInterface $mapper)
    {
        $this->quoteClient = $quoteClient;
        $this->mapper = $mapper;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isValidPayment(QuoteTransfer $quoteTransfer): bool
    {
        $computopPaymentTransfer = $this->getComputopPayment($quoteTransfer);
        if ($computopPaymentTransfer === null) {
            return false;
        }

        return $computopPaymentTransfer->getAmount() === $quoteTransfer->getTotals()->getGrandTotal();
    }
}

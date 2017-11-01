<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form\DataProvider;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteInterface;
use SprykerEco\Yves\Computop\Mapper\Order\MapperInterface;

abstract class AbstractFormDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    abstract protected function getComputopPayment(AbstractTransfer $quoteTransfer);

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerEco\Yves\Computop\Mapper\Order\MapperInterface
     */
    protected $cardMapper;

    /**
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteInterface $quoteClient
     * @param \SprykerEco\Yves\Computop\Mapper\Order\MapperInterface $cardMapper
     */
    public function __construct(ComputopToQuoteInterface $quoteClient, MapperInterface $cardMapper)
    {
        $this->quoteClient = $quoteClient;
        $this->cardMapper = $cardMapper;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        return [];
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return boolean
     */
    protected function isValidPayment(AbstractTransfer $quoteTransfer)
    {
        if ($this->getComputopPayment($quoteTransfer) === null) {
            return false;
        }

        return $this->getComputopPayment($quoteTransfer)->getAmount() === $quoteTransfer->getTotals()->getGrandTotal();
    }
}

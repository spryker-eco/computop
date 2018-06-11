<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\PrePlace;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMoneyFacadeInterface;

class EasyCreditStatusHandler extends AbstractHandler
{
    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface
     */
    protected $logger;

    /**
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface $computopApiFacade
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMoneyFacadeInterface $moneyFacade
     * @param \SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface $logger
     */
    public function __construct(
        ComputopToComputopApiFacadeInterface $computopApiFacade,
        ComputopToMoneyFacadeInterface $moneyFacade,
        ComputopResponseLoggerInterface $logger
    ) {
        parent::__construct($computopApiFacade);
        $this->moneyFacade = $moneyFacade;
        $this->logger = $logger;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function handle(QuoteTransfer $quoteTransfer)
    {
        $computopHeaderPayment = $this->createComputopHeaderPayment($quoteTransfer);

        /** @var \Generated\Shared\Transfer\ComputopEasyCreditStatusResponseTransfer $responseTransfer */

        $responseTransfer = $this
            ->computopApiFacade
            ->performEasyCreditStatusRequest($quoteTransfer, $computopHeaderPayment);

        $decision = $this->parseStatusExplanation($responseTransfer->getDecision());
        $financing = $this->parseStatusExplanation($responseTransfer->getFinancing());
        $responseTransfer->getHeader()->setIsSuccess($this->parseStatusValue($decision));

        $paymentAmount = $this->parsePaymentAmount($financing);
        $quoteTransfer->getPayment()->getComputopEasyCredit()->setEasyCreditStatusResponse($responseTransfer);

        if ($paymentAmount) {
            $quoteTransfer->getPayment()->setAmount($paymentAmount);
            $quoteTransfer->getPayment()->getComputopEasyCredit()->setAmount($paymentAmount);
        }
        $this->logger->log($responseTransfer->getHeader(), $responseTransfer->getHeader()->getMethod());

        return $responseTransfer;
    }

    /**
     * @param string $status
     *
     * @return array
     */
    protected function parseStatusExplanation($status)
    {
        return json_decode(base64_decode($status), true);
    }

    /**
     * @param array $decision
     *
     * @return bool
     */
    protected function parseStatusValue($decision)
    {
        if (isset($decision['entscheidung']['entscheidungsergebnis'])) {
            return $decision['entscheidung']['entscheidungsergebnis'] === 'GRUEN';
        }

        return false;
    }

    /**
     * @param array $financing
     *
     * @return int|bool
     */
    protected function parsePaymentAmount($financing)
    {
        if (isset($financing['ratenplan']['gesamtsumme'])) {
            $paymentAmount = (float)$financing['ratenplan']['gesamtsumme'];

            return $this->moneyFacade->convertDecimalToInteger($paymentAmount);
        }

        return false;
    }
}

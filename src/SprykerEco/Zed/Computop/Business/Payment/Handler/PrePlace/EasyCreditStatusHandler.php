<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\PrePlace;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopApiFacadeInterface;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToMoneyFacadeInterface;

class EasyCreditStatusHandler extends AbstractHandler
{
    protected const PLAN = 'ratenplan';
    protected const PAY_RENT = 'zinsen';
    protected const ACCRUED_INTEREST = 'anfallendeZinsen';
    protected const TOTAL = 'gesamtsumme';
    protected const DECISION = 'entscheidung';
    protected const DECISION_RESULT = 'entscheidungsergebnis';
    protected const DECISION_RESULT_GREEN = 'GRUEN';

    protected const COMPUTOP_EASY_CREDIT_EXPENSE_TYPE = 'COMPUTOP_EASY_CREDIT_EXPENSE_TYPE';

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
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handle(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $computopHeaderPayment = $this->createComputopHeaderPayment($quoteTransfer);

        $responseTransfer = $this
            ->computopApiFacade
            ->performEasyCreditStatusRequest($quoteTransfer, $computopHeaderPayment);

        if ($responseTransfer->getHeader()->getIsSuccess()) {
            $decision = $this->parseStatusExplanation((string)$responseTransfer->getDecision());
            $financing = $this->parseStatusExplanation((string)$responseTransfer->getFinancing());
            $process = $this->parseStatusExplanation((string)$responseTransfer->getProcess());

            $responseTransfer->setDecisionData($decision);
            $responseTransfer->setFinancingData($financing);
            $responseTransfer->setProcessData($process);

            $quoteTransfer = $this->addExpense($quoteTransfer, $financing);
        }

        $quoteTransfer->getPayment()->getComputopEasyCredit()->setEasyCreditStatusResponse($responseTransfer);

        $this->logger->log($responseTransfer->getHeader(), (string)$responseTransfer->getHeader()->getMethod());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string[] $financing
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addExpense(QuoteTransfer $quoteTransfer, array $financing): QuoteTransfer
    {
        $expenses = $quoteTransfer->getExpenses();

        foreach ($expenses as $index => $expenseTransfer) {
            if ($expenseTransfer->getType() === static::COMPUTOP_EASY_CREDIT_EXPENSE_TYPE) {
                $expenses->offsetUnset($index);
            }
        }

        $expense = (new ExpenseTransfer())
            ->setType(static::COMPUTOP_EASY_CREDIT_EXPENSE_TYPE)
            ->setName(ComputopConfig::PAYMENT_METHOD_EASY_CREDIT)
            ->setUnitGrossPrice($this->parseExpense($financing))
            ->setQuantity(1);

        $expenses->append($expense);
        $quoteTransfer->setExpenses($expenses);

        return $quoteTransfer;
    }

    /**
     * @param array $financing
     *
     * @return int
     */
    protected function parseExpense(array $financing): int
    {
        if (!isset($financing[static::PLAN][static::PAY_RENT][static::ACCRUED_INTEREST])) {
            return 0;
        }

        $expense = (float)$financing[static::PLAN][static::PAY_RENT][static::ACCRUED_INTEREST];

        return $this->moneyFacade->convertDecimalToInteger($expense);
    }

    /**
     * @param string $status
     *
     * @return array
     */
    protected function parseStatusExplanation(string $status): array
    {
        return json_decode(base64_decode($status), true);
    }

    /**
     * @param array $decision
     *
     * @return bool
     */
    protected function parseStatusValue(array $decision): bool
    {
        if (isset($decision[static::DECISION][static::DECISION_RESULT])) {
            return $decision[static::DECISION][static::DECISION_RESULT] === static::DECISION_RESULT_GREEN;
        }

        return false;
    }

    /**
     * @param array $financing
     *
     * @return int
     */
    protected function parsePaymentAmount(array $financing): int
    {
        if (!isset($financing[static::PLAN][static::TOTAL])) {
            return 0;
        }

        $paymentAmount = (float)$financing[static::PLAN][static::TOTAL];

        return $this->moneyFacade->convertDecimalToInteger($paymentAmount);
    }
}

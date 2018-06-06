<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Request\RiskCheck;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface;
use SprykerEco\Zed\Computop\Business\Api\Mapper\RiskCheck\ApiRiskCheckMapperInterface;

abstract class AbstractRiskCheckRequest implements RiskCheckRequestInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    protected $converter;

    /**
     * @var \SprykerEco\Zed\Computop\Business\Api\Mapper\RiskCheck\ApiRiskCheckMapperInterface
     */
    protected $mapper;

    /**
     * @param \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface $adapter
     * @param \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface $converter
     * @param \SprykerEco\Zed\Computop\Business\Api\Mapper\RiskCheck\ApiRiskCheckMapperInterface $mapper
     */
    public function __construct(
        AdapterInterface $adapter,
        ConverterInterface $converter,
        ApiRiskCheckMapperInterface $mapper
    ) {
        $this->adapter = $adapter;
        $this->converter = $converter;
        $this->mapper = $mapper;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $requestData = $this->mapper->buildRequest($quoteTransfer);

        return $this->sendRequest($requestData);
    }

    /**
     * @param array $requestData
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function sendRequest(array $requestData)
    {
        $response = $this
            ->adapter
            ->sendRequest($requestData);

        $responseTransfer = $this
            ->converter
            ->toTransactionResponseTransfer(
                $response
            );

        return $responseTransfer;
    }
}

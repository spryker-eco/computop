<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler;

use SprykerEco\Client\Computop\ComputopClientInterface;
use SprykerEco\Yves\Computop\Converter\ConverterInterface;

abstract class AbstractPrePostPaymentHandler implements ComputopPrePostPaymentHandlerInterface
{
    /**
     * @var \SprykerEco\Yves\Computop\Converter\ConverterInterface
     */
    protected $converter;

    /**
     * @var \SprykerEco\Client\Computop\ComputopClientInterface
     */
    protected $computopClient;

    /**
     * @param \SprykerEco\Yves\Computop\Converter\ConverterInterface $converter
     * @param \SprykerEco\Client\Computop\ComputopClientInterface $computopClient
     */
    public function __construct(ConverterInterface $converter, ComputopClientInterface $computopClient)
    {
        $this->converter = $converter;
        $this->computopClient = $computopClient;
    }
}

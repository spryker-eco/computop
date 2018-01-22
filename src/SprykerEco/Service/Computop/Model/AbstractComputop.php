<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use SprykerEco\Service\Computop\ComputopConfigInterface;

abstract class AbstractComputop
{
    const MAC_SEPARATOR = '*';
    const DATA_SEPARATOR = '&';
    const DATA_SUB_SEPARATOR = '=';

    /**
     * @var \Spryker\Service\Kernel\AbstractBundleConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Service\Computop\ComputopConfigInterface $config
     */
    public function __construct(ComputopConfigInterface $config)
    {
        $this->config = $config;
    }
}

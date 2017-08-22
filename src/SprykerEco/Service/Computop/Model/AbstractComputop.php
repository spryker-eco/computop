<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use SprykerEco\Service\Computop\ComputopConfig;

abstract class AbstractComputop
{

    const MAC_SEPARATOR = '*';
    const DATA_SEPARATOR = '&';
    const DATA_SUB_SEPARATOR = '=';

    /**
     * @var \SprykerEco\Service\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Service\Computop\ComputopConfig $config
     */
    public function __construct(ComputopConfig $config)
    {
        $this->config = $config;
    }

}

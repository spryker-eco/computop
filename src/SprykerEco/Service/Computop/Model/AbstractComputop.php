<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use Spryker\Service\Kernel\AbstractBundleConfig;

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
     * @param \Spryker\Service\Kernel\AbstractBundleConfig $config
     */
    public function __construct(AbstractBundleConfig $config)
    {
        $this->config = $config;
    }

}

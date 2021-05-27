<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Dependency\Facade;

use Propel\Runtime\Collection\ObjectCollection;

interface ComputopToOmsFacadeInterface
{
    /**
     * @param string $eventId
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    //phpcs:ignore
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = []);
}

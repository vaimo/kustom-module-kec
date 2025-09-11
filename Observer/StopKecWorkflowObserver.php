<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Observer;

use Klarna\Kec\Model\StopWorkflow;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * @internal
 */
class StopKecWorkflowObserver implements ObserverInterface
{
    /**
     * @var StopWorkflow
     */
    private StopWorkflow $stopWorkflow;

    /**
     * @param StopWorkflow $stopWorkflow
     * @codeCoverageIgnore
     */
    public function __construct(StopWorkflow $stopWorkflow)
    {
        $this->stopWorkflow = $stopWorkflow;
    }

    /**
     * Preparing the capture
     *
     * @param Observer $observer
     * @return void
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer)
    {
        $this->stopWorkflow->stopKecWorkflow();
    }
}

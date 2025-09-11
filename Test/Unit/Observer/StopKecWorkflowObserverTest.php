<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Test\Unit\Observer;

use Klarna\Kec\Observer\StopKecWorkflowObserver;
use Magento\Framework\Event\Observer;
use Klarna\Base\Test\Unit\Mock\TestCase;

class StopKecWorkflowObserverTest extends TestCase
{
    /**
     * @var StopKecWorkflowObserver
     */
    private $stopKecWorkflow;
    /**
     * @var Observer
     */
    private $observer;

    public function testExecute()
    {
        $this->dependencyMocks['stopWorkflow']
            ->expects($this->once())
            ->method('stopKecWorkflow');
        $this->stopKecWorkflow->execute($this->observer);
    }

    protected function setUp(): void
    {
        $this->stopKecWorkflow = parent::setUpMocks(StopKecWorkflowObserver::class);
        $this->observer = $this->mockFactory->create(Observer::class);
    }

}
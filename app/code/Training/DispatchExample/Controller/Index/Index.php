<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Training\DispatchExample\Controller\Index;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index implements ActionInterface
{

    /**
     * @var PageFactory $pageFactory
     */
    private PageFactory $pageFactory;

    /**
     * @var ManagerInterface $eventManager
     */
    private ManagerInterface $eventManager;

    public function __construct(
        PageFactory $pageFactory,
        ManagerInterface $eventManager
    ) {
        $this->pageFactory = $pageFactory;
        $this->eventManager = $eventManager;
    }

    public function execute(): Page|ResultInterface|ResponseInterface
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->set('Dispatch Event Example');
        $this->eventManager->dispatch('training_dispatch_example', ['page' => $resultPage]);
        return $resultPage;
    }
}

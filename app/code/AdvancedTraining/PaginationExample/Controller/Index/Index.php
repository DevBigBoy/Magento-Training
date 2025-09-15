<?php

declare(strict_types=1);

namespace AdvancedTraining\PaginationExample\Controller\Index;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected PageFactory $resultPageFactory;
    private CollectionFactory $productCollectionFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CollectionFactory $productCollectionFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        // Get page from request (default = 1)
        $currentPage = (int) $this->getRequest()->getParam('p', 1);

        // Set how many products per page
        $pageSize = 5;

        // Create product collection
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect(['name', 'price', 'sku']);
        $collection->addAttributeToFilter('status', 1); // enabled only
        $collection->setPageSize($pageSize);
        $collection->setCurPage($currentPage);

        // Total products and pages
        $totalProducts = $collection->getSize();

        $totalPages = ceil($totalProducts / $pageSize);

        // Debug: dump results
        foreach ($collection as $product) {
            echo "ID: {$product->getId()} - {$product->getName()} - {$product->getSku()} - {$product->getPrice()} <br>";
        }

        echo "<br><strong>Total Products:</strong> {$totalProducts}<br>";
        echo "<strong>Page:</strong> {$currentPage} of {$totalPages}<br>";

        // Pagination links
        echo "<div style='margin-top:10px;'>";
        for ($page = 1; $page <= $totalPages; $page++) {
            if ($page == $currentPage) {
                echo "[ <strong>$page</strong> ] ";
            } else {
                echo "<a href='?p={$page}'>$page</a> ";
            }
        }
        echo "</div>";

        exit;
    }
}

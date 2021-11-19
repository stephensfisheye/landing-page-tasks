<?php declare(strict_types=1);

namespace Stephen\LandingPages\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class HeroCategories implements ArgumentInterface
{
    protected CollectionFactory $categoryCollectionFactory;

    public function __construct(
        CollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    public function getCategories(int $level, string $sortAttribute, string $sortDirection, int $resultsLimit): Collection
    {
        /** @var Collection $categoryCollection */
        $categoryCollection = $this->categoryCollectionFactory->create();
        $categoryCollection->addAttributeToFilter(
                'level',
                ['eq' => $level]
            )
            ->addAttributeToFilter(
                'is_active',
                ['eq' => '1']
            )
            ->addAttributeToSelect('name')
            ->setOrder($sortAttribute, $sortDirection);
        $categoryCollection->setPageSize($resultsLimit);

        return $categoryCollection;
    }
}

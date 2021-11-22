<?php declare(strict_types=1);

namespace Stephen\LandingPages\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Helper\Image;
use Magento\Directory\Model\Currency;
use Magento\Framework\App\Request\Http;

class ActivityProducts implements ArgumentInterface
{
    protected CollectionFactory $productCollectionFactory;
    protected Visibility $visibility;
    protected Image $imageHelper;
    protected Currency $currency;
    protected Http $http;

    public function __construct(
        CollectionFactory $productCollectionFactory,
        Image $imageHelper,
        Currency $currency,
        Http $http
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->imageHelper = $imageHelper;
        $this->currency = $currency;
        $this->http = $http;
    }

    public function getProducts(int $status, string $sortAttribute, string $sortDirection, int $resultsLimit): Collection
    {
        /** @var Collection $productCollection */
        $productCollection = $this->productCollectionFactory->create();
        
        /** @var Http $http */
        $queryStrActivity = '%' . $this->http->getParam('activity') . '%';
        if($queryStrActivity !== null)
        {   
            $productCollection->addFieldToFilter(
                'activity',
                ['like' => $queryStrActivity]
            );
        }
        
        $productCollection->addAttributeToFilter(
                'status',
                ['eq' => $status]
            )
            ->addAttributeToFilter(
                'visibility',
                Visibility::VISIBILITY_BOTH
            )
            ->addAttributeToFilter(
                'activity',
                ['neq' => null]
            )
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price')
            ->addAttributeToSelect('small_image')
            ->addAttributeToSelect('activity')
            ->addAttributeToSelect('url')
            ->setOrder($sortAttribute, $sortDirection);
        $productCollection->setPageSize($resultsLimit);

        return $productCollection;
    }

    public function getImageUrl(Product $product, string $imageId): string
    {
        $imageUrl = $this->imageHelper->init($product, $imageId)->getUrl();

        return $imageUrl;
    }

    public function getCurrencySymbol(): string
    {
        $currencySymbol = $this->currency->getCurrencySymbol();

        return $currencySymbol;
    }
}

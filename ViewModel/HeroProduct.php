<?php declare(strict_types=1);

namespace Stephen\LandingPages\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product as ResourceProduct;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Directory\Model\Currency;
use Magento\Tax\Api\TaxCalculationInterface;
use Magento\Catalog\Block\Product\Context;

class HeroProduct implements ArgumentInterface
{
    protected ProductFactory $productFactory;
    protected ResourceProduct $resourceProduct;
    protected Currency $currency;
    protected TaxCalculationInterface $taxCalculation;
    protected Context $context;
    protected ImageBuilder $imageBuilder;

    public function __construct(
        ProductFactory $productFactory,
        ResourceProduct $resourceProduct,
        Currency $currency,
        TaxCalculationInterface $taxCalculation,
        Context $context
    ) {
        $this->productFactory = $productFactory;
        $this->resourceProduct = $resourceProduct;
        $this->currency = $currency;
        $this->taxCalculation = $taxCalculation;
        $this->context = $context;
        $this->imageBuilder = $this->context->getImageBuilder();
    }

    public function getProduct(int $id): Product
    {
        /** @var Product $product */
        $product = $this->productFactory->create();
        $this->resourceProduct->load($product, $id);

        return $product;
    }

    public function getCurrencySymbol(): string
    {
        $currencySymbol = $this->currency->getCurrencySymbol();

        return $currencySymbol;
    }

    public function getTaxRate(Product $product): float
    {
        $productTaxClassID = $product->getTaxClassId();
        $taxRate = $this->taxCalculation->getDefaultCalculatedRate($productTaxClassID);

        return $taxRate;
    }

    public function getProductImage(Product $product, string $imageId, array $attributes = []): string
    {
        $image = $this->imageBuilder->create($product, $imageId, $attributes);

        return $image->getImageUrl();
    }
}

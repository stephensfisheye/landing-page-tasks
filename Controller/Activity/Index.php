<?php declare(strict_types=1);

namespace Stephen\LandingPages\Controller\Activity;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;

class Index implements HttpGetActionInterface
{
    private ResultFactory $resultFactory;

    public function __construct(ResultFactory $resultFactory)
    {
        $this->resultFactory = $resultFactory;
    }

    public function execute(): Page
    {
        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->getConfig()->getTitle()->set(__('Products By Activity'));
        return $page;
    }
}

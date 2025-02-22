<?php

namespace Maddlen\Nivo\Model\Cms;

use Magento\Cms\Model\Page as MagePage;
use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @mixin MagePage
 */
class Page
{
    private MagePage $page;

    public function __construct(
        private readonly RequestInterface      $request,
        private readonly PageFactory           $pageFactory,
        private readonly StoreManagerInterface $storeManager,
        private readonly PageResource          $pageResource,
        private readonly FilterProvider        $filterProvider,
    )
    {
        $this->loadPage();
    }

    private function loadPage(): void
    {
        $page = $this->pageFactory->create();
        $page->setStoreId($this->storeManager->getStore()->getId());
        $this->pageResource->load($page, $this->request->getParam('page_id'));
        $this->page = $page;
    }

    public function __call($method, $args)
    {
        return $this->page->{$method}(...$args);
    }

    public function content(): string
    {
        return $this->filterProvider->getPageFilter()->filter($this->page->getContent());
    }


}

<?php

namespace Maddlen\Nivo\Plugin\Framework\Controller;

use Maddlen\Nivo\Framework\View\Result\Page;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Store\Model\ScopeInterface;

class ResultInterfacePlugin
{
    public function __construct(
        private readonly RequestInterface     $request,
        private readonly ScopeConfigInterface $scopeConfig,
    )
    {
    }

    /**
     * @param ResultInterface $subject
     * @param ResponseInterface $response
     * @return array
     */
    public function beforeRenderResult(ResultInterface $subject, ResponseInterface $response): array
    {
        if ($subject instanceof Page && $subject->getDefaultLayoutHandle() === 'cms_index_index') {
            $pageId = $this->scopeConfig->getValue(\Magento\Cms\Helper\Page::XML_PATH_HOME_PAGE, ScopeInterface::SCOPE_STORE);
            $this->request->setParam('page_id', $pageId);
        }
        return [$response];
    }
}

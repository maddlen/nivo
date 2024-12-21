<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Nivo\Providers;

use Maddlen\Zermatt\App\App;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Page\Config;

class StoreProvider implements ArgumentInterface
{
    public function __construct(
        protected Config $pageConfig,
        protected App $app
    )
    {
    }

    public function app(): App
    {
        return $this->app;
    }
    public function lang(): string
    {
        return $this->pageConfig->getElementAttribute(Config::ELEMENT_TYPE_HTML, Config::HTML_ATTRIBUTE_LANG);
    }

    public function metaDescription(): string
    {
        return $this->pageConfig->getDescription() ?: '';
    }

    public function metaKeywords(): string
    {
        return $this->pageConfig->getKeywords() ?: '';
    }

    public function title(): string
    {
        return $this->pageConfig->getTitle()->get();
    }

    public function canonical(): string
    {
        $pageAssets = $this->pageConfig->getAssetCollection()->getAll();
        foreach ($pageAssets as $asset) {
            if ($asset->getContentType() === 'canonical') {
                return $asset->getUrl();
            }
        }
        return '';
    }
}

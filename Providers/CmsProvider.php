<?php

namespace Maddlen\Nivo\Providers;

use Maddlen\Nivo\Model\Cms\Page;
use Maddlen\Nivo\Model\Cms\PageFactory;

readonly class CmsProvider implements ContainerProviderInterface
{
    public function __construct(
        private PageFactory $pageFactory
    )
    {
    }

    public function page(): Page
    {
        return $this->pageFactory->create();
    }
}

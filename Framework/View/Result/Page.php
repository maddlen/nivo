<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Nivo\Framework\View\Result;

use Exception;
use Maddlen\Zermatt\Partial\Render;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Response\HttpInterface as HttpResponseInterface;
use Magento\Framework\View;

class Page extends View\Result\Page
{
    protected $noRouteTemplate = 'Maddlen_Nivo::routes/no_route.phtml';

    protected function render(HttpResponseInterface $response)
    {
        $this->pageConfig->publicBuild();
        if ($this->getPageLayout()) {
            $layout = $this->layoutFactory->create();
            $output = $layout->getOutput();
            $this->assign('layoutContent', $output);
            $this->template = sprintf('Maddlen_Nivo::routes/%s.phtml', $this->getDefaultLayoutHandle());
            try {
                $output = $this->renderPage();
            } catch (Exception $e) {
                $this->template = $this->noRouteTemplate;
                $output = $this->renderPage();
            }
            $this->translateInline->processResponseBody($output);
            $response->appendBody($output);
        } else {
            parent::render($response);
        }
        return $this;
    }

    protected function renderPage()
    {
        $partialRender = ObjectManager::getInstance()->get(Render::class); // Still cleaner than overriding parent constructor
        return $partialRender->output(parent::renderPage());
    }
}

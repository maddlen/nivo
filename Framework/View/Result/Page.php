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
use Magento\Framework\View\Result\Page as MagePage;

class Page extends MagePage
{
    protected $viewVars = [];
    protected $noRouteTemplate = 'Maddlen_Nivo::routes/no_route.phtml';

    protected function render(HttpResponseInterface $response)
    {
        $this->pageConfig->publicBuild();
        $this->template = sprintf('Maddlen_Nivo::routes/%s.phtml', $this->getDefaultLayoutHandle());
        try {
            $output = $this->renderPage();
        } catch (Exception $e) {
            $this->template = $this->noRouteTemplate;
            $output = $this->renderPage();
        }
        $this->translateInline->processResponseBody($output);
        $response->appendBody($output);
        return $this;
    }

    protected function renderPage()
    {
        $partialRender = ObjectManager::getInstance()->get(Render::class); // Still cleaner than overriding parent constructor
        return $partialRender->output(parent::renderPage());
    }
}

<?php
namespace ProductComparison\Hook\Back;

use ProductComparison\ProductComparison;
use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Translation\Translator;
use Thelia\Tools\URL;

class ProductComparisonHook extends BaseHook
{
    public function onMainTopMenuTools(HookRenderBlockEvent $event)
    {
        $event
            ->add(
                array(
                    'url' => URL::getInstance()->absoluteUrl('/admin/module/ProductComparison/product_comparison'),
                    'title' => Translator::getInstance()->trans('Product Comparison', array(), ProductComparison::MESSAGE_DOMAIN)
                )
            )
        ;
    }
}
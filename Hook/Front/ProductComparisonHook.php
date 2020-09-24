<?php
namespace ProductComparison\Hook\Front;

use ProductComparison\ProductComparison;
use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Translation\Translator;
use Thelia\Tools\URL;
use Thelia\Model\ModuleConfig;
use Thelia\Model\ModuleConfigQuery;
use Thelia\Model\Product;
use Thelia\Model\ProductQuery;
use Thelia\Model\ProductCategoryQuery;

class ProductComparisonHook extends BaseHook
{
    public function onDisplaySingleProduct(HookRenderEvent $event)
    {

        $product_id = $event->getArgument('product');
        $parameters = $this->searchTemplateIdAndCategoryId($product_id);
        $varsConf = $this->getModuleConfig();
        $category_authorized = unserialize($varsConf['category_authorized']) ? unserialize($varsConf['category_authorized']) : [];
        $template_authorized = unserialize($varsConf['template_authorized']) ? unserialize($varsConf['template_authorized']) : [];
        $view = $this->getView();
        if(!empty(array_intersect($parameters['category_id'], $category_authorized)) && !empty(array_intersect($parameters['template_id'], $template_authorized)) && $view === 'category') {
            $event->add(
                $this->render(
                    "single-product.html",
                    [
                        'product_id' => $product_id,
                        'template_id' => $parameters['template_id'],
                    ]
                )
            );
        }
    }

    public function onDisplaySingleProductCss(HookRenderEvent $event)
    {
        $event->add(
            $this->addCSS("assets/css/style.css")
        );
    }

    public function onDisplaySingleProductJs(HookRenderEvent $event)
    {
        $event
            ->add(
                $this->addJS("assets/js/cookie.js"))
            ->add(
                $this->addJS("assets/js/script.js")
        );
    }

    public function onDisplayCategory(HookRenderEvent $event)
    {
        $category_id = $event->getArgument('category');
        $template_id = $this->searchTemplateId($category_id);
        $varsConf = $this->getModuleConfig();

        $category_authorized = unserialize($varsConf['category_authorized']) ? unserialize($varsConf['category_authorized']) : [];
        $template_authorized = unserialize($varsConf['template_authorized']) ? unserialize($varsConf['template_authorized']) : [];

        if(in_array($category_id, $category_authorized) && in_array($template_id, $template_authorized)){
            if(null !== $template_id) {
                $vars = array_merge(['template_id' => $template_id, 'category_id' => $category_id], $varsConf);

                $event
                    ->add(
                        $this->render(
                            "comparator-block.html",
                            $vars
                        )

                    );
            }
        }
    }

    public function onDisplayComparatorJs(HookRenderEvent $event)
    {
        $event
            ->add(
                $this->addJS("assets/js/tableHeadFixer.js")
            );
    }

    private function getModuleConfig()
    {
        $vars =[];
        if (null !== $params = ModuleConfigQuery::create()->findByModuleId(ProductComparison::getModuleId())) {
            /** @var ModuleConfig $param */
            foreach ($params as $param) {
                $vars[ $param->getName() ] = $param->getValue();
            }
        }

        return $vars;
    }

    private function searchTemplateId($category_id)
    {
        $template_id = null;

        if(null !== $params = ProductQuery::create()->joinProductCategory()->where('ProductCategory.category_id = ?', $category_id)->findOne())
        {
            $template_id = $params->getTemplateId();
        }
        return $template_id;
    }

    private function searchTemplateIdAndCategoryId($product_id)
    {
        $template_id = [];
        $category_id = [];
        $product = [];

        if(null !== $params = ProductQuery::create()->findById($product_id))
        {
            foreach($params as $param){
                array_push($template_id, $param->getTemplateId());
            }
        }
        if(null !== $params = ProductCategoryQuery::create()->findByProductId($product_id))
        {
            foreach($params as $param){
                array_push($category_id, $param->getCategoryId());
            }
        }

        $product['template_id']=$template_id;
        $product['category_id']=$category_id;

        return $product;
    }
}

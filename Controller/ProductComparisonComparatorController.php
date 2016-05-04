<?php

namespace ProductComparison\Controller;

use Thelia\Controller\Front\BaseFrontController;
use Thelia\Log\Tlog;
use Thelia\Model\Product;
use Thelia\Model\ProductQuery;
use Thelia\Model\ProductCategoryQuery;


/**
 * Class ProductRegistration
 * @package ProductRegistration
 * @author zzuutt <zzuutt34@free.fr>
 */
class ProductComparisonComparatorController extends BaseFrontController
{
    protected $useFallbackTemplate = true;

    public function addItemAction()
    {
        // only ajax
        $this->checkXmlHttpRequest();

        $request = $this->getRequest();

        $param = $request->get('comparator');

        return $this->render(
            "add-comparator",
            [
                'comparator_items' => $param,
            ]
        );

    }

    public function displayComparatorAction()
    {

        $request = $this->getRequest();
        $param = $request->get('items');
        $first_param = explode(',',base64_decode($param));
        $template = $this->searchTemplateId($first_param[0]);

        return $this->render(
            "comparator",
            [
                'comparator_items' => base64_decode($param),
                'comparator_template' => $template,
            ]
        );

    }

    private function searchTemplateId($product_id)
    {
        $template_id = null;

        if(null !== $params = ProductQuery::create()->findOneById($product_id))
        {
            $template_id = $params->getTemplateId();
        }
        return $template_id;
    }

}
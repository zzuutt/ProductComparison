<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace ProductComparison\Form\Type\Base;

use Thelia\Core\Form\Type\Field\AbstractIdType;
use ProductComparison\Model\ProductComparisonQuery;

/**
 * Class ProductComparison
 * @package ProductComparison\Form\Base
 * @author TheliaStudio
 */
class ProductComparisonIdType extends AbstractIdType
{
    const TYPE_NAME = "product_comparison_id";

    protected function getQuery()
    {
        return new ProductComparisonQuery();
    }

    public function getName()
    {
        return static::TYPE_NAME;
    }
}

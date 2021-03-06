<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace ProductComparison\Event\Base;

use Thelia\Core\Event\ActionEvent;
use ProductComparison\Model\ProductComparison;

/**
* Class ProductComparisonEvent
* @package ProductComparison\Event\Base
* @author TheliaStudio
*/
class ProductComparisonEvent extends ActionEvent
{
    protected $id;
    protected $featureId;
    protected $templateId;
    protected $position;
    protected $productComparison;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getFeatureId()
    {
        return $this->featureId;
    }

    public function setFeatureId($featureId)
    {
        $this->featureId = $featureId;

        return $this;
    }

    public function getTemplateId()
    {
        return $this->templateId;
    }

    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    public function getProductComparison()
    {
        return $this->productComparison;
    }

    public function setProductComparison(ProductComparison $productComparison)
    {
        $this->productComparison = $productComparison;

        return $this;
    }
}

<?php

namespace ProductComparison\Form;

use ProductComparison\ProductComparison;
use Thelia\Form\BaseForm;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Callback;
use Thelia\Model\ConfigQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Model\CategoryQuery;
use Thelia\Model\TemplateQuery;

/**
 * Class ProductComparisonCreateForm
 * @package ProductComparison\Form\Base
 * @author TheliaStudio
 */
class ProductComparisonConfigurationForm extends BaseForm
{
    const FORM_NAME = "product_comparison_configuration";

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'category_authorized',
                'choice',
                [
                    "constraints" => [
                        new NotBlank()
                    ],
                    "label" => $this->translator->trans('Category authorized', [], ProductComparison::MESSAGE_DOMAIN),
                    "label_attr" => [
                        "for" => 'category_authorized',
                        "help" => $this->translator->trans("List of category authorized", [], ProductComparison::MESSAGE_DOMAIN)
                    ],
                    'attr' => [
                        'size' => 10
                    ],
                    "required" => false,
                    "multiple" => true,
                    'data' => $this->getDataCategoryAuthorized(),
                    'choices' => $this->getCategoryList(),
                ]
            )
            ->add(
                'template_authorized',
                'choice',
                [
                    "constraints" => [
                        new NotBlank()
                    ],
                    "label" => $this->translator->trans('Template authorized', [], ProductComparison::MESSAGE_DOMAIN),
                    "label_attr" => [
                        "for" => 'template_authorized',
                        "help" => $this->translator->trans("List of template authorized", [], ProductComparison::MESSAGE_DOMAIN)
                    ],
                    'attr' => [
                        'size' => 10
                    ],
                    "required" => false,
                    "multiple" => true,
                    'data' => $this->getDataTemplateAuthorized(),
                    'choices' => $this->getTemplateList(),
                ]
            );
    }

    private function getDataCategoryAuthorized()
    {
        $dataValue = array();
        if(ProductComparison::getConfigValue('category_authorized'))
        {
            $dataValue = unserialize(ProductComparison::getConfigValue('category_authorized'));
        }
        return $dataValue;
    }

    private function getCategoryList()
    {
        $categoryList = array();
        $search = CategoryQuery::create()->rightJoinCategoryI18n()->find();
        foreach($search as $value)
        {
            $categoryId = $value->getId();
            $categoryTilte = $value->getTitle();
            $categoryList[$categoryId] = $categoryTilte;
        }
        return $categoryList;
    }

    private function getDataTemplateAuthorized()
    {
        $dataValue = array();
        if(ProductComparison::getConfigValue('template_authorized'))
        {
            $dataValue = unserialize(ProductComparison::getConfigValue('template_authorized'));
        }
        return $dataValue;
    }

    private function getTemplateList()
    {
        $templateList = array();
        $search = TemplateQuery::create()->rightJoinTemplateI18n()->find();
        foreach($search as $value)
        {
            $templateId = $value->getId();
            $templateName = $value->getName();
            $templateList[$templateId] = $templateName;
        }
        return $templateList;
    }

    public function getName()
    {
        return static::FORM_NAME;
    }

}

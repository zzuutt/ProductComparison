<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace ProductComparison\Controller\Base;

use ProductComparison\ProductComparison;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\AbstractCrudController;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Tools\URL;
use ProductComparison\Event\ProductComparisonEvent;
use ProductComparison\Event\ProductComparisonEvents;
use ProductComparison\Model\ProductComparisonQuery;
use Thelia\Core\Event\UpdatePositionEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductComparisonController
 * @package ProductComparison\Controller\Base
 * @author TheliaStudio
 */
class ProductComparisonController extends AbstractCrudController
{
    public function __construct()
    {
        parent::__construct(
            "product_comparison",
            "manual",
            "order",
            AdminResources::MODULE,
            ProductComparisonEvents::CREATE,
            ProductComparisonEvents::UPDATE,
            ProductComparisonEvents::DELETE,
            null,
            ProductComparisonEvents::UPDATE_POSITION,
            "ProductComparison"
        );
    }

    /**
     * Return the creation form for this object
     */
    protected function getCreationForm()
    {
        return $this->createForm("product_comparison.create");
    }

    /**
     * Return the update form for this object
     */
    protected function getUpdateForm($data = array())
    {
        if (!is_array($data)) {
            $data = array();
        }

        return $this->createForm("product_comparison.update", "form", $data);
    }

    /**
     * Hydrate the update form for this object, before passing it to the update template
     *
     * @param mixed $object
     */
    protected function hydrateObjectForm($object)
    {
        $data = array(
            "id" => $object->getId(),
            "feature_id" => $object->getFeatureId(),
            "template_id" => $object->getTemplateId(),
            "position" => $object->getPosition(),
        );

        return $this->getUpdateForm($data);
    }

    /**
     * Creates the creation event with the provided form data
     *
     * @param mixed $formData
     * @return \Thelia\Core\Event\ActionEvent
     */
    protected function getCreationEvent($formData)
    {
        $event = new ProductComparisonEvent();

        $event->setFeatureId($formData["feature_id"]);
        $event->setTemplateId($formData["template_id"]);

        return $event;
    }

    /**
     * Creates the update event with the provided form data
     *
     * @param mixed $formData
     * @return \Thelia\Core\Event\ActionEvent
     */
    protected function getUpdateEvent($formData)
    {
        $event = new ProductComparisonEvent();

        $event->setId($formData["id"]);
        $event->setFeatureId($formData["feature_id"]);
        $event->setTemplateId($formData["template_id"]);

        return $event;
    }

    /**
     * Creates the delete event with the provided form data
     */
    protected function getDeleteEvent()
    {
        $event = new ProductComparisonEvent();

        $event->setId($this->getRequest()->request->get("product_comparison_id"));

        return $event;
    }

    /**
     * Return true if the event contains the object, e.g. the action has updated the object in the event.
     *
     * @param mixed $event
     */
    protected function eventContainsObject($event)
    {
        return null !== $this->getObjectFromEvent($event);
    }

    /**
     * Get the created object from an event.
     *
     * @param mixed $event
     */
    protected function getObjectFromEvent($event)
    {
        return $event->getProductComparison();
    }

    /**
     * Load an existing object from the database
     */
    protected function getExistingObject()
    {
        return ProductComparisonQuery::create()
            ->findPk($this->getRequest()->query->get("product_comparison_id"))
        ;
    }

    /**
     * Returns the object label form the object event (name, title, etc.)
     *
     * @param mixed $object
     */
    protected function getObjectLabel($object)
    {
        return '';
    }

    /**
     * Returns the object ID from the object
     *
     * @param mixed $object
     */
    protected function getObjectId($object)
    {
        return $object->getId();
    }

    /**
     * Render the main list template
     *
     * @param mixed $currentOrder , if any, null otherwise.
     */
    protected function renderListTemplate($currentOrder)
    {
        $this->getParser()
            ->assign("order", $currentOrder)
        ;

        if(null != $template = $this->getRequest()->get('template')){
            $this->getParser()
                ->assign("template", $template)
            ;

        }


        return $this->render("product-comparisons");
    }

    /**
     * Render the edition template
     */
    protected function renderEditionTemplate()
    {
        $this->getParserContext()
            ->set(
                "product_comparison_id",
                $this->getRequest()->query->get("product_comparison_id")
            )
        ;

        return $this->render("product-comparison-edit");
    }

    /**
     * Must return a RedirectResponse instance
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToEditionTemplate()
    {
        $id = $this->getRequest()->query->get("product_comparison_id");

        return new RedirectResponse(
            URL::getInstance()->absoluteUrl(
                "/admin/module/ProductComparison/product_comparison/edit",
                [
                    "product_comparison_id" => $id,
                ]
            )
        );
    }

    /**
     * Must return a RedirectResponse instance
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToListTemplate()
    {
        $param = [];
        if(null != $template = $this->getRequest()->get('template')){
            $param = ["template"=>$template];

        }
        return new RedirectResponse(
            URL::getInstance()->absoluteUrl("/admin/module/ProductComparison/product_comparison",
            $param
            )
        );
    }
    
    protected function createUpdatePositionEvent($positionChangeMode, $positionValue)
    {
        return new UpdatePositionEvent(
            $this->getRequest()->query->get("product_comparison_id"),
            $positionChangeMode,
            $positionValue
        );
    }

    public function updateAttributePositionAction()
    {
        // Find attribute_template
        $attributeTemplate = ProductComparisonQuery::create()
            ->filterByTemplateId($this->getRequest()->get('template_id', null))
            ->filterById($this->getRequest()->get('product_comparison_id', null))
            ->findOne()
        ;

        return $this->genericUpdatePositionAction(
            $attributeTemplate,
            ProductComparisonEvents::UPDATE_POSITION
            //TheliaEvents::TEMPLATE_CHANGE_ATTRIBUTE_POSITION
        );
    }
}

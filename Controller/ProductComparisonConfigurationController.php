<?php

namespace ProductComparison\Controller;

use ProductComparison\ProductComparison;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Log\Tlog;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\AccessManager;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Tools\URL;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Class ProductRegistration
 * @package ProductRegistration
 * @author zzuutt <zzuutt34@free.fr>
 */
class ProductComparisonConfigurationController extends BaseAdminController
{
    /**
     * Configuration fields to save directly.
     * @var array
     */
    protected $fieldsToSave = [
        'category_authorized',
        'template_authorized',
    ];

    /**
     * Save the module configuration.
     * @return Response
     */
    public function saveAction()
    {
        $authResponse = $this->checkAuth(AdminResources::MODULE, ProductComparison::getModuleCode(), AccessManager::UPDATE);
        if (null !== $authResponse) {
            return $authResponse;
        }

        $baseForm = $this->createForm('product_comparison.configuration');
        try {
            $form = $this->validateForm($baseForm, 'POST');

            foreach ($this->fieldsToSave as $field) {
                ProductComparison::setConfigValue($field, serialize($form->get($field)->getData()));
            }

            if ($this->getRequest()->get('save_mode') === 'close') {
                return new RedirectResponse(URL::getInstance()->absoluteUrl(
                    'admin/module/ProductComparison/product_comparison'
                ));
            } else {
                return new RedirectResponse(URL::getInstance()->absoluteUrl(
                    'admin/module/ProductComparison/product_comparison',
                    [
                        'current_tab' => 'config'
                    ]
                ));
            }
        } catch (FormValidationException $ex) {
            $message = $this->createStandardFormValidationErrorMessage($ex);
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        $this->setupFormErrorContext(
            $this->getTranslator()->trans("Product Comparison configuration", [], 'productcomparison'),
            $message,
            $baseForm,
            $ex
        );

        return $this->generateRedirect(URL::getInstance()->absoluteUrl('/admin/module/ProductComparison/product_comparison'));

    }

    public function updateFeatureAction()
    {
        // only ajax
        $this->checkXmlHttpRequest();

        $request = $this->getRequest();

        $param = $request->get('template_id');

        return $this->render(
            "ajax/list_feature_not_in",
            [
                'template_id' => $param,
            ]
        );

    }
}
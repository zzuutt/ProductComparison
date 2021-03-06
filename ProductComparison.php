<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace ProductComparison;

use Thelia\Module\BaseModule;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;
use Thelia\Model\ConfigQuery;
use Thelia\Core\Template\TemplateDefinition;

/**
 * Class ProductComparison
 * @package ProductComparison
 */
class ProductComparison extends BaseModule
{
    const MESSAGE_DOMAIN = "productcomparison";
    const ROUTER = "router.productcomparison";


    public function postActivation(ConnectionInterface $con = null)
    {
        // Setup some default values
        $this->setConfigValue('name_cookie','thelia_comparator');
        $this->setConfigValue('template_authorized','');
        $this->setConfigValue('category_authorized','');
    }

    public function preActivation(ConnectionInterface $con = null)
    {
        if (! $this->getConfigValue('is_initialized', false)) {
            $database = new Database($con);

            $database->insertSql(null, [__DIR__ . "/Config/create.sql", __DIR__ . "/Config/insert.sql"]);

            $this->setConfigValue('is_initialized', true);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function destroy(ConnectionInterface $con = null, $deleteModuleData = false)
    {
        // If we have to delete module data, remove the media directory.
        if ($deleteModuleData) {
            $database = new Database($con);

            $database->insertSql(null, array(__DIR__ . '/Config/destroy.sql'));
        }
    }

    public function getHooks()
    {
        return [
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "comparator.bottom",
                "title" => [
                    "en_US" => "on the bottom comparator page",
                    "fr_FR" => "en bas de la page comparateur",
                ],
                "block" => false,
                "active" => true,
            ]
            ,
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "comparator.stylesheet",
                "title" => [
                    "en_US" => "comparator stylesheet",
                    "fr_FR" => "comparateur feuille de style",
                ],
                "block" => false,
                "active" => true,
            ]
            ,
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "comparator.after-javascript-include",
                "title" => [
                    "en_US" => "javascript on the comparator page",
                    "fr_FR" => "javascript sur la page comparateur",
                ],
                "block" => false,
                "active" => true,
            ]
            ,
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "comparator.javascript-initialization",
                "title" => [
                    "en_US" => "javascript on the comparator page",
                    "fr_FR" => "javascript sur la page comparateur",
                ],
                "block" => false,
                "active" => true,
            ]
            ,
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "comparator.top",
                "title" => [
                    "en_US" => "on the top comparator page",
                    "fr_FR" => "en haut de la page comparateur",
                ],
                "block" => false,
                "active" => true,
            ]
        ];
    }

}

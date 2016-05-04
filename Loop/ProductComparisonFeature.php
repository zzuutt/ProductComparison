<?php

namespace ProductComparison\Loop;

use ProductComparison\Model\ProductComparisonQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseI18nLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Type\BooleanOrBothType;
use Thelia\Model\FeatureQuery;
use Thelia\Model\FeatureI18nQuery;
use Thelia\Model\TemplateQuery;
use Thelia\Model\Map\FeatureTemplateTableMap;

/**
 * Class ProductComparison
 * @package ProductComparison\Loop
 * @author TheliaStudio
 */
class ProductComparisonFeature extends BaseI18nLoop implements PropelSearchLoopInterface
{
    protected $useFeaturePosition;
    protected $timestampable = true;

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var \ProductComparison\Model\ProductComparison $entry */
        foreach ($loopResult->getResultDataCollection() as $entry) {
            $row = new LoopResultRow($entry);

            $row
                ->set("ID", $entry->getId())
                ->set("IS_TRANSLATED", $entry->getVirtualColumn('IS_TRANSLATED'))
                ->set("LOCALE", $this->locale)
                ->set("TITLE", $entry->getVirtualColumn('i18n_TITLE'))
                ->set("POSITION", $this->useFeaturePosition ? $entry->getPosition() : $entry->getVirtualColumn('position'))
            ;

            $this->addMoreResults($row, $entry);

            $loopResult->addRow($row);
        }

        return $loopResult;
    }

    /**
     * Definition of loop arguments
     *
     * example :
     *
     * public function getArgDefinitions()
     * {
     *  return new ArgumentCollection(
     *
     *       Argument::createIntListTypeArgument('id'),
     *           new Argument(
     *           'ref',
     *           new TypeCollection(
     *               new Type\AlphaNumStringListType()
     *           )
     *       ),
     *       Argument::createIntListTypeArgument('category'),
     *       Argument::createBooleanTypeArgument('new'),
     *       ...
     *   );
     * }
     *
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument("id"),
            Argument::createIntListTypeArgument('template'),
            Argument::createIntListTypeArgument('exclude_template'),
            Argument::createEnumListTypeArgument(
                "order",
                [
                    "id",
                    "id-reverse",
                    "manual",
                    "manual-reverse",
                ],
                "manual"
            )
        );
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $query = new FeatureQuery();
        $this->configureI18nProcessing($query);

        $template = $this->getTemplate();
        if (! empty($template)) {
            // Join with feature_template table to get position
            $query
                ->withColumn(FeatureTemplateTableMap::POSITION, 'position')
                ->filterByTemplate(TemplateQuery::create()->findById($template), Criteria::IN)
            ;

            $this->useFeaturePosition = false;
        }

        if (null !== $id = $this->getId()) {
            $query->filterById($id);
        }

        $exclude_template = $this->getExcludeTemplate();
        if (null !== $exclude_template) {
            $exclude_features = ProductComparisonQuery::create()->filterByTemplateId($exclude_template)->select('feature_id')->find();

            $query
                ->filterById($exclude_features, Criteria::NOT_IN)
            ;
        }

        foreach ($this->getOrder() as $order) {
            switch ($order) {
                case "id":
                    $query->orderById();
                    break;
                case "id-reverse":
                    $query->orderById(Criteria::DESC);
                    break;
                case "manual":
                    if ($this->useFeaturePosition) {
                        $query->orderByPosition(Criteria::ASC);
                    } else {
                        $query->addAscendingOrderByColumn(FeatureTemplateTableMap::POSITION);
                    }
                    break;
                case "manual-reverse":
                    if ($this->useFeaturePosition) {
                        $query->orderByPosition(Criteria::DESC);
                    } else {
                        $query->addDescendingOrderByColumn(FeatureTemplateTableMap::POSITION);
                    }
                    break;
            }
        }

        return $query;
    }

    protected function addMoreResults(LoopResultRow $row, $entryObject)
    {
    }
}

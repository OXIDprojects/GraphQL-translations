<?php


namespace OxidEsales\GraphQl\Sample\Tests\Acceptance;


use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\EshopCommunity\Internal\Common\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQl\Sample\Dao\CategoryDaoInterface;
use OxidEsales\GraphQl\Utility\LegacyWrapperInterface;

trait CategorySetupTrait
{
    /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    protected $queryBuilderFactory;

    protected $rootIdS1;

    protected $subId1S1;

    protected $subId2S1;

    protected $rootIdS2;

    protected $subId1S2;

    protected $subId2S2;

    public function setUp()
    {
        parent::setUp();

        $this->queryBuilderFactory = $this->container->get(QueryBuilderFactoryInterface::class);

        $this->createSecondShop();

        /** @var CategoryDaoInterface $categoryDao */
        $categoryDao = $this->container->get(CategoryDaoInterface::class);
        $this->rootIdS1 = $categoryDao->addCategory(["Rootkategorie 1", "rootcategory 1"], 1);
        $this->subId1S1 = $categoryDao->addCategory(["Unterkategorie 1-1", "subcategory 1-1"], 1, $this->rootIdS1);
        $this->subId2S1 = $categoryDao->addCategory(["Unterkategorie 1-2", "subcategory 1-2"], 1, $this->rootIdS1);

        $this->rootIdS2 = $categoryDao->addCategory(["Rootkategorie 2", "rootcategory 2"], 2);
        $this->subId1S2 = $categoryDao->addCategory(["Unterkategorie 2-1", "subcategory 2-1"], 2, $this->rootIdS1);
        $this->subId2S2 = $categoryDao->addCategory(["Unterkategorie 2-2", "subcategory 2-2"], 2, $this->rootIdS1);

        /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    }

    public function tearDown()
    {

        foreach ([$this->rootIdS1, $this->subId1S1, $this->subId1S2, $this->rootIdS2, $this->subId2S1, $this->subId2S2] as $id) {
            $queryBuilder = $this->queryBuilderFactory->create();
            $queryBuilder->delete('oxcategories')->where($queryBuilder->expr()->eq('OXID', "'$id'"))->execute();
        };
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->delete('oxshops')->where($queryBuilder->expr()->eq('OXID', 2))->execute();
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->delete('oxconfig')->where($queryBuilder->expr()->eq('OXSHOPID', 2))->execute();

        parent::tearDown();

    }

}
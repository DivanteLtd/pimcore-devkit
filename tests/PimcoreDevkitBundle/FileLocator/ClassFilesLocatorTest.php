<?php
/**
 * @category    Pimcore DevKit
 * @date        17/06/2019
 * @author      Michał Bolka <mbolka@divante.pl>
 * @copyright   Copyright (c) 2019 Divante Ltd. (https://divante.co)
 */

namespace Tests\PimcoreDevkitBundle\FileLocator\ClassFilesLocatorTest;

use PimcoreDevkitBundle\FileLocator\PimcoreBundlesFilesLocator;
use PimcoreDevkitBundle\FileLocator\PimcoreClassLocator;
use PHPUnit\Framework\MockObject\MockObject;
use Pimcore\Extension\Bundle\PimcoreBundleManager;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class ClassFilesLocatorTest
 * @package Tests\PimcoreDevkitBundle\FileLocator\ClassFilesLocatorTest
 */
class ClassFilesLocatorTest extends WebTestCase
{
    /**
     * @param array $listOfBundles
     * @param array $listOfPimcoreClassFiles
     * @return PimcoreBundlesFilesLocator
     */
    private function createSuit(array $listOfBundles = [], $listOfPimcoreClassFiles = []): PimcoreBundlesFilesLocator
    {
        $stub = $this->createMock(PimcoreBundleManager::class);
        $stub->method('getEnabledBundleNames')
            ->willReturn($listOfBundles);
        $iteratorData = new \stdClass();
        $iteratorData->array = $listOfPimcoreClassFiles;
        $iteratorData->position = 0;

        $finder = $this->mockFinder($listOfPimcoreClassFiles);
        return new PimcoreClassLocator($stub, $finder);
    }

    /**
     * @param $data
     * @return MockObject|Finder
     */
    private function mockFinder($data)
    {
        $iteratorData = new \stdClass();
        $iteratorData->array = $data;
        $iteratorData->position = 0;
        $finderMock = $this->getMockBuilder(Finder::class)
            ->setMethods(['create', 'in', 'hasResults', 'getIterator'])
            ->getMock();
        $finderMock->method('create')
            ->willReturn($finderMock);
        $finderMock->method('in')->willReturn(null);
        $finderMock->method('hasResults')->willReturn(true);
        $iteratorMock = $this->createMock(\AppendIterator::class);
        $iteratorMock->expects($this->any())
            ->method('current')
            ->will(
                $this->returnCallback(
                    function() use ($iteratorData) {
                        return $iteratorData->array[$iteratorData->position];
                    }
                )
            );

        $iteratorMock->expects($this->any())
            ->method('key')
            ->will(
                $this->returnCallback(
                    function() use ($iteratorData) {
                        return $iteratorData->position;
                    }
                )
            );

        $iteratorMock->expects($this->any())
            ->method('next')
            ->will(
                $this->returnCallback(
                    function() use ($iteratorData) {
                        $iteratorData->position++;
                    }
                )
            );

        $iteratorMock->expects($this->any())
            ->method('valid')
            ->will(
                $this->returnCallback(
                    function() use ($iteratorData) {
                        return isset($iteratorData->array[$iteratorData->position]);
                    }
                )
            );
        $finderMock->method('getIterator')->willReturn($iteratorMock);
        return $finderMock;
    }

    /**
     * @param $name
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    protected static function getMethod($name)
    {
        $class = new ReflectionClass(PimcoreBundlesFilesLocator::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * @covers \PimcoreDevkitBundle\FileLocator\PimcoreBundlesFilesLocator::getBundleCatalogList
     * @return void
     * @throws \ReflectionException
     */
    public function testGetBundleCatalogListWithSingleCustomBundle()
    {
        $data = ['TestBundle\\TestBundle'];
        $expected = ['AppBundle', 'TestBundle'];
        $method = self::getMethod('getBundleCatalogList');
        $obj = $this->createSuit($data);
        $this->assertEquals($expected, $method->invokeArgs($obj, []));
    }

    /**
     * @covers \PimcoreDevkitBundle\FileLocator\PimcoreBundlesFilesLocator::getBundleCatalogList
     * @return void
     * @throws \ReflectionException
     */
    public function testGetBundleListWithoutCustomBundle()
    {
        $data = [];
        $expected = ['AppBundle'];
        $method = self::getMethod('getBundleCatalogList');
        $obj = $this->createSuit($data);
        $this->assertEquals($expected, $method->invokeArgs($obj, []));
    }

    /**
     * @covers \PimcoreDevkitBundle\FileLocator\PimcoreBundlesFilesLocator::getClassName
     * @return void
     * @throws \ReflectionException
     */
    public function testGetClassNameFromClassFile()
    {
        $data = 'class_Product_export.json';
        $expected = 'Product';
        $method = self::getMethod('getClassName');
        $obj = $this->createSuit();
        $this->assertEquals($expected, $method->invokeArgs($obj, [$data]));
    }

    /**
     * @covers \PimcoreDevkitBundle\FileLocator\PimcoreBundlesFilesLocator::getClassName
     */
    public function testGetClassNameFromNotJsonFile()
    {
        $data = 'index.php';
        $method = self::getMethod('getClassName');
        $obj = $this->createSuit();
        $this->assertNull($method->invokeArgs($obj, [$data]));
    }

    /**
     * @covers \PimcoreDevkitBundle\FileLocator\PimcoreBundlesFilesLocator::getClassName
     * @return void
     * @throws \ReflectionException
     */
    public function testGetClassNameFromNotClassFile()
    {
        $data = 'class_test.json';
        $method = self::getMethod('getClassName');
        $obj = $this->createSuit();
        $this->assertNull($method->invokeArgs($obj, [$data]));
    }

    /**
     * @covers \PimcoreDevkitBundle\FileLocator\PimcoreBundlesFilesLocator::getClassFiles
     * @return void
     * @throws \ReflectionException
     */
    public function testGetClassFileArrayFromBundles()
    {
        $data = new SplFileInfo(
            __DIR__ . '/../data/classes/class_Product_export.json',
            __DIR__ . '/../data/classes/',
            'class_Product_export.json'
        );
        $obj = $this->createSuit([], [$data]);
        $method = self::getMethod('getPimcoreFiles');
        $result = $method->invokeArgs($obj, ['AppBundle']);
        $this->assertArrayHasKey('Product', $result);
    }
}

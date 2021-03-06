<?php

namespace Vlabs\MediaBundle\Tests\Filter;

use Vlabs\MediaBundle\Entity\BaseFileInterface;
use Vlabs\MediaBundle\Filter\ImageResizeFilter;

class ImageResizeFilterTest extends \PHPUnit_Framework_TestCase
{
    private $cacheDir;

    public function setUp()
    {
        $this->cacheDir = __DIR__.'/../Fixtures/cache/';
    }

    public function getDataBag()
    {
        return array(
            array(
                $this->getBaseFileMock(),
                'resize',
                array ('width' => 200, 'height' => 300, 'upscale' => true, 'keepRatio' => true)
            ),
            array(
                $this->getBaseFileMock(),
                'resize',
                array ('width' => 1000, 'height' => 900, 'keepRatio' => true)
            ),
            array(
                $this->getBaseFileMock(),
                'resize',
                array ('width' => 900, 'height' => 900, 'keepRatio' => false)
            ),
            array(
                $this->getBaseFileMock(),
                'resize',
                array ('width' => 300)
            ),
            array(
                $this->getBaseFileMock(),
                'resize',
                array ('height' => 300)
            ),
        );
    }

    public function getErrorDataBag()
    {
        return array(
            array(
                $this->getBaseFileMock(),
                'resize',
                array ('upscale' => true, 'keepRatio' => true)
            ),
        );
    }

    protected function getBaseFileMock()
    {
        $mock = $this->getMockBuilder('Vlabs\MediaBundle\Entity\BaseFileInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('image.jpg'))
        ;

        return $mock;
    }

    protected static function getMethod($name)
    {
        $class = new \ReflectionClass('Vlabs\MediaBundle\Filter\ImageResizeFilter');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    private function rrmdir($dir)
    {
        foreach(glob($dir . '/*') as $file)
        {
            if(is_dir($file)) {
                $this->rrmdir($file);
            }
            else {
                unlink($file);
            }
        }

        rmdir($dir);
    }

    /**
     * @dataProvider getDataBag
     */
    public function testImageIsResized(BaseFileInterface $file, $alias, array $options)
    {
        $filter = new ImageResizeFilter($this->cacheDir);
        $filter->setAlias($alias);
        $filter->handle($file, __DIR__.'/../Fixtures/image.jpg',$options);

        $method = self::getMethod('doFilter');
        $method->invokeArgs($filter, array($file, __DIR__.'/../Fixtures/image.jpg', $options));

        $this->assertTrue(is_file($filter->getCachePath()));

        $this->rrmdir(sprintf('%s%s', $this->cacheDir, $alias));
    }

    /**
     * @dataProvider getErrorDataBag
     * @expectedException \InvalidArgumentException
     */
    public function testFilterException(BaseFileInterface $file, $alias, array $options)
    {
        $filter = new ImageResizeFilter($this->cacheDir);
        $filter->setAlias($alias);
        $filter->handle($file, __DIR__.'/../Fixtures/image.jpg',$options);

        $method = self::getMethod('doFilter');
        $method->invokeArgs($filter, array($file, __DIR__.'/../Fixtures/image.jpg', $options));

        $this->rrmdir(sprintf('%s%s', $this->cacheDir, $alias));
    }
}
<?php

namespace WidgetTest;

class MonologTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists('\Monolog\Logger')) {
            $this->markTestSkipped('The monolog/monolog is not loaded');
            return;
        }
        
        parent::setUp();
    }
    
    public function testInvoker()
    {
        $this->assertInstanceOf('\Monolog\Logger', $this->monolog());
    }
    
    public function testCustomHandler()
    {
        $monologWidget = new \Widget\Monolog(array(
            'handlers' => array(
                new \Monolog\Handler\StreamHandler('php://stderr')
            )
        ));
        
        $monolog = $monologWidget();
        
        $this->assertInstanceOf('\Monolog\Handler\StreamHandler', $monolog->popHandler());
    }
    
    /**
     * @expectedException \Widget\Exception\InvalidArgumentException
     */
    public function testInvalidArgumentException()
    {
        new \Widget\Monolog(array(
            'handlers' => array(
                new \stdClass
            )
        ));
    }
    
    public function testLog()
    {
        $handler = new \Monolog\Handler\TestHandler;
        $monologWidget = new \Widget\Monolog(array(
            'handlers' => array(
                $handler
            )
        ));
        
        $monologWidget(\Monolog\Logger::ALERT, 'alert message');
        
        $this->assertTrue($handler->hasAlert('alert message'));
    }

    /**
     * @dataProvider argsProvider
     */
    public function testCreateInstance($arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null)
    {
        /* @var $instance \WidgetTest\Fixtures\Instance */
        $instance = $this->monolog->createInstance('\WidgetTest\Fixtures\Instance', func_get_args());
        
        $this->assertEquals($arg1, $instance->arg1);
        $this->assertEquals($arg2, $instance->arg2);
        $this->assertEquals($arg3, $instance->arg3);
        $this->assertEquals($arg4, $instance->arg4);
    }
    
    public function testClassNotFound()
    {
        $this->assertFalse($this->monolog->createInstance('ClassNotFound'));
    }
    
    public function testClassWithoutConstructor()
    {
        $this->monolog->createInstance('\stdClass', array(1, 2, 3,4 ));
    }
    
    public function argsProvider()
    {
        return array(
            array(
                
            ),
            array(
                1
            ),
            array(
                1, 2
            ),
            array(
                1, 2, 3
            ),
            array(
                1, 2, 3, 4
            )
        );
    }
}
<?php
require_once dirname(__FILE__) . '/../../../libs/Qwin.php';

/**
 * Test class for Qwin_Router.
 * Generated by PHPUnit on 2012-02-23 at 14:49:29.
 */
class Qwin_RouterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Qwin_Router
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        Qwin::getInstance();

        $this->object = new Qwin_Router;

        $this->object->options['baseUri'] = '';
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }


    public function test__invoke() {
        $this->assertInstanceOf('Qwin_Router', $this->object->__invoke());
    }

    public function testAddRouteWithName()
    {
        $router = $this->object;

        $router->set(array(
            'name' => __FUNCTION__,
            'uri' => 'blog(/<page>)',
        ));

        $this->assertNotNull($router->get(__FUNCTION__));
    }

    public function testMatchStaticRoute()
    {
        $router = $this->object;

        $router->set(array(
            'uri' => 'user/login',
            'defaults' => array(
                'module' => 'user',
                'action' => 'login',
            ),
        ));

        $this->assertEquals($router->match('user/login?step=1'), array(
            'module' => 'user',
            'action' => 'login',
            'step' => '1',
        ));
    }

    public function testMatchRequiredRoute()
    {
        $router = $this->object;

        $router->set(array(
            'uri' => 'user/<action>',
            'defaults' => array(
                'module' => 'user',
            ),
        ));

        $this->assertEquals(array(
            'module' => 'user',
            'action' => 'login',
            'step' => '1',
        ), $router->match('user/login?step=1'));
    }

    public function testMatchOptionalRoute()
    {
        $router = $this->object;

        $router->set(array(
            'uri' => 'user(/<page>)',
            'defaults' => array(
                'module' => 'user',
                'page' => '1'
            ),
        ));

        $this->assertEquals(array(
            'module' => 'user',
            'page' => '1',
        ), $router->match('user'));

        $this->assertEquals(array(
            'module' => 'user',
            'page' => '2',
        ), $router->match('user/2'));
    }

    public function testMatchWithRequestMethod()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'uri' => 'postOrPut',
            'method' => 'POST|PUT',
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertEquals(array('matched' => true), $router->match('postOrPut'));

        $this->assertEquals(array('matched' => true), $router->match('postOrPut', 'POST'));

        $this->assertFalse($router->match('postOrPut', 'GET'));
    }

    public function testNotMatchUri()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'uri' => 'blog/(<page>)',
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertFalse($router->match('withoutThisRoute'));
    }

    public function testMatchUriWithRules()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'uri' => 'blog/<page>',
            'rules' => array(
                'page' => '\d+',
            ),
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertFalse($router->match('blog/notDigits'));

        $this->assertEquals(array(
            'matched' => true,
            'page' => 1,
        ), $router->match('blog/1'));
    }

    public function testMatchUriWhithSlashParams()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'uri' => 'blog/<page>',
            'slashSeparator' => true,
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertEquals(array(
            'matched' => true,
            'page' => 1,
            'var1' => 'value1',
            'var2' => 'value2',
        ), $router->match('blog/1/var1/value1/var2/value2'));
    }

    public function testMatchWithNameParameter()
    {
        $router = $this->object;

        $this->assertEquals(array(
            'module' => 'blog',
            'action' => 'list'
        ), $router->match('blog/list', null, 'default'));
    }

    public function testUriForStaticRule()
    {
        $router = $this->object;

        $router->set(array(
            'uri' => 'user/login',
            'defaults' => array(
                'module' => 'user',
                'action' => 'login',
            ),
        ));

        $this->assertEquals('user/login?var1=value1', $router->uri(array(
            'module' => 'user',
            'action' => 'login',
            'var1' => 'value1',
        )));
    }

    public function testUriForNotMatchStaticRule()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'uri' => 'user/login',
            'defaults' => array(
                'module' => 'user',
                'action' => 'login',
            ),
        ));

        $this->assertEquals('?module=user&amp;var1=value1', $router->uri(array(
            'module' => 'user',
            'var1' => 'value1',
        )));
    }

    public function testUriWithoutRule()
    {
        $router = $this->object;

        $router->remove('default');

        $this->assertEquals('?var1=value1&amp;var2=value2', $router->uri(array(
            'var1' => 'value1',
            'var2' => 'value2',
        )));
    }

    public function testUriForRequiredRule()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'uri' => 'blog/<page>',
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertEquals('blog/1', $router->uri(array(
            'matched' => true,
            'page' => 1,
        )));
    }

    public function testUriForRequiredRuleAndRequiredParameterNotPassed()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'uri' => 'blog/<page>',
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertEquals('?matched=1', $router->uri(array(
            'matched' => true,
        )));
    }

    public function testUriForRequiredRuleAndParameterNotMatchRule()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'uri' => 'blog/<page>',
            'rules' => array(
                'page' => '\d+',
            ),
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertEquals('?matched=1&amp;page=notDigits', $router->uri(array(
            'matched' => true,
            'page' => 'notDigits',
        )));
    }

    public function testUriForOptionalRule()
    {
        $router = $this->object;

        $router->set(array(
            'uri' => 'blog(/<page>)',
            'defaults' => array(
                'module' => 'blog',
                'page' => '1'
            ),
        ));

        $this->assertEquals('blog', $router->uri(array(
            'module' => 'blog',
        )));
    }

    public function testUriForOptionalRuleAndParameterNotMatchRule()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'uri' => 'blog(/<page>)',
            'rules' => array(
                'page' => '\d+',
            ),
            'defaults' => array(
                'module' => 'blog',
                'page' => '1'
            ),
        ));

        $this->assertEquals('?module=blog&amp;page=notDigits', $router->uri(array(
            'module' => 'blog',
            'page' => 'notDigits'
        )));
    }
    public function testUriWhithSlashParams()
    {
        $router = $this->object;

        $router->remove('default');

        $router->set(array(
            'uri' => 'blog/<page>',
            'slashSeparator' => true,
            'defaults' => array(
                'matched' => true
            ),
        ));

        $this->assertEquals('blog/1/var1/value1/var2/value2', $router->uri(array(
            'matched' => true,
            'page' => 1,
            'var1' => 'value1',
            'var2' => 'value2',
        )));
    }

    public function testUriWithNameParameter()
    {
        $router = $this->object;

        $router->set(array(
            'name' => 'blogList',
            'uri' => 'blog(/<page>)',
            'defaults' => array(
                'module' => 'blog',
                'page' => '1'
            ),
        ));

        $this->assertEquals('blog/2', $router->uri(array(
            'module' => 'blog',
            'page' => '2',
        ), 'blogList'));
    }

    public function testUriWhenRouterNotEnable()
    {
        $router = $this->object;

        $router->option('enable', false);

        $this->assertEquals('?module=index', $router->uri(array(
            'module' => 'index',
        )));
    }

    public function testSetBaseUriOption()
    {
        $router = $this->object;

        $router->option('baseUri', '/app');

        $this->assertEquals('/app/', $router->option('baseUri'));
    }

    public function testMatchRequestUriWhenRouterNotEnable()
    {
        $router = $this->object;

        $router->option('enable', false);

        $this->assertEquals($_GET, $router->matchRequestUri());
    }

    public function testMatchRequestUriInApache2()
    {
        $router = $this->object;

        !isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] = 'module/action/id';

        $this->assertContains('module', $router->matchRequestUri());
    }

    public function testMatchRequestUriInIis7()
    {
        $router = $this->object;

        if (isset($_SERVER['REQUEST_URI'])) {
            unset($_SERVER['REQUEST_URI']);
        }

        !isset($_SERVER['HTTP_X_ORIGINAL_URL']) && $_SERVER['HTTP_X_ORIGINAL_URL'] = 'module/action/id';

        $this->assertContains('module', $router->matchRequestUri());

        $this->assertContains('module', $router->matchRequestUri(), 'get again for cached variable');
    }

    public function testMatchRequestUriInIis6()
    {
        $router = $this->object;

        if (isset($_SERVER['REQUEST_URI'])) {
            unset($_SERVER['REQUEST_URI']);
        }

        if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {
            unset($_SERVER['HTTP_X_ORIGINAL_URL']);
        }

        !isset($_SERVER['HTTP_X_REWRITE_URL']) && $_SERVER['HTTP_X_REWRITE_URL'] = 'module/action/id';

        $this->assertContains('module', $router->matchRequestUri());
    }

    public function testMatchRequestUriInUnknownServer()
    {
        $router = $this->object;

        if (isset($_SERVER['REQUEST_URI'])) {
            unset($_SERVER['REQUEST_URI']);
        }

        if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {
            unset($_SERVER['HTTP_X_ORIGINAL_URL']);
        }

        if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
            unset($_SERVER['HTTP_X_REWRITE_URL']);
        }

        $this->assertEquals(array(
            'module' => 'index',
            'action' => 'index',
        ), $router->matchRequestUri());
    }
}
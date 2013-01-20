<?php

namespace WidgetTest;

class ValidatorTest extends TestCase
{
    public function testIsRegex()
    {
        $this->assertTrue($this->isRegex('This is Widget Framework.', '/widget/i'));

        $this->assertFalse($this->isRegex('This is Widget Framework.', '/that/i'));
    }

    public function testIsTime()
    {
        $this->assertTrue($this->isTime('00:00:00'));

        $this->assertTrue($this->isTime('00:00'));

        $this->assertTrue($this->isTime('23:59:59'));

        $this->assertFalse($this->isTime('24:00:00'));

        $this->assertFalse($this->isTime('23:60:00'));

        $this->assertFalse($this->isTime('23:59:61'));

        $this->assertFalse($this->isTime('61:00'));

        $this->assertFalse($this->isTime('01:01:01:01'));
    }

    public function testIsPostCode()
    {
        $this->assertTrue($this->isPostcode('123456'));

        $this->assertFalse($this->isPostcode('1234567'));

        $this->assertFalse($this->isPostcode('0234567'));
    }

    public function testIsFile()
    {
        $this->assertFalse(false, $this->isFile(array()), 'Not File path');

        $this->assertEquals($this->isFile(__FILE__), __FILE__, 'File found');

        $this->assertFalse($this->isFile('.file not found'), 'File not found');

        $paths = explode(PATH_SEPARATOR, ini_get('include_path'));
        $path = array_pop($paths);
        $files = scandir($path);
        foreach ($files as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            if (is_file($path . DIRECTORY_SEPARATOR . $file)) {
                $this->assertNotEquals(false, $this->isFile($file), 'File in include path found');
                break;
            }
        }
    }
    public function testIsDir()
    {
        $this->assertEquals(false, $this->isDir(array()), 'Not File path');

        $this->assertEquals($this->isDir(__DIR__), __DIR__, 'File found');

        $this->assertFalse($this->isDir('.file not found'), 'File not found');

        $paths = explode(PATH_SEPARATOR, ini_get('include_path'));
        $path = array_pop($paths);
        $files = scandir($path);
        foreach ($files as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {
                $this->assertNotEquals(false, $this->isDir($file), 'File in include path found');
                break;
            }
        }
    }

    public function testIsExists()
    {
        $this->assertEquals(false, $this->isExists(array()), 'Not File path');

        $this->assertEquals($this->isExists(__FILE__), __FILE__, 'File found');

        $this->assertFalse($this->isExists('.file not found'), 'File not found');

        $paths = explode(PATH_SEPARATOR, ini_get('include_path'));
        $path = array_pop($paths);
        $files = scandir($path);
        foreach ($files as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }
            if (file_exists($path . DIRECTORY_SEPARATOR . $file)) {
                $this->assertNotEquals(false, $this->isExists($file), 'File in include path found');
                break;
            }
        }
    }

    public function testIsDate()
    {
        $this->assertTrue($this->isDate('2013-01-13'));

        $this->assertTrue($this->isDate('1000-01-01'));

        $this->assertTrue($this->isDate('3000-01-01'));

        $this->assertTrue($this->isDate('2012-02-29'));

        $this->assertFalse($this->isDate('2013-02-29'));

        $this->assertFalse($this->isDate('2013-01-32'));

        $this->assertFalse($this->isDate('2013-00-00'));

        $this->assertFalse($this->isDate('2012'));
    }

    public function testIsDateTime()
    {
        $this->assertTrue($this->isDateTime('1000-01-01 00:00:00'));

        $this->assertTrue($this->isDateTime('3000-01-01 00:00:50'));

        $this->assertTrue($this->isDateTime('2012-02-29 23:59:59'));

        $this->assertFalse($this->isDateTime('2013-02-29 24:00:00'));

        $this->assertFalse($this->isDateTime('2013-01-32 23:60:00'));

        $this->assertFalse($this->isDateTime('2013-00-00 23:59:61'));

        $this->assertFalse($this->isDateTime('2012 61:00'));
    }

    public function testIsRange()
    {
        $this->assertTrue($this->isRange(20, array(
            'min' => 10,
            'max' => 30,
        )));

        $this->assertTrue($this->isRange('2013-01-13', array(
            'min' => '2013-01-01',
            'max' => '2013-01-31'
        )));

        $this->assertTrue($this->isRange(1.5, array(
            'min' => 0.8,
            'max' => 3.2
        )));

        $this->assertFalse($this->isRange(20, array(
            'min' => 30,
            'max' => 40
        )));
    }

    public function testIsIn()
    {
        $this->assertTrue($this->isIn('apple', array('apple', 'pear')));

        $this->assertTrue($this->isIn('apple', new \ArrayObject(array('apple', 'pear'))));

        $this->assertTrue($this->isIn('', array(null)));

        $this->assertFalse($this->isIn('', array(null), true));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIsInException()
    {
        $this->isIn('apple', 'not array');
    }

    public function testIsStartsWith()
    {
        $this->assertTrue($this->isStartsWith('abc', 'a'));

        $this->assertFalse($this->isStartsWith('abc', ''));

        $this->assertFalse($this->isStartsWith('abc', 'b'));

        $this->assertTrue($this->isStartsWith('ABC', 'A'));

        $this->assertFalse($this->isStartsWith('ABC', 'a', true));
    }

    public function testClosureAsParameter()
    {
        $this->assertTrue($this->is(function($data){
            return 'abc' === $data;
        }, 'abc'));

        $this->assertFalse($this->is(function(
            $data, \Widget\Validator\Rule\Callback $callback, \Widget\Widget $widget
        ){
            return false;
        }, 'data'));
    }


    public function testValidator()
    {
        $this->assertTrue($this->is('digit', '123'));

        $this->assertFalse($this->is('digit', 'abc'));

        $result = $this->validate(array(
            'data' => array(
                'email' => 'twinhuang@qq.com',
                'age' => '5',
            ),
            'rules' => array(
                'email' => array(
                    'email' => true
                ),
                'age' => array(
                    'digit' => true,
                    'range' => array(
                        'min' => 1,
                        'max' => 150
                    )
                ),
            ),
        ))->valid();

        $this->assertTrue($result);
    }

    public function testOptionalField()
    {
        $result = $this->validate(array(
            'data' => array(
                'email' => ''
            ),
            'rules' => array(
                'email' => array(
                    'required' => false,
                    'email' => true,
                )
            ),
        ))->valid();

        $this->assertTrue($result);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $this->is(new \stdClass());
    }

    /**
     * @expectedException \Widget\Exception
     */
    public function testRuleNotDefined()
    {
        $this->is('noThisRule', 'test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEmptyRuleException()
    {
        $this->validate(array(
            'rules' => array(),
        ));
    }

    public function testBreakOne()
    {
        $breakRule = '';

        $this->validate(array(
            'data' => array(
                'email' => 'error-email',
            ),
            'rules' => array(
                'email' => array(
                    'length' => array(
                        'min' => 1,
                        'max' => 3 
                    ), // invalid
                    'email' => true, // valid
                ),
            ),
            'breakOne' => true,
            'invalidatedOne' => function($field, $rule, $validator) use(&$breakRule) {
                $breakRule = $rule;
            }
        ));

        $this->assertEquals('length', $breakRule);
    }

    public function testReturnFalseInValidatedOneCallback()
    {
        $lastRule = '';

        $this->validate(array(
            'data' => array(
                'email' => 'twinhuang@qq.com',
            ),
            'rules' => array(
                'email' => array(
                    'required' => true, //Aavoid automatic added
                    'email' => true, // Will not validate
                ),
            ),
            'validatedOne' => function($field, $rule, $validator) use(&$lastRule) {
                $lastRule = $rule;

                // Return false to break the validation flow
                return false;
            }
        ));

        $this->assertEquals('required', $lastRule);
    }

    public function testReturnFalseInValidatedCallback()
    {
        $lastField = '';

        $this->validate(array(
            'data' => array(
                'email' => 'twinhuang@qq.com',
                'age' => 5
            ),
            'rules' => array(
                // Will validate
                'email' => array(
                    'email' => true,
                ),
                // Will not validate
                'age' => array(
                    'range' => array(0, 150)
                ),
            ),
            'validated' => function($field, $validator) use(&$lastField) {
                $lastField = $field;

                // Return false to break the validation flow
                return false;
            }
        ));

        $this->assertEquals('email', $lastField);
    }
    
    public function testIsOne()
    {
        $this->assertTrue($this->is(array(
            'email' => true,
            'endsWith' => array(
                'findMe' => 'example.com',
            ),
        ), 'example@example.com'));
    }
    
    public function testNotPrefix()
    {
        $this->assertTrue($this->is('notDigit', 'string'));
        
        $this->assertFalse($this->is('notDigit', '123'));
    }
}
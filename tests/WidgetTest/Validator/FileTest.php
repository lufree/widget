<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class FileTest extends TestCase
{
    public function createFileValidator()
    {
        return new \Widget\Validator\File(array(
            'widget' => $this->widget,
        ));
    }
    
    public function testIsFile()
    {
        $this->assertFalse($this->isFile(array()), 'Not File path');

        $this->assertTrue($this->isFile(__FILE__), 'File found');

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
    
    public function testNotFound()
    {
        $file = $this->createFileValidator();
        
        $this->assertFalse($file('/not_found_this_file'));
    }
    
    public function testExts()
    {
        $file = $this->createFileValidator();
        
        $this->assertFalse($file(__FILE__, array(
            'exts' => 'gif,jpg',
            'excludeExts' => array('doc', 'php')
        )));
        
        $this->assertEquals(array('excludeExts', 'exts'), array_keys($file->getErrors()));
    }
    
    public function testSize()
    {
        $file = $this->createFileValidator();
        
        $this->assertFalse($file(__FILE__, array(
            'maxSize' => 8, // 8bytes
            'minSize' => '10.5MB'
        )));
        
        $this->assertEquals(array('maxSize', 'minSize'), array_keys($file->getErrors()));
    }
    
    /**
     * @expectedException Widget\UnexpectedTypeException
     */
    public function testUnexpectedExts()
    {
        $file = $this->createFileValidator();
        
        $file->setExcludeExts(new \stdClass());
    }
}
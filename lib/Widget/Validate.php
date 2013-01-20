<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Validate
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 * @link        http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 */
class Validate extends WidgetProvider
{
    /**
     * The last validator object
     *
     * @var \Widget\Validator\Validator
     */
    protected $validator;
    
    public function __invoke(array $options)
    {
        $validator = $this->validator = $this->is->createValidator();
        
        $validator($options);
        
        return $validator;
    }
}
<?php
/**
 * Widget Framework
 *
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Widget\Cache\AbstractCache;

/**
 * A cache widget base on Couchbase
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link https://github.com/couchbase/php-ext-couchbase
 */
class Couchbase extends AbstractCache
{
    /**
     * The couchbase object
     *
     * @var \Couchbase
     */
    protected $object;
    
    
    
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->setOption($options);

        $this->object = new \Couchbase('127.0.0.1:8091', '', '', 'default');
        
        parent::__construct();
    }
    
    /**
     * {@inheritdoc}
     */
    public function __invoke($key, $value = null, $expire = 0)
    {
        if (1 == func_num_args()) {
            return $this->get($key);
        } else {
            return $this->set($key, $value, $expire);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return $this->object->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $expire = 0)
    {
        return $this->object->set($key, $value, $expire);
    }
    
    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return $this->object->delete($key);
    }
    
    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        if ($this->object->add($key, true)) {
            $this->object->delete($key);
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, $expire = 0)
    {
        return (bool)$this->object->add($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $expire = 0)
    {
        return (bool)$this->object->replace($key, $value, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function increment($key, $offset = 1)
    {
        if (is_numeric($this->object->get($key))) {
            return $this->object->increment($key, $offset);
        } else {
            return $this->object->set($key, $offset);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($key, $offset = 1)
    {
        return $this->object->decrement($key, $offset);
    }

    /**
     * {@inheritdoc}
     * 
     * @throws \CouchbaseServerException when flush is disabled for the bucket
     * @link http://www.couchbase.com/docs/couchbase-manual-2.0/couchbase-admin-web-console-data-buckets-createedit.html
     * @link http://www.couchbase.com/docs/couchbase-manual-2.0/couchbase-admin-cli-flushing.html
     */
    public function clear()
    {
        return $this->object->flush();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMulti(array $keys)
    {
        return $this->object->getMulti($keys);
    }
    
    /**
     * {@inheritdoc}
     */
    public function setMulti(array $items, $expire = 0)
    {
        $results = $this->object->setMulti($items, $expire);
        foreach ($results as &$result) {
            $result = (bool)$result;
        }
        return $results;
    }
    
    /**
     * Get couchbase object
     *
     * @return \Couchbase
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set couchbase object
     *
     * @param \Couchbase $object
     * @return Couchbase
     */
    public function setObject(\Couchbase $object)
    {
        $this->object = $object;

        return $this;
    }
}
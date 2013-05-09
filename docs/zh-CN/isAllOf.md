[isAllOf()](http://twinh.github.io/widget/api/isAllOf)
======================================================

检查数据是否通过所有的规则校验

### 
```php
bool isAllOf( $input [, $rules ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$rules** `array` 验证规则数组,数组的键名是规则名称,数组的值是验证规则的配置选项

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `atLeast`             | %name%必须满足以下所有规则                                     |

##### 代码范例
检查数据是否为5-10位的数字
```php
<?php

$input = '123456';
if ($widget->isAllOf($input, array(
	'length' => array(5, 10),
	'digit' => true
))) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
[isMin()](http://twinh.github.io/widget/api/isMin)
==================================================

检查数据是否大于等于指定的值

### 
```php
bool isMin( $input [, $min ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$min** `int` 待比较的数值

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `min`                 | %name%必须大于等于%min%                                        |
| `notString`           | %name%必须是字符串                                             |
| `negative`            | %name%不合法                                                   |

##### 代码范例
检查10是否大于等于20
```php
<?php
 
if ($widget->isMin(10, 20)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
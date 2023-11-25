# wd_checksum
checksum

#### 开始
1. composer安装
```bash
composer require jdd/wd_checksum:@dev
```
2. 引用

```php

require __DIR__ . '/vendor/autoload.php';#这个autoload.php是这个包内生成的那个

use Jdd\Checksum;

echo  Checksum::get_email_checksum('1');
```
3. 更新

你可以执行 `composer update jdd/wd_checksum` 命令来只更新 `jdd/wd_checksum` 包

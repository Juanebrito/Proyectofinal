```php
require __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('channel-name');
$logger->pushHandler(new StreamHandler(__DIR__.'/app.log', Logger::DEBUG));
$logger->info('This is a log! ^_^ ');
$logger->warning('This is a log warning! ^_^ ');
$logger->error('This is a log error! ^_^ ');
```
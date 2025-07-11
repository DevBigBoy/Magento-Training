# Magento 2 Logging Guide: File, Console & Mailer Loggers

## Table of Contents
1. [Introduction to Magento 2 Logging](#introduction)
2. [Understanding Magento 2 Logger Architecture](#architecture)
3. [File Loggers](#file-loggers)
4. [Console Loggers](#console-loggers)
5. [Mailer Loggers](#mailer-loggers)
6. [Advanced Configuration](#advanced-configuration)
7. [Best Practices](#best-practices)
8. [Troubleshooting](#troubleshooting)

## Introduction to Magento 2 Logging {#introduction}

Magento 2 uses the Monolog library for logging, which provides a flexible and powerful logging system. The framework supports multiple logging handlers that can write logs to different destinations: files, console output, email, databases, and more.

### Key Benefits
- **Debugging**: Track application flow and identify issues
- **Monitoring**: Monitor system performance and user behavior
- **Auditing**: Keep records of important system events
- **Troubleshooting**: Diagnose problems in production environments

## Understanding Magento 2 Logger Architecture {#architecture}

### Core Components

**Logger**: The main interface for writing log messages
**Handler**: Determines where log messages are written (file, console, email)
**Formatter**: Defines the format of log messages
**Processor**: Adds additional context to log messages

### Log Levels
Magento 2 supports standard PSR-3 log levels:
- `EMERGENCY` (600): System unusable
- `ALERT` (550): Action must be taken immediately
- `CRITICAL` (500): Critical conditions
- `ERROR` (400): Error conditions
- `WARNING` (300): Warning conditions
- `NOTICE` (250): Normal but significant condition
- `INFO` (200): Informational messages
- `DEBUG` (100): Debug-level messages

## File Loggers {#file-loggers}

File logging is the most common type of logging in Magento 2. By default, logs are written to `var/log/` directory.

### Basic File Logger Implementation

#### Step 1: Create a Custom Module

Create the module structure:
```
app/code/YourCompany/YourModule/
├── etc/
│   ├── module.xml
│   └── di.xml
├── Model/
│   └── Logger/
│       ├── Handler.php
│       └── Logger.php
└── registration.php
```

#### Step 2: Module Registration

**registration.php**
```php
<?php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'YourCompany_YourModule',
    __DIR__
);
```

#### Step 3: Module Declaration

**etc/module.xml**
```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="YourCompany_YourModule" setup_version="1.0.0"/>
</config>
```

#### Step 4: Create Custom Log Handler

**Model/Logger/Handler.php**
```php
<?php
namespace YourCompany\YourModule\Model\Logger;

use Magento\Framework\Logger\Handler\Base as BaseHandler;
use Monolog\Logger as MonologLogger;

class Handler extends BaseHandler
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = MonologLogger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/custom_module.log';
}
```

#### Step 5: Create Custom Logger

**Model/Logger/Logger.php**
```php
<?php
namespace YourCompany\YourModule\Model\Logger;

class Logger extends \Monolog\Logger
{
    // Logger class automatically inherits all Monolog functionality
}
```

#### Step 6: Dependency Injection Configuration

**etc/di.xml**
```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    
    <!-- Custom Logger Handler -->
    <type name="YourCompany\YourModule\Model\Logger\Handler">
        <arguments>
            <argument name="filesystem" xsi:type="object">Magento\Framework\Filesystem\Driver\File</argument>
        </arguments>
    </type>
    
    <!-- Custom Logger -->
    <type name="YourCompany\YourModule\Model\Logger\Logger">
        <arguments>
            <argument name="name" xsi:type="string">CustomModuleLogger</argument>
            <argument name="handlers" xsi:type="array">
                <item name="system" xsi:type="object">YourCompany\YourModule\Model\Logger\Handler</item>
            </argument>
        </arguments>
    </type>
</config>
```

#### Step 7: Using the Custom Logger

```php
<?php
namespace YourCompany\YourModule\Model;

use YourCompany\YourModule\Model\Logger\Logger;

class SomeModel
{
    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function someMethod()
    {
        $this->logger->info('This is an info message');
        $this->logger->error('This is an error message');
        $this->logger->debug('Debug information', ['context' => 'additional data']);
    }
}
```

### Advanced File Logger Configuration

#### Multiple Log Files

Create separate handlers for different log levels:

**etc/di.xml**
```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    
    <!-- Error Handler -->
    <type name="YourCompany\YourModule\Model\Logger\ErrorHandler">
        <arguments>
            <argument name="filesystem" xsi:type="object">Magento\Framework\Filesystem\Driver\File</argument>
        </arguments>
    </type>
    
    <!-- Info Handler -->
    <type name="YourCompany\YourModule\Model\Logger\InfoHandler">
        <arguments>
            <argument name="filesystem" xsi:type="object">Magento\Framework\Filesystem\Driver\File</argument>
        </arguments>
    </type>
    
    <!-- Multi-Handler Logger -->
    <type name="YourCompany\YourModule\Model\Logger\Logger">
        <arguments>
            <argument name="name" xsi:type="string">MultiLogger</argument>
            <argument name="handlers" xsi:type="array">
                <item name="error" xsi:type="object">YourCompany\YourModule\Model\Logger\ErrorHandler</item>
                <item name="info" xsi:type="object">YourCompany\YourModule\Model\Logger\InfoHandler</item>
            </argument>
        </arguments>
    </type>
</config>
```

#### Custom Log Format

**Model/Logger/Formatter.php**
```php
<?php
namespace YourCompany\YourModule\Model\Logger;

use Monolog\Formatter\LineFormatter;

class Formatter extends LineFormatter
{
    public function __construct()
    {
        // Custom format: [datetime] [level] [message] [context]
        $format = "[%datetime%] [%level_name%] %message% %context%\n";
        $dateFormat = 'Y-m-d H:i:s';
        parent::__construct($format, $dateFormat);
    }
}
```

Apply formatter in di.xml:
```xml
<type name="YourCompany\YourModule\Model\Logger\Handler">
    <arguments>
        <argument name="formatter" xsi:type="object">YourCompany\YourModule\Model\Logger\Formatter</argument>
    </arguments>
</type>
```

## Console Loggers {#console-loggers}

Console loggers output log messages directly to the terminal/console, useful for CLI commands and debugging.

### Basic Console Logger Setup

#### Step 1: Create Console Handler

**Model/Logger/ConsoleHandler.php**
```php
<?php
namespace YourCompany\YourModule\Model\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class ConsoleHandler extends StreamHandler
{
    public function __construct($level = MonologLogger::DEBUG, $bubble = true)
    {
        parent::__construct('php://stdout', $level, $bubble);
    }
}
```

#### Step 2: Configure Console Logger

**etc/di.xml**
```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    
    <!-- Console Logger -->
    <type name="YourCompany\YourModule\Model\Logger\ConsoleLogger">
        <arguments>
            <argument name="name" xsi:type="string">ConsoleLogger</argument>
            <argument name="handlers" xsi:type="array">
                <item name="console" xsi:type="object">YourCompany\YourModule\Model\Logger\ConsoleHandler</item>
            </argument>
        </arguments>
    </type>
</config>
```

#### Step 3: Create Console Command with Logger

**Console/Command/TestCommand.php**
```php
<?php
namespace YourCompany\YourModule\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YourCompany\YourModule\Model\Logger\ConsoleLogger;

class TestCommand extends Command
{
    protected $logger;

    public function __construct(ConsoleLogger $logger)
    {
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('yourmodule:test')
             ->setDescription('Test console logging');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Command started');
        $this->logger->debug('Processing data...');
        $this->logger->info('Command completed');
        
        return 0;
    }
}
```

### Advanced Console Logger Features

#### Colored Console Output

**Model/Logger/ColoredConsoleHandler.php**
```php
<?php
namespace YourCompany\YourModule\Model\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Monolog\Formatter\LineFormatter;

class ColoredConsoleHandler extends StreamHandler
{
    private $colorMap = [
        MonologLogger::DEBUG => "\033[0;37m",     // White
        MonologLogger::INFO => "\033[0;32m",      // Green
        MonologLogger::NOTICE => "\033[1;33m",    // Yellow
        MonologLogger::WARNING => "\033[0;33m",   // Yellow
        MonologLogger::ERROR => "\033[0;31m",     // Red
        MonologLogger::CRITICAL => "\033[1;31m",  // Bold Red
        MonologLogger::ALERT => "\033[1;35m",     // Bold Magenta
        MonologLogger::EMERGENCY => "\033[1;41m", // Bold Red Background
    ];

    public function __construct($level = MonologLogger::DEBUG, $bubble = true)
    {
        parent::__construct('php://stdout', $level, $bubble);
        $this->setFormatter(new LineFormatter(
            "%start_color%[%datetime%] %level_name%: %message%%end_color%\n",
            'H:i:s'
        ));
    }

    protected function write(array $record): void
    {
        $levelColor = $this->colorMap[$record['level']] ?? '';
        $endColor = $levelColor ? "\033[0m" : '';
        
        $record['formatted'] = str_replace(
            ['%start_color%', '%end_color%'],
            [$levelColor, $endColor],
            $record['formatted']
        );
        
        parent::write($record);
    }
}
```

## Mailer Loggers {#mailer-loggers}

Mailer loggers send log messages via email, useful for critical errors and alerts.

### Basic Email Logger Setup

#### Step 1: Create Mail Handler

**Model/Logger/MailHandler.php**
```php
<?php
namespace YourCompany\YourModule\Model\Logger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger as MonologLogger;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class MailHandler extends AbstractProcessingHandler
{
    protected $transportBuilder;
    protected $scopeConfig;
    protected $toEmail;
    protected $fromEmail;
    protected $subject;

    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        $toEmail = null,
        $fromEmail = null,
        $subject = 'Application Log Alert',
        $level = MonologLogger::ERROR,
        $bubble = true
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->toEmail = $toEmail ?: $this->scopeConfig->getValue(
            'trans_email/ident_general/email',
            ScopeInterface::SCOPE_STORE
        );
        $this->fromEmail = $fromEmail ?: $this->scopeConfig->getValue(
            'trans_email/ident_general/email',
            ScopeInterface::SCOPE_STORE
        );
        $this->subject = $subject;
        
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $this->sendEmail($record);
    }

    protected function sendEmail(array $record)
    {
        try {
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('custom_log_alert_email_template') // You need to create this
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => 1,
                ])
                ->setTemplateVars([
                    'level' => $record['level_name'],
                    'message' => $record['message'],
                    'datetime' => $record['datetime']->format('Y-m-d H:i:s'),
                    'context' => $record['context'] ?? [],
                    'extra' => $record['extra'] ?? []
                ])
                ->setFrom($this->fromEmail)
                ->addTo($this->toEmail)
                ->getTransport();
                
            $transport->sendMessage();
        } catch (\Exception $e) {
            // Fallback: don't let email sending break the application
            error_log('Failed to send log email: ' . $e->getMessage());
        }
    }
}
```

#### Step 2: Create Email Template

**view/frontend/email/custom_log_alert_email_template.html**
```html
<!--@subject Log Alert - {{var level}} @-->
<!--@vars {
    "var level":"Log Level",
    "var message":"Log Message", 
    "var datetime":"Date Time",
    "var context":"Context Data",
    "var extra":"Extra Data"
} @-->

<h2>Log Alert: {{var level}}</h2>
<p><strong>Time:</strong> {{var datetime}}</p>
<p><strong>Message:</strong> {{var message}}</p>

{{if context}}
<h3>Context:</h3>
<pre>{{var context}}</pre>
{{/if}}

{{if extra}}
<h3>Additional Information:</h3>
<pre>{{var extra}}</pre>
{{/if}}
```

#### Step 3: Register Email Template

**etc/email_templates.xml**
```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Email:etc/email_templates.xsd">
    <template id="custom_log_alert_email_template" 
              label="Custom Log Alert Email Template" 
              file="custom_log_alert_email_template.html" 
              type="html" 
              module="YourCompany_YourModule" 
              area="frontend"/>
</config>
```

#### Step 4: Configure Mail Logger

**etc/di.xml**
```xml
<type name="YourCompany\YourModule\Model\Logger\MailHandler">
    <arguments>
        <argument name="toEmail" xsi:type="string">admin@yourstore.com</argument>
        <argument name="fromEmail" xsi:type="string">noreply@yourstore.com</argument>
        <argument name="subject" xsi:type="string">Critical Error Alert</argument>
        <argument name="level" xsi:type="number">400</argument> <!-- ERROR level -->
    </arguments>
</type>

<type name="YourCompany\YourModule\Model\Logger\MailLogger">
    <arguments>
        <argument name="name" xsi:type="string">MailLogger</argument>
        <argument name="handlers" xsi:type="array">
            <item name="mail" xsi:type="object">YourCompany\YourModule\Model\Logger\MailHandler</item>
        </arguments>
    </arguments>
</type>
```

### Advanced Email Logger Features

#### Throttled Email Logger

To prevent email spam, implement throttling:

**Model/Logger/ThrottledMailHandler.php**
```php
<?php
namespace YourCompany\YourModule\Model\Logger;

use Magento\Framework\App\CacheInterface;

class ThrottledMailHandler extends MailHandler
{
    protected $cache;
    protected $throttleMinutes;

    public function __construct(
        // ... previous constructor parameters
        CacheInterface $cache,
        $throttleMinutes = 60
    ) {
        parent::__construct(/* ... parameters ... */);
        $this->cache = $cache;
        $this->throttleMinutes = $throttleMinutes;
    }

    protected function write(array $record): void
    {
        $cacheKey = 'email_log_throttle_' . md5($record['message'] . $record['level']);
        
        if (!$this->cache->load($cacheKey)) {
            parent::write($record);
            $this->cache->save(
                time(), 
                $cacheKey, 
                ['email_log_throttle'], 
                $this->throttleMinutes * 60
            );
        }
    }
}
```

## Advanced Configuration {#advanced-configuration}

### Environment-Based Logging

**etc/di.xml**
```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    
    <!-- Production Logger (Errors only to file + email) -->
    <type name="YourCompany\YourModule\Model\Logger\ProductionLogger">
        <arguments>
            <argument name="name" xsi:type="string">ProductionLogger</argument>
            <argument name="handlers" xsi:type="array">
                <item name="file" xsi:type="object">YourCompany\YourModule\Model\Logger\ErrorFileHandler</item>
                <item name="mail" xsi:type="object">YourCompany\YourModule\Model\Logger\CriticalMailHandler</item>
            </argument>
        </arguments>
    </type>
    
    <!-- Development Logger (All levels to file + console) -->
    <type name="YourCompany\YourModule\Model\Logger\DevelopmentLogger">
        <arguments>
            <argument name="name" xsi:type="string">DevelopmentLogger</argument>
            <argument name="handlers" xsi:type="array">
                <item name="file" xsi:type="object">YourCompany\YourModule\Model\Logger\DebugFileHandler</item>
                <item name="console" xsi:type="object">YourCompany\YourModule\Model\Logger\ConsoleHandler</item>
            </argument>
        </arguments>
    </type>
</config>
```

### Conditional Logger Factory

**Model/LoggerFactory.php**
```php
<?php
namespace YourCompany\YourModule\Model;

use Magento\Framework\App\State;
use YourCompany\YourModule\Model\Logger\ProductionLogger;
use YourCompany\YourModule\Model\Logger\DevelopmentLogger;

class LoggerFactory
{
    protected $appState;
    protected $productionLogger;
    protected $developmentLogger;

    public function __construct(
        State $appState,
        ProductionLogger $productionLogger,
        DevelopmentLogger $developmentLogger
    ) {
        $this->appState = $appState;
        $this->productionLogger = $productionLogger;
        $this->developmentLogger = $developmentLogger;
    }

    public function create()
    {
        try {
            $mode = $this->appState->getMode();
            return $mode === State::MODE_DEVELOPER 
                ? $this->developmentLogger 
                : $this->productionLogger;
        } catch (\Exception $e) {
            return $this->productionLogger;
        }
    }
}
```

### Structured Logging with Processors

**Model/Logger/Processor/ContextProcessor.php**
```php
<?php
namespace YourCompany\YourModule\Model\Logger\Processor;

use Magento\Framework\HTTP\PhpEnvironment\Request;
use Magento\Customer\Model\Session as CustomerSession;

class ContextProcessor
{
    protected $request;
    protected $customerSession;

    public function __construct(
        Request $request,
        CustomerSession $customerSession
    ) {
        $this->request = $request;
        $this->customerSession = $customerSession;
    }

    public function __invoke(array $record)
    {
        $record['extra']['request_uri'] = $this->request->getRequestUri();
        $record['extra']['user_agent'] = $this->request->getHeader('User-Agent');
        $record['extra']['ip_address'] = $this->request->getClientIp();
        
        if ($this->customerSession->isLoggedIn()) {
            $record['extra']['customer_id'] = $this->customerSession->getCustomerId();
            $record['extra']['customer_email'] = $this->customerSession->getCustomer()->getEmail();
        }
        
        $record['extra']['memory_usage'] = memory_get_usage(true);
        $record['extra']['timestamp'] = microtime(true);
        
        return $record;
    }
}
```

Add processor to logger configuration:
```xml
<type name="YourCompany\YourModule\Model\Logger\Logger">
    <arguments>
        <argument name="processors" xsi:type="array">
            <item name="context" xsi:type="object">YourCompany\YourModule\Model\Logger\Processor\ContextProcessor</item>
        </argument>
    </arguments>
</type>
```

## Best Practices {#best-practices}

### 1. Log Level Guidelines

**DEBUG**: Use for detailed diagnostic information
```php
$this->logger->debug('User search query', ['query' => $searchTerm, 'filters' => $filters]);
```

**INFO**: Use for general application flow
```php
$this->logger->info('Order placed successfully', ['order_id' => $orderId, 'customer_id' => $customerId]);
```

**WARNING**: Use for potentially harmful situations
```php
$this->logger->warning('Product inventory low', ['product_id' => $productId, 'qty' => $currentQty]);
```

**ERROR**: Use for error conditions that don't stop the application
```php
$this->logger->error('Payment processing failed', ['order_id' => $orderId, 'error' => $exception->getMessage()]);
```

**CRITICAL**: Use for critical conditions
```php
$this->logger->critical('Database connection lost', ['exception' => $exception->getMessage()]);
```

### 2. Performance Considerations

#### Lazy Loading
```php
class SomeModel
{
    private $logger;
    private $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->loggerFactory = $loggerFactory;
    }

    private function getLogger()
    {
        if (!$this->logger) {
            $this->logger = $this->loggerFactory->create();
        }
        return $this->logger;
    }

    public function someMethod()
    {
        $this->getLogger()->info('Method called');
    }
}
```

#### Conditional Logging
```php
public function expensiveOperation()
{
    if ($this->logger->isHandling(Logger::DEBUG)) {
        $this->logger->debug('Expensive debug info', $this->getExpensiveDebugData());
    }
}
```

### 3. Security Best Practices

#### Sanitize Sensitive Data
```php
public function logUserData($userData)
{
    $sanitizedData = $userData;
    unset($sanitizedData['password']);
    unset($sanitizedData['credit_card']);
    
    $this->logger->info('User data processed', $sanitizedData);
}
```

#### Use Context Instead of String Interpolation
```php
// Good
$this->logger->info('Order created', ['order_id' => $orderId, 'total' => $total]);

// Avoid
$this->logger->info("Order {$orderId} created with total {$total}");
```

### 4. Log Rotation and Management

**etc/crontab.xml**
```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Cron:etc/crontab.xsd">
    <group id="default">
        <job name="custom_log_cleanup" instance="YourCompany\YourModule\Cron\LogCleanup" method="execute">
            <schedule>0 2 * * *</schedule> <!-- Daily at 2 AM -->
        </job>
    </group>
</config>
```

**Cron/LogCleanup.php**
```php
<?php
namespace YourCompany\YourModule\Cron;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;

class LogCleanup
{
    protected $directoryList;
    protected $file;

    public function __construct(
        DirectoryList $directoryList,
        File $file
    ) {
        $this->directoryList = $directoryList;
        $this->file = $file;
    }

    public function execute()
    {
        $logDir = $this->directoryList->getPath(DirectoryList::LOG);
        $cutoffTime = time() - (30 * 24 * 60 * 60); // 30 days ago
        
        $files = $this->file->readDirectory($logDir);
        
        foreach ($files as $file) {
            if ($this->file->isFile($file) && 
                pathinfo($file, PATHINFO_EXTENSION) === 'log' &&
                $this->file->stat($file)['mtime'] < $cutoffTime) {
                $this->file->deleteFile($file);
            }
        }
    }
}
```

## Troubleshooting {#troubleshooting}

### Common Issues and Solutions

#### 1. Logs Not Appearing

**Check File Permissions**
```bash
chmod 777 var/log/
```

**Verify Handler Configuration**
```php
// Test if handler is working
$handler = new YourCompany\YourModule\Model\Logger\Handler();
$logger = new Monolog\Logger('test', [$handler]);
$logger->info('Test message');
```

#### 2. Email Logs Not Sending

**Check Email Configuration**
```php
// Test email configuration
try {
    $transport = $this->transportBuilder
        ->setTemplateIdentifier('customer_create_account_email_template')
        ->setTemplateOptions(['area' => 'frontend', 'store' => 1])
        ->setTemplateVars(['customer' => 'Test'])
        ->setFrom('test@example.com')
        ->addTo('recipient@example.com')
        ->getTransport();
    $transport->sendMessage();
} catch (\Exception $e) {
    echo "Email error: " . $e->getMessage();
}
```

#### 3. Performance Issues

**Check Log Level in Production**
```xml
<!-- Make sure debug logging is disabled in production -->
<type name="YourCompany\YourModule\Model\Logger\Handler">
    <arguments>
        <argument name="loggerType" xsi:type="number">400</argument> <!-- ERROR level -->
    </arguments>
</type>
```

#### 4. Memory Issues

**Use Buffered Handlers for High-Volume Logging**
```php
use Monolog\Handler\BufferHandler;

class BufferedFileHandler extends BufferHandler
{
    public function __construct()
    {
        parent::__construct(
            new \YourCompany\YourModule\Model\Logger\Handler(),
            100, // Buffer size
            MonologLogger::INFO,
            true, // Bubble
            true  // Flush on overflow
        );
    }
}
```

### Debug Commands

Create a debug command to test logger configuration:

**Console/Command/LoggerTestCommand.php**
```php
<?php
namespace YourCompany\YourModule\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YourCompany\YourModule\Model\Logger\Logger;

class LoggerTestCommand extends Command
{
    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('yourmodule:test-logger')
             ->setDescription('Test logger configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->debug('Debug message test');
        $this->logger->info('Info message test');
        $this->logger->warning('Warning message test');
        $this->logger->error('Error message test');
        $this->logger->critical('Critical message test');
        
        $output->writeln('Logger test completed. Check log files and email.');
        return 0;
    }
}
```

This comprehensive guide covers all aspects of implementing file, console, and mailer loggers in Magento 2. Each section includes practical examples and can be adapted to your specific requirements.

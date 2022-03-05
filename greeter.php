<?php
require __DIR__ . '/vendor/autoload.php';

use Ray\Di\AbstractModule;
use Ray\Di\Di\Qualifier;
use Ray\Di\ProviderInterface;
use Ray\Di\Injector;

#[Attribute, Qualifier]
class Message
{
}

#[Attribute, Qualifier]
class Count
{
}

class CountProvider implements ProviderInterface
{
    public function get(): int
    {
        return 3;
    }
}

class MessageProvider implements ProviderInterface
{
    public function get(): string
    {
        return 'hello world';
    }
}

class DemoModule extends AbstractModule
{
    protected function configure()
    {
        $this->bind()->annotatedWith(Count::class)->toProvider(CountProvider::class);
        $this->bind()->annotatedWith(Message::class)->toProvider(MessageProvider::class);
    }
}

class Greeter
{
    public function __construct(
        #[Message] private string $message,
        #[Count] private int $count
    ) {}

    public function sayHello(): void
    {
        for ($i = 0; $i < $this->count ; $i++) {
            echo $this->message . PHP_EOL;
        }
    }
}

/*
 * Injector() takes one or more modules. Most applications
 * will call this method exactly once, in their main() method.
 */
$injector = new Injector(new DemoModule);

/*
 * Now that we've got the injector, we can build objects.
 */
$greeter = $injector->getInstance(Greeter::class);

// Prints "hello world" 3 times to the console.
$greeter->sayHello();

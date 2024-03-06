<?php

use app\libraries\ExampleCommand;
use app\libraries\ExampleDependency;
use app\libraries\ExampleService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestResult;

class TestDoubleTest extends TestCase
{
    
    public function testMock()
    {
      $mock = $this->createMock(ExampleService::class);

      $mock->expects($this->once())->method('doSomething')->with('bar')->willReturn('foo');//($this->exactly(2))

      $exampleCommand = new ExampleCommand($mock);

      $this->assertSame('foo', $exampleCommand->execute('bar'));
    }

    public function testReturnTypes()
    {
        $mock = $this->createMock(ExampleService::class);  
  
        $this->assertNull($mock->doSomething('bar'));
    }

    public function testConsecutiveReturns()
    {
        $mock = $this->createMock(ExampleService::class);  

        $mock->method('doSomething')->will($this->onConsecutiveCalls(1,2));

        foreach([1,2] as $value){
            $this->assertSame($value, $mock->doSomething('bar'));
        }
    }

    public function testExceptionsThrown()
    {
        $mock = $this->createMock(ExampleService::class);  

        $mock->method('doSomething')->willThrowException(new RuntimeException());

        $this->expectException(RuntimeException::class);

        $mock->doSomething('bar');
    }

    public function testCallbackReturns()
    {
        $mock = $this->createMock(ExampleService::class); 

        $mock->method('doSomething')->willReturnCallback(function($arg){
                
            if($arg % 2 == 0){
              return $arg;
            }

            throw new InvalidArgumentException();

        });

        $this->assertSame(10, $mock->doSomething(10));

        $this->expectException(InvalidArgumentException::class);

        $mock->doSomething(9);
    }

    public function testWithEqualTo()
    {
      $mock = $this->createMock(ExampleService::class);

      $mock->expects($this->once())->method('doSomething')->with($this->equalTo('bar'));

      $mock->doSomething('bar');
    }

    public function testMultipleArgs()
    {
        $mock = $this->createMock(ExampleService::class);

        $mock->expects($this->once())->method('doSomething')->with(
            $this->stringContains('foo'), $this->greaterThanOrEqual(100),$this->anything()
        );

        $mock->doSomething('foobar', 101, null);
        
    }

    public function testCallbackArguments()
    {
        $mock = $this->createMock(ExampleService::class);

        $mock->expects($this->once())->method('doSomething')->with($this->callback(function($object){
            $this->assertInstanceOf(ExampleDependency::class, $object);
            return $object->exampleMethod() === 'Example string';
        }));

        $mock->doSomething(new ExampleDependency());
    }
}



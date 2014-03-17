<?php

namespace mageekguy\atoum\tests\units\test\assertion;

use
	mageekguy\atoum,
	mageekguy\atoum\test
;

require_once __DIR__ . '/../../../runner.php';

class manager extends atoum\test
{
	public function test__get()
	{
		$this
			->given($assertionManager = $this->newTestedInstance)
			->then
				->exception(function() use ($assertionManager, & $event) {
						$assertionManager->{$event = uniqid()};
					}
				)
					->isInstanceOf('mageekguy\atoum\test\assertion\manager\exception')
					->hasMessage('There is no handler defined for event \'' . $event . '\'')

			->if($this->testedInstance->setDefaultHandler(function() use (& $defaultReturn) { return ($defaultReturn = uniqid()); }))
			->then
				->string($this->testedInstance->{uniqid()})->isEqualTo($defaultReturn)

			->if($this->testedInstance->setHandler($event = uniqid(), function() use (& $eventReturn) { return ($eventReturn = uniqid()); }))
			->then
				->string($this->testedInstance->{uniqid()})->isEqualTo($defaultReturn)
				->string($this->testedInstance->{$event})->isEqualTo($eventReturn)

			->if($this->testedInstance->setMethodHandler($methodEvent = uniqid(), function() use (& $methodReturn) { return ($methodReturn = uniqid()); }))
			->then
				->string($this->testedInstance->{uniqid()})->isEqualTo($defaultReturn)
				->string($this->testedInstance->{$event})->isEqualTo($eventReturn)
				->string($this->testedInstance->{$methodEvent})->isEqualTo($defaultReturn)

			->if($this->testedInstance->setPropertyHandler($propertyEvent = uniqid(), function() use (& $propertyReturn) { return ($propertyReturn = uniqid()); }))
			->then
				->string($this->testedInstance->{uniqid()})->isEqualTo($defaultReturn)
				->string($this->testedInstance->{$event})->isEqualTo($eventReturn)
				->string($this->testedInstance->{$methodEvent})->isEqualTo($defaultReturn)
				->string($this->testedInstance->{$propertyEvent})->isEqualTo($propertyReturn)
		;
	}

	public function test__set()
	{
		$this
			->given($this->newTestedInstance)

			->if($this->testedInstance->{$event = uniqid()} = function() use (& $return) { return ($return = uniqid()); })
			->then
				->string($this->testedInstance->invokeMethodHandler($event))->isEqualTo($return)
				->string($this->testedInstance->invokePropertyHandler($event))->isEqualTo($return)
		;
	}

	public function test__call()
	{
		$this
			->given($assertionManager = $this->newTestedInstance)
			->then
				->exception(function() use ($assertionManager, & $event) {
						$assertionManager->{$event = uniqid()}();
					}
				)
					->isInstanceOf('mageekguy\atoum\test\assertion\manager\exception')
					->hasMessage('There is no handler defined for event \'' . $event . '\'')

			->if($this->testedInstance->setDefaultHandler(function($event, $defaultArg) { return $defaultArg; }))
			->then
				->array($this->testedInstance->{uniqid()}($arg = uniqid()))->isEqualTo(array($arg))

			->if($this->testedInstance->setHandler($event, function($arg) { return $arg; }))
			->then
				->array($this->testedInstance->{uniqid()}($arg = uniqid()))->isEqualTo(array($arg))
				->string($this->testedInstance->{$event}($eventArg = uniqid()))->isEqualTo($eventArg)

			->if($this->testedInstance->setMethodHandler($methodEvent = uniqid(), function() use (& $methodReturn) { return ($methodReturn = uniqid()); }))
			->then
				->array($this->testedInstance->{uniqid()}($arg = uniqid()))->isEqualTo(array($arg))
				->string($this->testedInstance->{$event}($eventArg = uniqid()))->isEqualTo($eventArg)
				->string($this->testedInstance->{$methodEvent}())->isEqualTo($methodReturn)

			->if($this->testedInstance->setPropertyHandler($propertyEvent = uniqid(), function() use (& $propertyReturn) { return ($propertyReturn = uniqid()); }))
			->then
				->array($this->testedInstance->{uniqid()}($arg = uniqid()))->isEqualTo(array($arg))
				->string($this->testedInstance->{$event}($eventArg = uniqid()))->isEqualTo($eventArg)
				->string($this->testedInstance->{$methodEvent}())->isEqualTo($methodReturn)
				->array($this->testedInstance->{$propertyEvent}($arg = uniqid()))->isEqualTo(array($arg))
		;
	}

	public function testSetHandler()
	{
		$this
			->given($this->newTestedInstance)
			->then
				->object($this->testedInstance->setHandler($event = uniqid(), function() use (& $return) { return ($return = uniqid()); }))->isTestedInstance
				->string($this->testedInstance->invokeMethodHandler($event))->isEqualTo($return)
				->string($this->testedInstance->invokePropertyHandler($event))->isEqualTo($return)

				->object($this->testedInstance->setHandler($otherEvent = uniqid(), function() use (& $otherReturn) { return ($otherReturn = uniqid()); }))->isTestedInstance
				->string($this->testedInstance->invokeMethodHandler($event))->isEqualTo($return)
				->string($this->testedInstance->invokePropertyHandler($event))->isEqualTo($return)
				->string($this->testedInstance->invokeMethodHandler($otherEvent))->isEqualTo($otherReturn)
				->string($this->testedInstance->invokePropertyHandler($otherEvent))->isEqualTo($otherReturn)
		;
	}

	public function testSetPropertyHandler()
	{
		$this
			->given($assertionManager = $this->newTestedInstance)
			->then
				->object($this->testedInstance->setPropertyHandler($event = uniqid(), function() use (& $return) { return ($return = uniqid()); }))->isTestedInstance
				->string($this->testedInstance->invokePropertyHandler($event))->isEqualTo($return)

				->object($this->testedInstance->setPropertyHandler($otherEvent = uniqid(), function() use (& $otherReturn) { return ($otherReturn = uniqid()); }))->isTestedInstance
				->string($this->testedInstance->invokePropertyHandler($event))->isEqualTo($return)
				->string($this->testedInstance->invokePropertyHandler($otherEvent))->isEqualTo($otherReturn)

				->exception(function() use ($assertionManager, $event) {
						$assertionManager->invokeMethodHandler($event);
					}
				)
					->isInstanceOf('mageekguy\atoum\test\assertion\manager\exception')
					->hasMessage('There is no handler defined for event \'' . $event . '\'')
		;
	}

	public function testSetMethodHandler()
	{
		$this
			->given($assertionManager = $this->newTestedInstance)
			->then
				->object($this->testedInstance->setMethodHandler($event = uniqid(), function() use (& $return) { return ($return = uniqid()); }))->isTestedInstance
				->string($this->testedInstance->invokeMethodHandler($event))->isEqualTo($return)

				->object($this->testedInstance->setMethodHandler($otherEvent = uniqid(), function() use (& $otherReturn) { return ($otherReturn = uniqid()); }))->isTestedInstance
				->string($this->testedInstance->invokeMethodHandler($event))->isEqualTo($return)
				->string($this->testedInstance->invokeMethodHandler($otherEvent))->isEqualTo($otherReturn)

				->exception(function() use ($assertionManager, $event) {
						$assertionManager->invokePropertyHandler($event);
					}
				)
					->isInstanceOf('mageekguy\atoum\test\assertion\manager\exception')
					->hasMessage('There is no handler defined for event \'' . $event . '\'')
		;
	}

	public function testSetDefaultHandler()
	{
		$this
			->given($this->newTestedInstance)
			->then
				->object($this->testedInstance->setDefaultHandler($handler = function() {}))->isTestedInstance
		;
	}

	public function testInvokeMethodHandler()
	{
		$this
			->given($assertionManager = $this->newTestedInstance)
			->then
				->exception(function() use ($assertionManager, & $event) {
						$assertionManager->invokeMethodHandler($event = uniqid());
					}
				)
					->isInstanceOf('mageekguy\atoum\test\assertion\manager\exception')
					->hasMessage('There is no handler defined for event \'' . $event . '\'')

			->if($this->testedInstance->setDefaultHandler(function($event, $arg) { return $arg; }))
			->then
				->array($this->testedInstance->invokeMethodHandler(uniqid(), array($defaultArg = uniqid())))->isEqualTo(array($defaultArg))

			->if($this->testedInstance->setMethodHandler($event = uniqid(), function($eventArg) { return $eventArg; }))
			->then
				->string($this->testedInstance->invokeMethodHandler($event, array($eventArg = uniqid())))->isEqualTo($eventArg)
		;
	}
}

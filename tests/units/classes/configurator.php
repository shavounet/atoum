<?php

namespace atoum\atoum\tests\units;

use atoum\atoum;

require_once __DIR__ . '/../runner.php';

class configurator extends atoum\test
{
    public function test__construct()
    {
        $this
            ->if($script = new atoum\scripts\runner(uniqid()))
            ->and($configurator = new atoum\configurator($script))
            ->then
                ->object($configurator->getScript())->isIdenticalTo($script)
        ;
    }

    public function test__call()
    {
        $this
            ->if($runner = new \mock\atoum\atoum\runner())
            ->and($runner->getMockController()->setBootstrapFile = function () {
            })
            ->and($script = new \mock\atoum\atoum\scripts\runner(uniqid()))
            ->and($this->calling($script)->addDefaultReport = $report = new \mock\atoum\atoum\report())
            ->and($script->setRunner($runner))
            ->and($configurator = new atoum\configurator($script))
            ->then
                ->object($configurator->scoreFile($scoreFile = uniqid()))->isIdenticalTo($configurator)
                ->mock($script)->call('setScoreFile')->withArguments($scoreFile)->once()
                ->object($configurator->bf($bootstrapFile = uniqid()))->isIdenticalTo($configurator)
                ->mock($runner)->call('setBootstrapFile')->withArguments($bootstrapFile)->once()
                ->object($configurator->bootstrapFile($bootstrapFile = uniqid()))->isIdenticalTo($configurator)
                ->mock($runner)->call('setBootstrapFile')->withArguments($bootstrapFile)->once()
                ->object($configurator->setScoreFile($scoreFile = uniqid()))->isIdenticalTo($configurator)
                ->mock($script)->call('setScoreFile')->withArguments($scoreFile)->once()
                ->object($configurator->addDefaultReport())->isIdenticalTo($report)
                ->mock($script)->call('addDefaultReport')->once()
                ->object($configurator->noCodeCoverageForNamespaces($namespaces = ['foo', 'bar']))->isIdenticalTo($configurator)
                ->mock($script)->call('excludeNamespacesFromCoverage')
                    ->withArguments($namespaces)->once()
                ->object($configurator->noCodeCoverageForNamespaces('foo', 'bar'))->isIdenticalTo($configurator)
                ->mock($script)->call('excludeNamespacesFromCoverage')
                    ->withArguments($namespaces)->twice()
            ->exception(function () use ($configurator, & $method) {
                $configurator->{$method = uniqid()}();
            })
                ->isInstanceOf(atoum\exceptions\runtime\unexpectedValue::class)
                ->hasMessage('Method \'' . $method . '\' is unavailable')
        ;
    }
}

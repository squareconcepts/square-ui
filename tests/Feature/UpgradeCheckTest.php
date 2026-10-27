<?php

namespace Squareconcepts\SquareUi\Tests\Feature;

use Squareconcepts\SquareUi\Tests\TestCase;

class UpgradeCheckTest extends TestCase
{
    public function test_deprecated_components_are_reported_without_failing(): void
    {
        $this->artisan('square-ui:upgrade-check', [
            '--path' => [__DIR__ . '/../Fixtures/upgrade-check'],
            '--no-report' => true,
        ])
            ->expectsOutputToContain('0 removed / 5 deprecated')
            ->expectsOutputToContain('<x-square-ui::card> (1×)')
            ->expectsOutputToContain('<x-square-ui::inputs.datepicker> (1×)')
            ->expectsOutputToContain('<x-square-ui::inputs.time-picker> (1×)')
            ->expectsOutputToContain('<x-square-ui.inputs.pin> (1×)')
            ->expectsOutputToContain('<livewire:square-ui::data-table> (1×)')
            ->doesntExpectOutputToContain('dropzone')
            ->assertExitCode(0);
    }

    public function test_positive_and_warning_buttons_become_flux_2_colours(): void
    {
        $this->artisan('square-ui:upgrade-check', [
            '--path' => [__DIR__ . '/../Fixtures/upgrade-check-buttons'],
            '--no-report' => true,
        ])
            ->expectsOutputToContain('<flux:button variant="positive|warning"> (2×)')
            ->assertExitCode(1);

        $rule = collect((new \ReflectionMethod(\Squareconcepts\SquareUi\Commands\UpgradeCheck::class, 'buildRules'))->invoke(new \Squareconcepts\SquareUi\Commands\UpgradeCheck()))
            ->firstWhere('key', 'button_positive_warning');

        $this->assertSame('<flux:button variant="primary" color="green" icon="check">', ($rule['transform'])('<flux:button variant="positive" icon="check">'));
        $this->assertSame('<flux:button size="sm" variant="primary" color="amber" />', ($rule['transform'])('<flux:button size="sm" variant="warning" />'));
        $this->assertSame('<flux:callout variant="warning">', ($rule['transform'])('<flux:callout variant="warning">'));
    }
}

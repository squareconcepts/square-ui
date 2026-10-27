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
}

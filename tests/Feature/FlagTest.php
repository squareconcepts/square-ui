<?php

namespace Squareconcepts\SquareUi\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Squareconcepts\SquareUi\BladeComponents\Flag;
use Squareconcepts\SquareUi\Tests\TestCase;

class FlagTest extends TestCase
{
    private string $flagDir;

    protected function setUp(): void
    {
        parent::setUp();

        // Flag kijkt in base_path('public/vendor/...'), waar vendor:publish --tag=square-ui-images ze neerzet.
        $this->flagDir = base_path('public/vendor/squareconcepts/square-ui/flags');
        File::ensureDirectoryExists($this->flagDir);
        File::put($this->flagDir . '/nl.svg', '<svg/>');
        File::put($this->flagDir . '/gb.svg', '<svg/>');

        $this->resetFlagCache();
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(base_path('public/vendor/squareconcepts'));
        $this->resetFlagCache();

        parent::tearDown();
    }

    private function resetFlagCache(): void
    {
        (new \ReflectionProperty(Flag::class, 'available'))->setValue(null, null);
    }

    public function test_it_renders_an_existing_flag(): void
    {
        $html = Blade::render('<x-square-ui::flag country="nl" class="w-6" />');

        $this->assertStringContainsString('vendor/squareconcepts/square-ui/flags/nl.svg', $html);
        $this->assertStringContainsString('class="w-6"', $html);
    }

    public function test_en_maps_to_the_gb_flag(): void
    {
        $html = Blade::render('<x-square-ui::flag country="en" />');

        $this->assertStringContainsString('flags/gb.svg', $html);
    }

    public function test_missing_flag_shows_a_translated_error_and_the_available_options(): void
    {
        $html = Blade::render('<x-square-ui::flag country="xx" />');

        $this->assertStringNotContainsString('<img src="' . asset('vendor/squareconcepts/square-ui/flags/xx.svg'), $html);
        $this->assertStringContainsString(__('square-ui::square-ui.flag_not_found', ['file' => 'xx.svg']), $html);
        $this->assertStringContainsString('flags/nl.svg', $html);
    }

    public function test_country_is_escaped(): void
    {
        $html = Blade::render('<x-square-ui::flag :country="$c" />', ['c' => '"><script>alert(1)</script>']);

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
    }
}

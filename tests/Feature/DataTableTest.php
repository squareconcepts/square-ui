<?php

namespace Squareconcepts\SquareUi\Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use Squareconcepts\SquareUi\LivewireComponents\DataTable;
use Squareconcepts\SquareUi\Tests\Fixtures\Widget;
use Squareconcepts\SquareUi\Tests\TestCase;

class DataTableTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Widget::create(['id' => 1, 'name' => 'Alpha']);
        Widget::create(['id' => 2, 'name' => 'Beta']);
        Widget::create(['id' => 3, 'name' => 'Not given to the table']);
    }

    private function table(array $overrides = [])
    {
        return Livewire::test(DataTable::class, array_merge([
            'model' => Widget::class,
            'results' => collect([
                ['id' => 1, 'name' => 'Alpha'],
                ['id' => 2, 'name' => 'Beta'],
            ]),
            'columns' => ['name' => ['label' => 'Name']],
        ], $overrides));
    }

    public static function lockedProperties(): array
    {
        return [
            'model' => ['model', \stdClass::class],
            'hideDelete' => ['hideDelete', false],
            'deletableIds' => ['deletableIds', ['3']],
            'results' => ['results', [['id' => 3]]],
            'routePrefix' => ['routePrefix', 'https://evil.example'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('lockedProperties')]
    public function test_security_relevant_properties_are_locked(string $property, mixed $value): void
    {
        $this->expectException(CannotUpdateLockedPropertyException::class);

        $this->table()->set($property, $value);
    }

    public function test_it_deletes_a_row_it_was_given(): void
    {
        $this->table()
            ->call('deleteRowCallback', 1)
            ->assertOk()
            ->assertSet('deletableIds', ['2']);

        $this->assertNull(Widget::find(1));
        $this->assertNotNull(Widget::find(2));
    }

    public function test_it_still_accepts_the_legacy_row_array(): void
    {
        $this->table()->call('deleteRowCallback', ['id' => 2])->assertOk();

        $this->assertNull(Widget::find(2));
    }

    public function test_id_must_be_in_deletable_ids(): void
    {
        $this->table()->call('deleteRowCallback', 3)->assertForbidden();

        $this->assertNotNull(Widget::find(3));
    }

    public function test_refresh_data_cannot_widen_the_deletable_ids(): void
    {
        $this->table()
            ->call('refreshData', [['id' => 3, 'name' => 'Injected']])
            ->call('deleteRowCallback', 3)
            ->assertForbidden();

        $this->assertNotNull(Widget::find(3));
    }

    public function test_model_must_be_an_eloquent_model_subclass(): void
    {
        $this->table(['model' => \stdClass::class])->call('deleteRowCallback', 1)->assertForbidden();
        $this->table(['model' => null])->call('deleteRowCallback', 1)->assertForbidden();

        $this->assertNotNull(Widget::find(1));
    }

    public function test_delete_is_refused_when_hidden(): void
    {
        $this->table(['hideDelete' => true])->call('deleteRowCallback', 1)->assertForbidden();

        $this->assertNotNull(Widget::find(1));
    }

    public function test_invalid_ids_are_refused(): void
    {
        $this->table()->call('deleteRowCallback', '')->assertForbidden();
        $this->table()->call('deleteRowCallback', ['id' => ['1']])->assertForbidden();

        $this->assertSame(3, Widget::count());
    }

    public function test_delete_row_only_opens_a_confirm_for_this_table(): void
    {
        $this->table()
            ->call('deleteRow', ['id' => 1])
            ->assertDispatched('show-confirm-dialog');

        $this->assertNotNull(Widget::find(1));
    }

    public function test_legacy_button_colours_map_to_flux_2_styles(): void
    {
        $table = new DataTable();

        $this->assertSame(['variant' => 'primary', 'color' => 'green'], $table->buttonStyle('positive'));
        $this->assertSame(['variant' => 'primary', 'color' => 'amber'], $table->buttonStyle('warning'));
        $this->assertSame(['variant' => 'danger', 'color' => null], $table->buttonStyle('danger'));
        $this->assertSame(['variant' => 'primary', 'color' => 'teal'], $table->buttonStyle('teal'));
        $this->assertSame(['variant' => 'primary', 'color' => null], $table->buttonStyle(null));
    }
}

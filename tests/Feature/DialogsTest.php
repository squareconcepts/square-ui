<?php

namespace Squareconcepts\SquareUi\Tests\Feature;

use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use Squareconcepts\SquareUi\LivewireComponents\Dialogs;
use Squareconcepts\SquareUi\Tests\TestCase;

class DialogsTest extends TestCase
{
    private const PAYLOAD = '<img src=x onerror=alert(1)>';

    private function confirmOptions(array $options = []): array
    {
        return ['options' => array_merge([
            'title' => 'Delete?',
            'description' => self::PAYLOAD,
            'method' => 'doDelete',
            'params' => [42],
        ], $options)];
    }

    public function test_confirm_description_is_escaped_by_default(): void
    {
        Livewire::test(Dialogs::class)
            ->call('showConfirmModal', $this->confirmOptions())
            ->assertSet('html', false)
            ->assertSee(e(self::PAYLOAD), false)
            ->assertDontSee(self::PAYLOAD, false);
    }

    public function test_confirm_description_is_raw_only_with_explicit_html_true(): void
    {
        Livewire::test(Dialogs::class)
            ->call('showConfirmModal', $this->confirmOptions(['description' => '<strong>trusted</strong>', 'html' => true]))
            ->assertSet('html', true)
            ->assertSeeHtml('<strong>trusted</strong>');
    }

    public function test_truthy_non_boolean_html_option_does_not_disable_escaping(): void
    {
        Livewire::test(Dialogs::class)
            ->call('showConfirmModal', $this->confirmOptions(['html' => 'yes']))
            ->assertSet('html', false)
            ->assertDontSee(self::PAYLOAD, false);
    }

    public function test_error_and_success_dialogs_escape_description(): void
    {
        Livewire::test(Dialogs::class)
            ->call('showErrorDialog', ['options' => ['title' => 'Oops', 'description' => self::PAYLOAD]])
            ->assertDontSee(self::PAYLOAD, false)
            ->call('showSuccessDialog', ['options' => ['title' => 'Yay', 'description' => self::PAYLOAD]])
            ->assertDontSee(self::PAYLOAD, false);
    }

    public function test_html_flag_cannot_be_set_from_the_browser(): void
    {
        $this->expectException(CannotUpdateLockedPropertyException::class);

        Livewire::test(Dialogs::class)->set('html', true);
    }

    public function test_pending_flag_cannot_be_set_from_the_browser(): void
    {
        $this->expectException(CannotUpdateLockedPropertyException::class);

        Livewire::test(Dialogs::class)->set('pending', true);
    }

    public function test_confirm_click_without_pending_dialog_does_nothing(): void
    {
        Livewire::test(Dialogs::class)
            ->set('method', 'doDelete')
            ->call('confirmClick')
            ->assertNotDispatched('doDelete')
            ->assertNotDispatched('confirm-dialog-response');
    }

    public function test_reject_click_without_pending_dialog_does_nothing(): void
    {
        Livewire::test(Dialogs::class)
            ->set('rejectMethod', 'doReject')
            ->call('rejectClick')
            ->assertNotDispatched('doReject')
            ->assertNotDispatched('confirm-dialog-response');
    }

    public function test_confirm_click_dispatches_callback_once_with_positional_params(): void
    {
        $component = Livewire::test(Dialogs::class)
            ->call('showConfirmModal', $this->confirmOptions())
            ->assertSet('pending', true)
            ->call('confirmClick')
            ->assertDispatched('doDelete', 42)
            ->assertDispatched('confirm-dialog-response', accepted: true)
            ->assertSet('pending', false);

        // Tweede klik (bijv. knop + sluiten van de modal) mag niets meer doen.
        $component->call('confirmClick')
            ->assertNotDispatched('doDelete')
            ->assertNotDispatched('confirm-dialog-response');
    }

    public function test_reject_click_dispatches_reject_callback_only_when_pending(): void
    {
        Livewire::test(Dialogs::class)
            ->call('showConfirmModal', $this->confirmOptions(['reject' => ['method' => 'doReject', 'params' => [7]]]))
            ->call('rejectClick')
            ->assertDispatched('doReject', 7)
            ->assertNotDispatched('doDelete')
            ->assertDispatched('confirm-dialog-response', accepted: false)
            ->call('rejectClick')
            ->assertNotDispatched('doReject');
    }

    public function test_close_button_uses_package_translation_instead_of_app_key(): void
    {
        Livewire::test(Dialogs::class)
            ->assertSee(__('square-ui::square-ui.close'))
            ->assertDontSee('buttons.close');
    }
}

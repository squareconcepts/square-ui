<?php

namespace Squareconcepts\SquareUi\Tests\Unit;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use Squareconcepts\SquareUi\Helpers\PasswordChecker;
use Squareconcepts\SquareUi\Helpers\TailwindHelper;
use Squareconcepts\SquareUi\Tests\TestCase;

class PackageTest extends TestCase
{
    public function test_add_class_macro_returns_a_bag_with_the_class(): void
    {
        $bag = (new ComponentAttributeBag(['class' => 'a']))->addClass('b');

        $this->assertInstanceOf(ComponentAttributeBag::class, $bag);
        $this->assertSame('b a', $bag->get('class'));
    }

    public function test_views_are_not_registered_under_the_square_core_namespace(): void
    {
        $hints = View::getFinder()->getHints();

        $this->assertArrayHasKey('square-ui', $hints);
        $this->assertArrayNotHasKey('square-core', $hints);
    }

    public function test_nl_and_en_translations_have_the_same_keys(): void
    {
        $nl = array_keys(Arr::dot(require __DIR__ . '/../../src/lang/nl/square-ui.php'));
        $en = array_keys(Arr::dot(require __DIR__ . '/../../src/lang/en/square-ui.php'));

        sort($nl);
        sort($en);

        $this->assertSame($nl, $en);
    }

    public function test_package_translations_resolve(): void
    {
        $this->app->setLocale('nl');
        $this->assertSame('Sluiten', __('square-ui::square-ui.close'));
        $this->assertSame('Vandaag', __('square-ui::square-ui.periods.today'));

        $this->app->setLocale('en');
        $this->assertSame('Close', __('square-ui::square-ui.close'));
    }

    public function test_password_checker_is_deprecated_and_keeps_the_password_out_of_the_snapshot(): void
    {
        Http::fake(['api.pwnedpasswords.com/*' => Http::response('')]);

        $deprecations = $this->captureDeprecations(function () {
            $checker = PasswordChecker::checkPassword('Sup3r$ecret!');
            $snapshot = $checker->toLivewire();

            $this->assertArrayNotHasKey('password', $snapshot);
            $this->assertStringNotContainsString('Sup3r$ecret!', json_encode($snapshot));
            $this->assertTrue($snapshot['hasSpecial']);

            $restored = PasswordChecker::fromLivewire($snapshot);
            $this->assertSame('', $restored->password);
            $this->assertTrue($restored->hasCapitalLetter);
        });

        $this->assertNotEmpty($deprecations);
        $this->assertStringContainsString('PasswordChecker is deprecated', $deprecations[0]);
    }

    public function test_tailwind_helper_is_deprecated(): void
    {
        $deprecations = $this->captureDeprecations(fn () => TailwindHelper::tailwindToHex('primary'));

        $this->assertCount(1, $deprecations);
        $this->assertStringContainsString('deprecated', $deprecations[0]);
    }

    private function captureDeprecations(callable $callback): array
    {
        $messages = [];

        set_error_handler(function (int $level, string $message) use (&$messages) {
            $messages[] = $message;

            return true;
        }, E_USER_DEPRECATED);

        try {
            $callback();
        } finally {
            restore_error_handler();
        }

        return $messages;
    }
}

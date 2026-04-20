<?php

namespace Squareconcepts\SquareUi\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

class UpgradeCheck extends Command
{
    protected $signature = 'square-ui:upgrade-check
        {--path=* : Extra directories to scan (in addition to app, resources/views)}
        {--output= : Output path for the migration report (default: base_path/square-ui-migration-report.md)}
        {--no-report : Skip writing the .md report, only show console summary}';

    protected $description = 'Scan de codebase op verwijderde/verouderde square-ui onderdelen en genereer een installatie-specifiek migratie rapport.';

    private array $rules;

    public function __construct()
    {
        parent::__construct();
        $this->rules = $this->buildRules();
    }

    public function handle(): int
    {
        $defaults = array_filter([app_path(), resource_path('views')], 'is_dir');
        $extra = array_filter($this->option('path'), 'is_dir');
        $paths = array_values(array_unique(array_merge($defaults, $extra)));

        if (empty($paths)) {
            $this->error('Geen geldige paden gevonden om te scannen.');
            return self::FAILURE;
        }

        $findings = $this->scan($paths);
        $totalRemoved = collect($findings)->where('severity', 'removed')->count();
        $totalDeprecated = collect($findings)->where('severity', 'deprecated')->count();
        $total = $totalRemoved + $totalDeprecated;

        $this->newLine();
        $this->line('<fg=cyan;options=bold>Square UI upgrade check</>');
        $this->line(str_repeat('─', 60));
        $this->line('  Scanned paths : ' . implode(', ', array_map(fn($p) => $this->relative($p), $paths)));
        $this->line('  Findings      : <fg=red>' . $totalRemoved . ' removed</> / <fg=yellow>' . $totalDeprecated . ' deprecated</>');

        if ($total === 0) {
            $this->newLine();
            $this->info('✓ Geen issues gevonden. De codebase is compatibel met de nieuwste square-ui.');
            return self::SUCCESS;
        }

        $this->printSummary($findings);

        if (!$this->option('no-report')) {
            $reportPath = $this->option('output') ?: base_path('square-ui-migration-report.md');
            file_put_contents($reportPath, $this->generateReport($findings, $paths));
            $this->newLine();
            $this->line('  <fg=green>✓</> Rapport geschreven naar <fg=cyan>' . $this->relative($reportPath) . '</>');
            $this->line('    Open dit bestand voor per-regel before/after + stap-voor-stap migratie.');
        }

        return $totalRemoved > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function scan(array $paths): array
    {
        $findings = [];

        foreach ($paths as $path) {
            $finder = (new Finder())->files()->in($path)->name(['*.php', '*.blade.php']);

            foreach ($finder as $file) {
                $content = $file->getContents();
                $lines = explode("\n", $content);
                $isBlade = str_ends_with($file->getFilename(), '.blade.php');

                foreach ($this->rules as $rule) {
                    if (!in_array($rule['scope'], ['both', $isBlade ? 'blade' : 'php'])) {
                        continue;
                    }

                    if (preg_match_all($rule['pattern'], $content, $matches, PREG_OFFSET_CAPTURE)) {
                        foreach ($matches[0] as $match) {
                            $lineNumber = substr_count(substr($content, 0, $match[1]), "\n") + 1;
                            $line = $lines[$lineNumber - 1] ?? '';

                            if ($this->isCommentedOut($line, $isBlade)) {
                                continue;
                            }

                            $findings[] = [
                                'file' => $file->getRealPath(),
                                'relative' => $this->relative($file->getRealPath()),
                                'line_number' => $lineNumber,
                                'line' => $line,
                                'rule_key' => $rule['key'],
                                'label' => $rule['label'],
                                'severity' => $rule['severity'],
                                'replacement_title' => $rule['replacement_title'],
                                'after' => $this->computeAfter($rule, $line),
                                'notes' => $rule['notes'],
                                'hint' => $rule['hint'],
                                'language' => $isBlade ? 'blade' : 'php',
                            ];
                        }
                    }
                }
            }
        }

        return $findings;
    }

    private function computeAfter(array $rule, string $line): string
    {
        if (isset($rule['transform']) && is_callable($rule['transform'])) {
            return ($rule['transform'])($line);
        }

        return $rule['template'] ?? '';
    }

    private function printSummary(array $findings): void
    {
        $this->newLine();
        $this->line('<options=bold>Findings per onderdeel:</>');
        $this->newLine();

        $grouped = collect($findings)->groupBy('rule_key');

        foreach ($grouped as $items) {
            $first = $items->first();
            $badge = $first['severity'] === 'removed'
                ? '<fg=red;options=bold>REMOVED   </>'
                : '<fg=yellow;options=bold>DEPRECATED</>';

            $this->line("  {$badge} <options=bold>{$first['label']}</> <fg=cyan>({$items->count()}×)</>");
            $this->line("             → <fg=green>{$first['replacement_title']}</>");
        }
    }

    private function generateReport(array $findings, array $paths): string
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $totalRemoved = collect($findings)->where('severity', 'removed')->count();
        $totalDeprecated = collect($findings)->where('severity', 'deprecated')->count();

        $md = [];
        $md[] = '# Square UI — Migratie Rapport';
        $md[] = '';
        $md[] = "_Gegenereerd op {$timestamp} door `php artisan square-ui:upgrade-check`._";
        $md[] = '';
        $md[] = '> Dit rapport is specifiek voor **deze installatie**. Voor algemene uitleg / mapping tables,';
        $md[] = '> zie `vendor/squareconcepts/square-ui/UPGRADE.md`.';
        $md[] = '';
        $md[] = '## Samenvatting';
        $md[] = '';
        $md[] = '| Categorie | Aantal |';
        $md[] = '|---|---|';
        $md[] = "| 🔴 Verwijderd (moet gemigreerd) | **{$totalRemoved}** |";
        $md[] = "| 🟡 Deprecated (aanbevolen) | **{$totalDeprecated}** |";
        $md[] = "| **Totaal** | **" . ($totalRemoved + $totalDeprecated) . "** |";
        $md[] = '';
        $md[] = 'Gescand: `' . implode('`, `', array_map(fn($p) => $this->relative($p), $paths)) . '`';
        $md[] = '';
        $md[] = '---';
        $md[] = '';
        $md[] = '## Afvinklijst';
        $md[] = '';
        $md[] = 'Doorloop onderstaande items één voor één. Vink ze af naarmate je ze migreert.';
        $md[] = '';

        $byFile = collect($findings)->sortBy([['severity', 'asc'], ['relative', 'asc'], ['line_number', 'asc']]);
        $counter = 0;

        foreach ($byFile as $finding) {
            $counter++;
            $icon = $finding['severity'] === 'removed' ? '🔴' : '🟡';

            $md[] = "### {$counter}. {$icon} `{$finding['label']}` — `{$finding['relative']}:{$finding['line_number']}`";
            $md[] = '';
            $md[] = '- [ ] Gemigreerd';
            $md[] = '';
            $md[] = '**Huidige code:**';
            $md[] = '';
            $md[] = '```' . $finding['language'];
            $md[] = trim($finding['line'], "\r\n");
            $md[] = '```';
            $md[] = '';

            if (!empty($finding['after'])) {
                $md[] = '**Vervangen door:**';
                $md[] = '';
                $md[] = '```' . $finding['language'];
                $md[] = $finding['after'];
                $md[] = '```';
                $md[] = '';
            }

            if (!empty($finding['hint'])) {
                $md[] = '> 💡 ' . $finding['hint'];
                $md[] = '';
            }

            if (!empty($finding['notes'])) {
                $md[] = '**Vergeet niet ook in dit bestand te migreren:**';
                $md[] = '';
                foreach ($finding['notes'] as $note) {
                    $md[] = '- ' . $note;
                }
                $md[] = '';
            }

            $md[] = '---';
            $md[] = '';
        }

        $md[] = '## Na afloop';
        $md[] = '';
        $md[] = '```bash';
        $md[] = '# Re-run de scan om te verifiëren dat alles gemigreerd is';
        $md[] = 'php artisan square-ui:upgrade-check';
        $md[] = '';
        $md[] = '# Clear caches';
        $md[] = 'php artisan view:clear && php artisan cache:clear';
        $md[] = '```';
        $md[] = '';
        $md[] = 'Wanneer de scan `✓ Geen issues gevonden` toont kan dit rapport verwijderd worden.';
        $md[] = '';

        return implode("\n", $md);
    }

    private function isCommentedOut(string $line, bool $isBlade): bool
    {
        $trimmed = ltrim($line);

        if ($isBlade) {
            return str_starts_with($trimmed, '{{--') || str_starts_with($trimmed, '<!--');
        }

        return str_starts_with($trimmed, '//')
            || str_starts_with($trimmed, '#')
            || str_starts_with($trimmed, '*')
            || str_starts_with($trimmed, '/*');
    }

    private function relative(string $path): string
    {
        $base = base_path() . DIRECTORY_SEPARATOR;
        return str_starts_with($path, $base) ? substr($path, strlen($base)) : $path;
    }

    private function buildRules(): array
    {
        return [
            [
                'key' => 'dropdown',
                'scope' => 'blade',
                'severity' => 'removed',
                'label' => '<x-square-ui.dropdown>',
                'pattern' => '/<x-square-ui\.dropdown\b[^>]*>/',
                'replacement_title' => '<flux:dropdown> + <flux:menu>',
                'template' => "<flux:dropdown>\n    <flux:button>Trigger</flux:button>\n    <flux:menu>\n        <flux:menu.item>Item</flux:menu.item>\n    </flux:menu>\n</flux:dropdown>",
                'hint' => 'Trigger als eerste kind van <flux:dropdown>, items in <flux:menu>.',
                'notes' => [],
            ],
            [
                'key' => 'alerts',
                'scope' => 'blade',
                'severity' => 'removed',
                'label' => '<x-square-ui.alerts::*>',
                'pattern' => '/<x-square-ui\.alerts::(\w+)\b[^>]*>/',
                'replacement_title' => '<flux:callout variant="...">',
                'transform' => function (string $line): string {
                    if (preg_match('/<x-square-ui\.alerts::(\w+)\b/', $line, $m)) {
                        $variant = match ($m[1]) {
                            'error' => 'danger',
                            'success' => 'success',
                            'warning' => 'warning',
                            'info' => 'info',
                            'secondary' => 'info',
                            default => 'info',
                        };
                        return "<flux:callout variant=\"{$variant}\" inline>\n    <flux:callout.heading>…message…</flux:callout.heading>\n</flux:callout>";
                    }
                    return '';
                },
                'hint' => 'Vervang <x-slot:message> door <flux:callout.heading>. Variants: error→danger, success→success, warning→warning, info/secondary→info.',
                'notes' => [],
            ],
            [
                'key' => 'color_picker',
                'scope' => 'blade',
                'severity' => 'removed',
                'label' => '<x-square-ui.color-picker>',
                'pattern' => '/<x-square-ui\.color-picker\b[^>]*>/',
                'replacement_title' => '<input type="color"> of <x-square-ui.pickr>',
                'template' => "<flux:field>\n    <flux:label>Kleur</flux:label>\n    <input type=\"color\" wire:model=\"color\" class=\"h-10 w-20 rounded border\">\n</flux:field>",
                'hint' => 'Voor swatches / opacity / HEX-input: gebruik de bestaande <x-square-ui.pickr> component.',
                'notes' => [],
            ],
            [
                'key' => 'wire_model_defer',
                'scope' => 'blade',
                'severity' => 'deprecated',
                'label' => 'wire:model.defer',
                'pattern' => '/wire:model\.defer/',
                'replacement_title' => 'wire:model',
                'transform' => fn(string $line): string => str_replace('wire:model.defer', 'wire:model', $line),
                'hint' => 'Livewire 3/4 maakt wire:model standaard deferred. Gebruik wire:model.live als je oude live-gedrag wil behouden.',
                'notes' => [],
            ],
            [
                'key' => 'swal_fire',
                'scope' => 'both',
                'severity' => 'removed',
                'label' => 'Swal.fire(...)',
                'pattern' => '/Swal\.fire\s*\(/',
                'replacement_title' => 'Flux toast via $wire.dispatch of $this->successNotification()',
                'template' => '$this->successNotification(\'Titel\', \'Bericht\');' . "\n" . '// of vanuit JS: $wire.successNotification(\'Titel\', \'Bericht\')',
                'hint' => 'SweetAlert2 is volledig verwijderd. Gebruik Flux toast (voor flash messages) of <flux:modal> (voor bevestigingen).',
                'notes' => [],
            ],
            [
                'key' => 'squareui_modals',
                'scope' => 'php',
                'severity' => 'removed',
                'label' => 'use SquareUiModals',
                'pattern' => '/use\s+Squareconcepts\\\\SquareUi\\\\Traits\\\\SquareUiModals\s*;/',
                'replacement_title' => 'use SquareUiActions',
                'template' => 'use Squareconcepts\SquareUi\Traits\SquareUiActions;',
                'hint' => 'SquareUiModals was SweetAlert2-gebaseerd en is volledig verwijderd. SquareUiActions biedt alle functionaliteit via Flux.',
                'notes' => [
                    'Zoek in dit bestand ook naar `use SquareUiModals` in een `use X, Y` statement — daar ook Actions van maken.',
                    'Vervang `$this->success($msg)` → `$this->successNotification($msg)`.',
                    'Vervang `$this->error($msg, $title)` → `$this->errorNotification($title, $msg)` (**parameters gewisseld!**).',
                    'Vervang `$this->info/warning/question(...)` → `$this->notification($heading, $text)`.',
                    'Vervang `$this->confirm($msg, $title, confirmButtonCallback: "foo")` → `$this->confirm(["title" => $title, "description" => $msg, "method" => "foo"])`.',
                    'Zorg dat `<livewire:square-ui::dialogs />` ergens in je layout staat (meestal in `<body>`).',
                ],
            ],
            [
                'key' => 'emit',
                'scope' => 'php',
                'severity' => 'removed',
                'label' => '$this->emit()',
                'pattern' => '/\\$this->emit(?:To|Self|Up)?\s*\(/',
                'replacement_title' => '$this->dispatch(...)',
                'transform' => function (string $line): string {
                    $line = preg_replace('/\\$this->emitTo\s*\(\s*([^,]+),\s*/', '$this->dispatch(', $line);
                    return preg_replace('/\\$this->emit(?:Self|Up)?\s*\(/', '$this->dispatch(', $line);
                },
                'hint' => 'emit/emitSelf/emitUp → dispatch(). Voor emitTo("component", "event", $data): gebruik $this->dispatch("event", $data)->to("component").',
                'notes' => [
                    'Als je `emitTo("comp", "event", $data)` gebruikte: pas de signature aan naar `dispatch("event", $data)->to("comp")`.',
                    'Parameters aan dispatch() moeten named of array zijn: `dispatch("event", userId: $id)`.',
                ],
            ],
            [
                'key' => 'dispatch_browser_event',
                'scope' => 'php',
                'severity' => 'removed',
                'label' => '$this->dispatchBrowserEvent()',
                'pattern' => '/\\$this->dispatchBrowserEvent\s*\(/',
                'replacement_title' => '$this->dispatch(...)',
                'transform' => fn(string $line): string => str_replace('$this->dispatchBrowserEvent(', '$this->dispatch(', $line),
                'hint' => 'Browser events gaan nu via dispatch(); vang op met `x-on:event-name.window="..."` in Alpine.',
                'notes' => [],
            ],
            [
                'key' => 'listeners_array',
                'scope' => 'php',
                'severity' => 'deprecated',
                'label' => 'protected $listeners = [...]',
                'pattern' => '/protected\s+\$listeners\s*=/',
                'replacement_title' => '#[On("event-name")] attribute op method',
                'template' => "use Livewire\\Attributes\\On;\n\n#[On('event-name')]\npublic function methodName(\$payload)\n{\n    // ...\n}",
                'hint' => 'Migreer elke listener naar een #[On] attribute op z\'n handler-method.',
                'notes' => [
                    'Importeer `use Livewire\\Attributes\\On;` bovenaan de class.',
                    'Verwijder daarna de hele `protected $listeners` array.',
                ],
            ],
        ];
    }
}

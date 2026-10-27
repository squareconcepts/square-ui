<?php

namespace Squareconcepts\SquareUi\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class ConvertLivewireClassComponentsToMFC extends Command
{
    protected $signature = 'square-ui:convert-to-mfc';
    protected $description = 'Converteer een traditionele Livewire class naar een MFC in de package';

    public function handle(): void
    {
        $livewirePath = app_path('Livewire');

        if (!File::exists($livewirePath)) {
            $this->error('Geen Livewire map gevonden in app/Livewire');
            return;
        }

        $files = (new Finder())->files()->in($livewirePath)->name('*.php');
        $options = [];

        foreach ($files as $file) {
            $relativePath = str_replace([$livewirePath . DIRECTORY_SEPARATOR, '.php'], ['', ''], $file->getRealPath());
            $options[$file->getRealPath()] = str_replace(DIRECTORY_SEPARATOR, '.', $relativePath);
        }

        $selectedPath = select(
            label: 'Welke Livewire class wil je converteren?',
            options: $options,
            scroll: 10
        );

        $className = $options[$selectedPath];
        $kebabName = Str::kebab(Str::afterLast($className, '.'));

        $targetSubDir = text(
            label: 'Waar moet de MFC geplaatst worden in de package?',
            default: "resources/views/test/livewire/{$kebabName}",
            hint: 'Relatief aan de root van je package'
        );

        $this->proceedWithConversion($selectedPath, $className, $targetSubDir);
    }

    protected function proceedWithConversion(string $classPath, string $className, string $targetSubDir): void
    {
        $targetPath = base_path(ltrim($targetSubDir, '/'));
        // Livewire-views zijn kebab-case per segment: Admin.UserList -> livewire/admin/user-list.blade.php
        $viewName = collect(explode('.', $className))->map(fn (string $part) => Str::kebab($part))->implode('/');
        $viewPath = resource_path('views/livewire/' . $viewName . '.blade.php');

        if (!File::isDirectory($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        $classContent = File::get($classPath);

        if (!preg_match('/^(?:(?:final|abstract|readonly)\s+)*class\s+\w+[^{]*\{/m', $classContent, $classMatch, PREG_OFFSET_CAPTURE)) {
            $this->error("Geen class-declaratie gevonden in {$classPath}");
            return;
        }

        $bodyStart = $classMatch[0][1] + strlen($classMatch[0][0]);
        $bodyEnd = strrpos($classContent, '}');

        // Alleen imports vóór de class: trait-`use` statements in de body horen bij de class zelf.
        preg_match_all('/^use\s+[\w\\\\]+(?:\s+as\s+\w+)?;/m', substr($classContent, 0, $classMatch[0][1]), $useMatches);
        $imports = $useMatches[0];
        if (!in_array('use Livewire\\Component;', $imports, true)) {
            $imports[] = 'use Livewire\\Component;';
        }

        $logic = $this->removeMethod(substr($classContent, $bodyStart, $bodyEnd - $bodyStart), 'render');

        $fileName = basename($targetPath);

        // Een MFC is een anonieme class: geen namespace. Classes uit de oude namespace die zonder `use`
        // werden aangesproken moeten na conversie expliciet geïmporteerd worden.
        $phpContent = "<?php\n\n" . implode("\n", $imports) . "\n\nnew class extends Component\n{" . $logic . "};\n";

        File::put("{$targetPath}/{$fileName}.php", $phpContent);

        if (File::exists($viewPath)) {
            File::copy($viewPath, "{$targetPath}/{$fileName}.blade.php");
            info("View gekopieerd van: {$viewPath}");
        } else {
            $this->warn("Geen view gevonden op {$viewPath}; kopieer de view handmatig.");
        }

        info("Succes! MFC aangemaakt in: {$targetSubDir}");
        // Namespaced Livewire-componenten resolven alleen met '::' (Livewire::addNamespace), niet met een punt.
        $this->line('Gebruik in Blade: <livewire:square-ui::' . basename($targetSubDir) . ' /> (registreer de map met Livewire::addNamespace(\'square-ui\', viewPath: ...))');
    }

    /**
     * Verwijdert een methode inclusief body door accolades te tellen (een regex stopt bij de eerste `}`).
     */
    protected function removeMethod(string $code, string $method): string
    {
        if (!preg_match('/^[ \t]*(?:#\[[^\]]*\]\s*)*(?:public\s+|protected\s+|private\s+)?function\s+' . preg_quote($method, '/') . '\s*\(/m', $code, $match, PREG_OFFSET_CAPTURE)) {
            return $code;
        }

        $start = $match[0][1];
        $open = strpos($code, '{', $start);

        if ($open === false) {
            return $code;
        }

        $depth = 0;
        $length = strlen($code);

        for ($i = $open; $i < $length; $i++) {
            if ($code[$i] === '{') {
                $depth++;
            } elseif ($code[$i] === '}') {
                $depth--;

                if ($depth === 0) {
                    return substr($code, 0, $start) . ltrim(substr($code, $i + 1), "\r\n");
                }
            }
        }

        return $code;
    }
}

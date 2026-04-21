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
        $kebabName = str($className)->afterLast('.')->lower();

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
        $viewPath = resource_path('views/livewire/' . str_replace('.', '/', Str::lower($className)) . '.blade.php');

        if (!File::isDirectory($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        $classContent = File::get($classPath);

        preg_match_all('/use\s+[\w\\\\]+;/', $classContent, $useMatches);
        $imports = implode("\n", $useMatches[0]);

        $startPos = strpos($classContent, '{');
        $endPos = strrpos($classContent, '}');
        $logic = ($startPos !== false) ? substr($classContent, $startPos + 1, ($endPos - $startPos) - 1) : '';

        $logic = preg_replace('/public function render\(.*?\).*?{.*?}/s', '', $logic);

        $fileName = basename($targetPath);
        $namespace = str_replace('-', '', Str::title($fileName));

        $phpContent = "<?php\n\nnamespace SquareConcepts\ScUi\Resources\Views\Livewire\\{$namespace};\n\n{$imports}\nuse Livewire\Component;\n\nnew class extends Component\n{" . $logic . '};';

        File::put("{$targetPath}/{$fileName}.php", $phpContent);

        if (File::exists($viewPath)) {
            File::copy($viewPath, "{$targetPath}/{$fileName}.blade.php");
            info("View gekopieerd van: {$viewPath}");
        }

        info("Succes! MFC aangemaakt in: {$targetSubDir}");
        $this->line('Gebruik in Blade: <livewire:square-ui.' . str_replace('/', '.', $targetSubDir) . ' />');
    }
}

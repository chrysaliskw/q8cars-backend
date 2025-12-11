<?php

namespace App\Console\Commands;

use App\Models\Car;
use App\Models\CarVersion;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class GenerateCarSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cars:generate-slugs {--dry-run : Preview without saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for cars AND car versions missing slug';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->generateCarSlugs($dryRun);

        $this->generateCarVersionSlugs($dryRun);

        $this->info("\nAll slugs generated successfully!");
        if ($dryRun) {
            $this->warn("Run without --dry-run to actually save slugs");
        }
    }

    private function generateCarSlugs($dryRun)
    {
        $cars = Car::whereNull('slug')
            ->orWhere('slug', '')
            ->get();

        $total = $cars->count();
        if ($total === 0) {
            $this->info("No cars need slugs");
            return;
        }

        $this->info("\nFound {$total} cars without slugs");

        $processed = 0;
        foreach ($cars as $car) {
            $processed++;

            $refNo = $car->car_ref_no ?: Str::random(6);
            $slug = $this->generateUniqueCarSlug($car->model_name, $refNo);

            $this->line("  Car '{$car->model_name}' → {$slug}");

            if (!$dryRun) {
                $car->slug = $slug;
                $car->save();
            }

            $this->output->write("\rCars processed: {$processed}/{$total}");
        }

        $this->info("\nProcessed {$processed} cars");
    }

    private function generateCarVersionSlugs($dryRun)
    {
        $versions = CarVersion::whereNull('slug')
            ->orWhere('slug', '')
            ->with('car')
            ->get();

        $total = $versions->count();
        if ($total === 0) {
            $this->info("No car versions need slugs");
            return;
        }

        $this->info("\nFound {$total} car versions without slugs");

        $processed = 0;
        foreach ($versions as $version) {
            $processed++;

            if ($version->car && $version->car->slug && $version->varient_name) {
                $slug = $version->car->slug . '-' . Str::slug($version->varient_name);

                $this->line("  Version '{$version->varient_name}' → {$slug}");

                if (!$dryRun) {
                    $version->slug = $slug;
                    $version->save();
                }
            } else {
                $this->warn("  Version ID {$version->id}: skipped (no car/slug/varient_name)");
            }

            $this->output->write("\rVersions processed: {$processed}/{$total}");
        }

        $this->info("\nProcessed {$processed} car versions");
    }

    private function generateUniqueCarSlug($modelName, $refNo)
    {
        $slug = Str::slug($modelName);
        $baseSlug = $slug . '-' . $refNo;

        $count = Car::where('slug', 'like', "{$slug}%")->count();

        if ($count > 0) {
            return $baseSlug . '-' . ($count + 1);
        }

        return $baseSlug;
    }
}

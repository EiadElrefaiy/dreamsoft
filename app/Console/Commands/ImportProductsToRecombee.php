<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests\SetItemValues;
use Illuminate\Support\Facades\DB;
use PDO;
use App\Services\AlgoliaImportService;

class ImportProductsToRecombee extends Command
{
    protected $signature = 'import:products';
    protected $description = 'Import products into Algolia';

    public function handle()
    {
        $importService = new AlgoliaImportService();
        $importService->importProducts();

        $this->info('Products imported successfully.');
    }
}

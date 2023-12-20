<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ProductsChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected static ?int $sort = 3;
    protected function getData(): array
    {
        $data = $this->getProductsPerMonth();

        return [
            'datasets' => [
                [
                    'label' => 'Liste de produits publiés',
                    'data' => $data['productsPerMonth'],
                ],
            ],
            'labels' => $data['months'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getProductsPerMonth(): array
    {
        // You can customize this method to retrieve data from your database or API.
        // For example, you could use Eloquent to fetch the number of published products for each month:
            $now = Carbon::now();
            $productsPerMonth = [];

            $months = collect(range(1,12))->map(function($month) use ($now, $productsPerMonth){
                $count = Product::whereMonth('created_at', Carbon::parse($now->month($month)->format('Y-m')))->count();
                $productsPerMonth[] = $count;

                return $now->month($month)->format('M');

            })->toArray();

            return [
                'productsPerMonth' => $productsPerMonth,
                'months' => $months
            ];
    }

}

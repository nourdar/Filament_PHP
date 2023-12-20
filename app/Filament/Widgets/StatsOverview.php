<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Customer;

class StatsOverview extends BaseWidget
{
    protected $getDataLabel = 'Clients';

    protected static ?string $pollingInterval = '10s';

    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Clients', Customer::count())
                ->chart([27, 22, 30, 53, 35, 4, 77])
                ->description('Augmentation de clients')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Total Produits', Product::count())
                ->chart([7, 2, 3, 3, 5, 4, 7])
                ->description('Nombre de produits publiés')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Commande en cours', Order::where('status', OrderStatus::PENDING->value)->count())
                ->chart([27, 22, 30, 53, 35, 14, 7])
                ->description('Baisse de commande')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

        ];
    }
}

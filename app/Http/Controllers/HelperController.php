<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductMesure;
use App\Models\Settings;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;

class HelperController extends Controller
{
    public static function get_product_name_from_form_record($record): string
    {

        if (!isset($record?->items[0])) {
            return '';
        }

        $product = '';

        $i = 1;

        foreach ($record->items as $item) {

            $product .= '<b>' . $item->product->name . '</b>';

            if (isset($item?->options[0])) {
                foreach ($item?->options[0] as $key => $value) {



                    if (is_string($value) && is_string($key)) {

                        $product .=  ' <br> ' . ' | <b><em>' . $key . ' :</em></b> ' . $value;
                    }
                }
            }

            if ($i < count($record->items)) {
                $product .= ' <hr> <span style="color:red; font-weight:600; font-size: 20px">+</span> ';
            }
            $i++;
        }

        return new HtmlString($product);
    }

    public static function get_livreurs_options(): array
    {
        $settings = Settings::first();

        if (empty($settings)) {
            return [];
        }

        $options = collect($settings['transport'])->pluck('provider', 'provider');

        return $options->toArray();
    }


    public static function get_image_src($image): string
    {

        if (file_exists('storage/' . $image)) {
            $src = asset('storage/' . $image);
        } else {
            $src = $image;
        }

        return $src;
    }
    public static function get_product_mesures_options($multiple = false): array
    {
        $columns = [];

        $mesures = ProductMesure::all();

        foreach ($mesures as $mesure) {

            $column =  Select::make($mesure->mesure)
                ->searchable()
                ->multiple($multiple)
                ->options(collect($mesure->options)->pluck('option', 'option'));

            array_push($columns, $column);
        }



        return $columns;
    }

    public static function product_mesures($product)
    {
        if (!empty($product['mesures'][0])) {

            return $product['mesures'][0];
        } elseif (!empty($product['mesures'])) {

            return $product['mesures'];
        }
    }
}

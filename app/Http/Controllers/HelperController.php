<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductMesure;
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

                    if (is_string($value[0]) && is_string($key)) {

                        $product .=  ' <br> ' . ' | <b><em>' . $key . ' :</em></b> ' . $value[0];
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

    public static function get_product_mesures_options(): array
    {
        $columns = [];

        $mesures = ProductMesure::all();

        foreach ($mesures as $mesure) {

            $column =  Select::make($mesure->mesure)
                ->searchable()
                ->options(collect($mesure->options)->pluck('option', 'option'));

            array_push($columns, $column);
        }

        return $columns;
    }
}

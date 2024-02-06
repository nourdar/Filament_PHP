<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use SoulDoit\SetEnv\Env;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\DeliveryCategory;
use FacebookAds\Object\ServerSide\UserData;


class FacebookController extends Controller
{

    public $fbPixel;
    public $eventId;
    public $userData;
    public $content;
    public $customData;

    public function __construct()
    {
        $settings = Settings::first();

        if (isset($settings['social_media'][0])) {

            config(['facebook-pixel.enabled' => $settings['social_media'][0]['facebook_pixel_enabled']]);
            config(['facebook-pixel.facebook_pixel_id' => $settings['social_media'][0]['facebook_pixel_id']]);
            config(['facebook-pixel.token' => $settings['social_media'][0]['facebook_pixel_token']]);
            config(['facebook-pixel.test_event_code' => $settings['social_media'][0]['facebook_pixel_test_event_code']]);
            // dd(config('facebook-pixel.token'));

            $this->fbPixel = new  \Combindma\FacebookPixel\FacebookPixel();

            $this->eventId = uniqid('prefix_');
        }
    }



    public function set_user_data($phone, $email)
    {
        $this->userData =  $this->fbPixel->userData()
            // ->setEmail($email ?? null)
            ->setPhone($phone ?? null);
        return $this;
    }

    public function set_content(int | string $productId, int $quantity, string $deliveryType)
    {
        $this->content = (new Content())
            ->setProductId($productId)
            ->setQuantity($quantity)
            ->setDeliveryCategory(($deliveryType == 'home ') ? DeliveryCategory::HOME_DELIVERY : DeliveryCategory::CURBSIDE);
        return $this;
    }
    public function set_custom_data(string | int $price)
    {
        $this->customData = (new CustomData())
            ->setContents(array($this->content))
            ->setCurrency('dzd')
            ->setValue($price);

        return $this;
    }

    public function send($eventName): void
    {
        // $this->set_content('33331', 12, 'home');
        // $this->set_custom_data(2222);
        // $this->set_user_data('0541891205', 'gachtoun@gmail.com');
        // dd($this);
        // $this->fbPixel->track($eventName, ['currency' => 'DZD', 'value' => 2200]);
        $this->fbPixel->send($eventName, $this->eventId, $this->customData, $this->userData);
    }


    public function simple_send($eventName): void
    {

        $this->eventId = uniqid('prefix_');
        $this->set_content('', 1, '');
        $this->set_custom_data('');

        $this->fbPixel->send($eventName, uniqid('prefix_'), $this->customData);
    }
}

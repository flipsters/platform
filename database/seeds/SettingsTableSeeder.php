<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('settings')->delete();

        \DB::table('settings')->insert(array (
            0 =>
            array (
                'id' => 1,
                'key' => 'page_name',
                'name' => 'Page Name',
                'description' => 'Name of the page',
                'value' => 'GamePort',
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'general',
                'reorder' => 1,
                'created_at' => NULL,
                'updated_at' => '2017-01-09 13:36:33',
            ),
            1 =>
            array (
                'id' => 2,
                'key' => 'meta_description',
                'name' => 'Meta Description',
                'description' => 'Meta Description',
                'value' => 'GamePort is the best place to sell, buy or trade your favourite video games. Join thousands of gamers from all over the world!',
                'field' => '{"name":"value","label":"Value","type":"textarea"}',
                'active' => 1,
                'category' => 'general',
                'reorder' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'key' => 'contact_email',
                'name' => 'Contact eMail',
                'description' => 'Contact eMail',
                'value' => 'admin@gameport.com',
                'field' => '{"name":"value","label":"Value","type":"email"}',
                'active' => 1,
                'category' => 'general',
                'reorder' => 4,
                'created_at' => NULL,
                'updated_at' => '2017-01-09 17:04:50',
            ),
            3 =>
            array (
                'id' => 4,
                'key' => 'logo',
                'name' => 'Logo',
                'description' => 'Logo',
                'value' => 'img/logo.png',
                'field' => '{"name":"value","label":"Value","type":"image_settings","upload":"true","hint":"min-height: 80px, transparent background. Retina logo will be generated automatically."}',
                'active' => 1,
                'category' => 'design',
                'reorder' => 1,
                'created_at' => NULL,
                'updated_at' => '2017-01-19 22:39:51',
            ),
            4 =>
            array (
                'id' => 5,
                'key' => 'css',
                'name' => 'Additional CSS',
                'description' => 'css',
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"textarea"}',
                'active' => 1,
                'category' => 'design',
                'reorder' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'key' => 'currency',
                'name' => 'Currency',
                'description' => 'currency',
                'value' => 'EUR',
                'field' => '{"name":"value","label":"Value","type":"select_money"}',
                'active' => 2,
                'category' => 'localization',
                'reorder' => 1,
                'created_at' => NULL,
                'updated_at' => '2017-01-19 22:31:01',
            ),
            6 =>
            array (
                'id' => 7,
                'key' => 'giantbomb_key',
                'name' => 'Giantbomb API Key',
                'description' => 'Giantbomb API Key',
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'game',
                'reorder' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 =>
            array (
                'id' => 8,
                'key' => 'automatic_genres',
                'name' => 'Add new genres automatically',
                'description' => 'Add new genres automatically',
                'value' => '1',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'game',
                'reorder' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 =>
            array (
                'id' => 9,
                'key' => 'date_format',
                'name' => 'Date Format',
                'description' => 'Date Format',
                'value' => 'Y-m-d',
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'localization',
                'reorder' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 =>
            array (
                'id' => 10,
                'key' => 'time_format',
                'name' => 'Time Format',
                'description' => 'Time Format',
                'value' => 'H:i:s',
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'localization',
                'reorder' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 =>
            array (
                'id' => 11,
                'key' => 'facebook_client_id',
                'name' => 'Facebook App-ID',
                'description' => 'facebook_client_id',
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'auth',
                'reorder' => 3,
                'created_at' => NULL,
                'updated_at' => '2017-01-17 19:11:47',
            ),
            11 =>
            array (
                'id' => 12,
                'key' => 'facebook_client_secret',
                'name' => 'Facebook App Secret-Key',
                'description' => 'facebook_client_secret',
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'auth',
                'reorder' => 4,
                'created_at' => NULL,
                'updated_at' => '2017-01-17 19:10:16',
            ),
            12 =>
            array (
                'id' => 13,
                'key' => 'user_confirmation',
                'name' => 'User need to confirm eMail',
                'description' => 'user_confirmation',
                'value' => '1',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'auth',
                'reorder' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 =>
            array (
                'id' => 14,
                'key' => 'twitter_client_id',
                'name' => 'Twitter Consumer Key',
                'description' => 'twitter_client_id',
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'auth',
                'reorder' => 6,
                'created_at' => NULL,
                'updated_at' => '2017-01-17 18:48:20',
            ),
            14 =>
            array (
                'id' => 15,
                'key' => 'twitter_client_secret',
                'name' => 'Twitter Secret Key',
                'description' => 'twitter_client_secret',
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'auth',
                'reorder' => 7,
                'created_at' => NULL,
                'updated_at' => '2017-01-17 18:48:20',
            ),
            15 =>
            array (
                'id' => 16,
                'key' => 'google_client_id',
                'name' => 'Google Client-ID',
                'description' => 'google_client_id',
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'auth',
                'reorder' => 9,
                'created_at' => NULL,
                'updated_at' => '2017-01-17 19:25:11',
            ),
            16 =>
            array (
                'id' => 17,
                'key' => 'google_client_secret',
                'name' => 'Google Secret-ID',
                'description' => 'google_client_secret',
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'auth',
                'reorder' => 10,
                'created_at' => NULL,
                'updated_at' => '2017-01-17 19:25:11',
            ),
            17 =>
            array (
                'id' => 18,
                'key' => 'facebook_auth',
                'name' => 'Enable Facebook Auth',
                'description' => 'facebook_auth',
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'auth',
                'reorder' => 2,
                'created_at' => NULL,
                'updated_at' => '2017-01-19 22:31:09',
            ),
            18 =>
            array (
                'id' => 19,
                'key' => 'twitter_auth',
                'name' => 'Enable Twitter Auth',
                'description' => 'twitter_auth',
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'auth',
                'reorder' => 5,
                'created_at' => NULL,
                'updated_at' => '2017-01-17 19:21:27',
            ),
            19 =>
            array (
                'id' => 20,
                'key' => 'google_auth',
                'name' => 'Enable Google Auth',
                'description' => 'google_auth',
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'auth',
                'reorder' => 8,
                'created_at' => NULL,
                'updated_at' => '2017-01-17 19:21:21',
            ),
            20 =>
            array (
                'id' => 21,
                'key' => 'sub_title',
                'name' => 'Sub Title',
                'description' => 'Sub Title',
                'value' => 'Trade, sell or buy your favourite video games!',
                'field' => '{"name":"value","label":"Value","type":"text"}
',
                'active' => 1,
                'category' => 'general',
                'reorder' => 2,
                'created_at' => NULL,
                'updated_at' => '2017-01-18 18:29:13',
            ),
            21 =>
            array (
                'id' => 22,
                'key' => 'facebook_link',
                'name' => 'Facebook Link',
                'description' => NULL,
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"text"}
',
                'active' => 1,
                'category' => 'design',
                'reorder' => 5,
                'created_at' => NULL,
                'updated_at' => '2017-01-21 21:20:03',
            ),
            22 =>
            array (
                'id' => 23,
                'key' => 'twitter_link',
                'name' => 'Twitter Link',
                'description' => NULL,
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"text"}
',
                'active' => 1,
                'category' => 'design',
                'reorder' => 6,
                'created_at' => NULL,
                'updated_at' => '2017-01-21 21:20:13',
            ),
            23 =>
            array (
                'id' => 24,
                'key' => 'google_plus_link',
                'name' => 'Google Plus Link',
                'description' => NULL,
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'design',
                'reorder' => 7,
                'created_at' => NULL,
                'updated_at' => '2017-01-21 21:20:13',
            ),
            24 =>
            array (
                'id' => 25,
                'key' => 'youtube_link',
                'name' => 'YouTube Link',
                'description' => NULL,
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'design',
                'reorder' => 8,
                'created_at' => NULL,
                'updated_at' => '2017-01-21 21:20:13',
            ),
            25 =>
            array (
                'id' => 26,
                'key' => 'favicon',
                'name' => 'Favicon',
                'description' => NULL,
                'value' => 'img/favicon-32x32.png',
                'field' => '{"name":"value","label":"Value","type":"image_settings","upload":"true","hint":"32x32 png, transparent background allowed. 16x16 icon will be generated automatically."}',
                'active' => 1,
                'category' => 'design',
                'reorder' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 =>
            array (
                'id' => 27,
                'key' => 'default_locale',
                'name' => 'Default Language',
                'description' => 'test',
                'value' => 'en',
                'field' => '{"name":"value","label":"Value","type":"select2_languages"}',
                'active' => 1,
                'category' => 'localization',
                'reorder' => 5,
                'created_at' => NULL,
                'updated_at' => '2017-01-22 13:39:51',
            ),
            27 =>
            array (
                'id' => 28,
                'key' => 'platform_logo',
                'name' => 'Use platform logos instead of simple text',
                'description' => NULL,
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle","hint":"Must Have ZIP File Uploaded."}',
                'active' => 1,
                'category' => 'game',
                'reorder' => 3,
                'created_at' => NULL,
                'updated_at' => '2017-01-22 13:12:57',
            ),
            28 =>
            array (
                'id' => 29,
                'key' => 'js',
                'name' => 'Additional JS',
                'description' => 'JS',
                'value' => NULL,
                'field' => '{"name":"value","label":"Value","type":"textarea"}',
                'active' => 1,
                'category' => 'design',
                'reorder' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 =>
            array (
                'id' => 30,
                'key' => 'locale_selector',
                'name' => 'Locale Selector',
                'description' => 'Locale Selector',
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle","hint":"User can select language in frontend."}',
                'active' => 1,
                'category' => 'localization',
                'reorder' => 4,
                'created_at' => NULL,
                'updated_at' => '2017-01-22 13:40:08',
            ),
            30 =>
            array (
                'id' => 31,
                'key' => 'frontpage_carousel',
                'name' => 'New releases carousel on frontpage',
                'description' => 'New releases carousel on frontpage',
                'value' => '1',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'design',
                'reorder' => 9,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 =>
            array (
                'id' => 32,
                'key' => 'google_maps_key',
                'name' => 'Google Maps API Key',
                'description' => 'Google Maps API Key',
                'value' => '',
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'localization',
                'reorder' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 =>
            array (
                'id' => 33,
                'key' => 'frontpage_carousel_day',
                'name' => 'Release in max',
                'description' => 'Release in max',
                'value' => '21',
                'field' => '{"name":"value","label":"Value","type":"number","suffix":"Days","hint":"This change can take a few minutes."}',
                'active' => 1,
                'category' => 'design',
                'reorder' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 =>
            array (
                'id' => 34,
                'key' => 'google_adsense',
                'name' => 'Enable Google AdSense',
                'description' => 'Enable Google AdSense',
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'ads',
                'reorder' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 =>
            array (
                'id' => 35,
                'key' => 'google_adsense_code',
                'name' => 'Google AdSense Code',
                'description' => 'Google AdSense Code',
                'value' => '',
                'field' => '{"name":"value","label":"Value","type":"textarea","hint":"Best results with responsive ads"}',
                'active' => 1,
                'category' => 'ads',
                'reorder' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 =>
            array (
                'id' => 36,
                'key' => 'buy_button_ref',
                'name' => 'Enable Buy Buttton Ref Link',
                'description' => 'Enable Buy Buttton Ref Link',
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'ads',
                'reorder' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 =>
            array (
                'id' => 37,
                'key' => 'buy_button_ref_merchant',
                'name' => 'Buy Button Merchant',
                'description' => 'Buy Button Merchant',
                'value' => '',
                'field' => '{"name":"value","label":"Value","type":"text","hint":"For example: Amazon"}',
                'active' => 1,
                'category' => 'ads',
                'reorder' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 =>
            array (
                'id' => 38,
                'key' => 'buy_button_ref_link',
                'name' => 'Buy Button Link',
                'description' => 'Buy Button Link',
                'value' => '',
                'field' => '{"name":"value","label":"Value","type":"text","hint":"Add %game_name% to include current game name in the link"}',
                'active' => 1,
                'category' => 'ads',
                'reorder' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 =>
            array (
                'id' => 39,
                'key' => 'script_version',
                'name' => 'script_version',
                'description' => 'script_version',
                'value' => '1.2',
                'field' => '',
                'active' => 1,
                'category' => '',
                'reorder' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 =>
            array (
                'id' => 40,
                'key' => 'location_api',
                'name' => 'Location API',
                'description' => 'location_api',
                'value' => 'zippopotam',
                'field' => '{"name":"value","label":"Value","type":"select2_location_api","hint":"Location API for the location feature."}',
                'active' => 1,
                'category' => 'localization',
                'reorder' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 =>
            array (
                'id' => 41,
                'key' => 'picture_upload',
                'name' => 'Enable Picture Upload',
                'description' => 'picture_upload',
                'value' => '1',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'listing',
                'reorder' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 =>
            array (
                'id' => 42,
                'key' => 'watermark',
                'name' => 'Picture Watermark',
                'description' => 'watermark',
                'value' => 'img/watermark.png',
                'field' => '{"name":"value","label":"Value","type":"image_settings","upload":"true","hint":"png with transparent background."}',
                'active' => 1,
                'category' => 'listing',
                'reorder' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 =>
            array (
                'id' => 43,
                'key' => 'ssl',
                'name' => 'Force HTTPS (SSL)',
                'description' => 'ssl',
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'general',
                'reorder' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),



            43 =>
            array (
                'id' => 44,
                'key' => 'decimal_place',
                'name' => 'Show decimal places on even numbers',
                'description' => 'decimal_place',
                'value' => '1',
                'field' => '{"name":"value","label":"Value","type":"toggle","hint":"Example for disabled option: 16.00 will be displayed as 16"}',
                'active' => 1,
                'category' => 'listing',
                'reorder' => '3',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 =>
            array (
                'id' => 45,
                'key' => 'recaptcha_register',
                'name' => 'Enable invisible reCAPTCHA for registration',
                'description' => 'recaptcha_register',
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 11,
                'category' => 'auth',
                'reorder' => '11',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 =>
            array (
                'id' => 46,
                'key' => 'recaptcha_secret',
                'name' => 'reCAPTCHA Secret',
                'description' => 'recaptcha_secret',
                'value' => '',
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'auth',
                'reorder' => '12',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 =>
            array (
                'id' => 47,
                'key' => 'recaptcha_sitekey',
                'name' => 'reCAPTCHA Site Key',
                'description' => 'recaptcha_sitekey',
                'value' => '',
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'auth',
                'reorder' => '13',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 =>
            array (
                'id' => 48,
                'key' => 'distance_unit',
                'name' => 'Distance unit',
                'description' => 'distance_unit',
                'value' => 'km',
                'field' => '{"name":"value","label":"Value","type":"select_from_array","options":{"km":"Kilometer (km)","mi":"Mile (mi)","nm":"Nautical mile (nm)"},"hint":"Unit for the distance between user and listing."}',
                'active' => 1,
                'category' => 'listing',
                'reorder' => '4',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 =>
            array (
                'id' => 49,
                'key' => 'payment',
                'name' => 'Enable Payment System',
                'description' => 'payment',
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'payment',
                'reorder' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 =>
            array (
                'id' => 50,
                'key' => 'variable_fee',
                'name' => 'Variable fee',
                'description' => 'variable_fee',
                'value' => '8',
                'field' => '{"name":"value","label":"Value","type":"number","hint":"Fee for successful transaction in percentage","prefix":"%"}',
                'active' => 1,
                'category' => 'payment',
                'reorder' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 =>
            array (
                'id' => 51,
                'key' => 'fixed_fee',
                'name' => 'Fixed fee',
                'description' => 'fixed_fee',
                'value' => '0.35',
                'field' => '{"name":"value","label":"Value","type":"text","hint":"[Format: 0.00] Fixed fee for successful transaction in your site currency","prefix":"site_currency"}',
                'active' => 1,
                'category' => 'payment',
                'reorder' => '3',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 =>
            array (
                'id' => 52,
                'key' => 'paypal_sandbox',
                'name' => 'PayPal Sandbox Mode',
                'description' => 'paypal_sandbox',
                'value' => '1',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'payment',
                'reorder' => '4',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 =>
            array (
                'id' => 53,
                'key' => 'paypal_client_id',
                'name' => 'PayPal Client-ID',
                'description' => 'paypal_client_id',
                'value' => '',
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'payment',
                'reorder' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 =>
            array (
                'id' => 54,
                'key' => 'paypal_client_secret',
                'name' => 'PayPal Secret Key',
                'description' => 'paypal_client_secret',
                'value' => '',
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'payment',
                'reorder' => '6',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 =>
            array (
                'id' => 55,
                'key' => 'comment_game',
                'name' => 'Game Comments',
                'description' => 'comment_game',
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'comment',
                'reorder' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 =>
            array (
                'id' => 56,
                'key' => 'comment_listing',
                'name' => 'Listing Comments',
                'description' => 'comment_listing',
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'comment',
                'reorder' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 =>
            array (
                'id' => 57,
                'key' => 'comment_article',
                'name' => 'Article Comments',
                'description' => 'comment_article',
                'value' => '0',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'comment',
                'reorder' => '3',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 =>
            array (
                'id' => 58,
                'key' => 'comment_max_page',
                'name' => 'Max comments per page',
                'description' => 'comment_max_page',
                'value' => '15',
                'field' => '{"name":"value","label":"Value","type":"number"}',
                'active' => 1,
                'category' => 'comment',
                'reorder' => '4',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 =>
            array (
                'id' => 59,
                'key' => 'comment_throttle',
                'name' => 'Throttle flood',
                'description' => 'comment_throttle',
                'value' => '20',
                'field' => '{"name":"value","label":"Value","type":"number","hint":"Time in seconds between two comments for a user.","prefix":"sec"}',
                'active' => 1,
                'category' => 'comment',
                'reorder' => '5',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 =>
            array (
                'id' => 60,
                'key' => 'locate_position',
                'name' => 'Locate guest position for distance to listing',
                'description' => 'locate_position',
                'value' => '1',
                'field' => '{"name":"value","label":"Value","type":"toggle"}',
                'active' => 1,
                'category' => 'localization',
                'reorder' => '6',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 =>
            array (
                'id' => 61,
                'key' => 'instagram_link',
                'name' => 'Instagram Link',
                'description' => 'instagram_link',
                'value' => '',
                'field' => '{"name":"value","label":"Value","type":"text"}',
                'active' => 1,
                'category' => 'design',
                'reorder' => '8',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}

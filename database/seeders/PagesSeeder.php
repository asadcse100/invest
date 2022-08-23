<?php

namespace Database\Seeders;

use App\Enums\Boolean;
use App\Enums\PageStatus;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    private $slugKeyMap = [
        'main_nav' => [
            'about-us',
            'contact-us',
            'faqs' ,
        ],
        'footer_menu' => [
            'terms-and-condition',
            'privacy-policy',
            'faqs'
        ],
        'page_terms' => 'terms-and-condition',
        'page_privacy' => 'privacy-policy',
        'page_contact' => 'contact-us',
    ];

    public function run()
    {
        $pages = [
            'about-us' => [
                'name' => 'About Us',
                'slug' => 'about-us',
                'menu_name' => 'About',
                'title' => 'About the platform',
                'content' => "<h4>About the Company</h4>\n<p><strong>[[site_name]]</strong> work in the field of financing promising developments on cryptocurrency market and with blockchain technology. According to experts, blockchain technologies currently have great opportunity. Lots of business ideas related to blockchain technologies become more successful and every day by day it bring high profits to their creators.</p>\n<p>We track and analyze most business ideas. It allows us to get high profits. For our investor do not need to research independently in which project it is more profitable. So our investor can invest their capital and then receive an interest on the profit.</p>\n<h4>Investment</h4>\n<p>We invest in projects at an early stage, in particular, it can be business ideas, investing in startups at various stages of their development, ICO (Initial Coin Offering), IEO (Initial Exchange Offering).</p>",
                'trash' => Boolean::NO,
                'status' => PageStatus::ACTIVE,
                'public' => Boolean::YES,
            ],
            'fees' => [
                'name' => 'Our Fees',
                'slug' => 'fees',
                'menu_name' => 'Fees',
                'title' => 'Our Fees',
                'trash' => Boolean::NO,
                'status' => PageStatus::INACTIVE,
                'public' => Boolean::YES,
            ],
            'referral' => [
                'name' => 'Referral',
                'slug' => 'referral',
                'menu_name' => 'Referral',
                'title' => 'My Referral',
                'trash' => Boolean::NO,
                'status' => PageStatus::INACTIVE,
                'public' => Boolean::YES,
            ],
            'faqs' => [
                'name' => 'Frequently Asked Questions',
                'slug' => 'faqs',
                'menu_name' => 'FAQs',
                'title' => 'Frequently Asked Questions',
                'content' => "<h4>How can we help you?</h4>\n<p>Do You have any questions? We strongly recommend that you start searching for the necessary information in the FAQ section.</p>\n<h5>What is [[site_name]] company?</h5>\n<p>[[site_name]] platform is an international investment company. The activity of our company is aimed at the cryptocurrency trading, forex, stocks and providing investment services worldwide.</p>\n<h5>How to create an account?</h5>\n<p>The registration process on the website is quite simple. You need to fill out the fields of the registration form, which include full name, email address and password.</p>\n<h5>Which payment methods do you accept?</h5>\n<p>At the moment we work with PayPal, Wire Transfer, Bitcoin, Ethereum, Litecoin, Binance Coin.</p>\n<h5>I want to reinvest the funds received, is it possible?</h5>\n<p>Of course. You have the right to reinvesting your profits again and again.</p>",
                'trash' => Boolean::NO,
                'status' => PageStatus::ACTIVE,
                'public' => Boolean::YES,
            ],
            'contact-us' => [
                'name' => 'Contact Us',
                'slug' => 'contact-us',
                'menu_name' => 'Contact',
                'title' => 'Contact Us',
                'content' => "<h4>Get In Touch</h4>\n<p>If you need advice or have any question in mind or technical assistance, do not hesitate to contact our specialists.</p>\n<p><strong>Email Address:</strong> [[site_email]]</p>",
                'trash' => Boolean::NO,
                'status' => PageStatus::ACTIVE,
                'public' => Boolean::YES,
            ],
            'terms-and-condition' => [
                'name' => 'Terms and Condition',
                'slug' => 'terms-and-condition',
                'menu_name' => 'Terms and Condition',
                'title' => 'Terms and Condition',
                'content' => "<h4>Terms and condition</h4>\n<p>Welcome to [[site_name]]!</p>\n<p>These terms and conditions outline the rules and regulations for the use of [[site_name]]'s Website.</p>\n<p>By accessing this website we assume you accept these terms and conditions. Do not continue to use [[site_name]] if you do not agree to take all of the terms and conditions stated on this page.</p>\n<p>If you have additional questions or require more information, do not hesitate to contact us through email at [[site_email]].</p>",
                'trash' => Boolean::NO,
                'status' => PageStatus::ACTIVE,
                'public' => Boolean::YES,
            ],
            'privacy-policy' => [
                'name' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'menu_name' => 'Privacy Policy',
                'title' => 'Privacy Policy',
                'content' => "<h4>Privacy Policy for [[site_name]].</h4>\n<p>At <strong>[[site_name]]</strong>, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by <strong>[[site_name]]</strong> and how we use it.</p>\n<p>If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us through email at [[site_email]].</p>",
                'trash' => Boolean::NO,
                'status' => PageStatus::ACTIVE,
                'public' => Boolean::YES,
            ]
        ];

        foreach ($pages as $slug => $page) {
            $exist = Page::where('slug', $slug)->count();
            if ($exist <= 0) {
                $page = Page::create($page);
                foreach ($this->slugKeyMap as $key => $item) {
                    if (is_array($item) && in_array($page->slug, $item) ) {
                        $value = gss($key);
                        if (is_array($value)) {
                            $value = array_merge($value, [$page->id]);
                            Setting::updateOrCreate(['key' => $key],['value' => json_encode($value)]);
                        }
                    }

                    if ($item == $page->slug) {
                        Setting::updateOrCreate(['key' => $key],['value' => $page->id]);
                    }
                }
            }
        }
    }
}

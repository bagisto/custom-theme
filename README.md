### Introduction

Our improved theme design enhances the look and feel of the store’s interface. By giving users complete control over themes, we enable seamless customization. This feature allows users to add images, text, and links to any section of their store with ease.

### Compatibility

This Custom package is compatible with version Bagisto 2.0 

### Installation:

* Unzip the respective extension zip and then merge the "packages" folder into the project root directory.
* Goto config/app.php file and add the following line under 'providers'

~~~
Webkul\Store\Providers\StoreServiceProvider::class
~~~

* Goto composer.json file and add the following line under 'psr-4'

~~~
"Webkul\\Store\\": "packages/Webkul/Store/src"
~~~

* Run these commands in the root of Bagisto

~~~
composer dump-autoload
~~~

~~~
php artisan optimize:clear
~~~

~~~
-> php artisan vendor:publish --force 
--provider="Webkul\Store\Providers\StoreServiceProvider"
~~~

* Run these commands in the Store package

~~~
npm install
~~~

~~~
npm run dev
~~~

~~~
npm run build
~~~

For Custom theme
~~~
Admin->Settings->Channels->Edit Channel->Themes-> select new theme 
~~~

* For step-by-step installation follow this URL

[CustomTheme](https://bagisto.com/en/create-custom-theme-in-bagisto/)
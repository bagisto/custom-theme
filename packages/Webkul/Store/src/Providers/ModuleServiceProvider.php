<?php

namespace Webkul\Store\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Define the models
     *
     * @var array
     */
    protected $models = [
        \Webkul\Store\Models\ThemeCustomization::class,
        \Webkul\Store\Models\ThemeCustomizationTranslation::class,
    ];
}
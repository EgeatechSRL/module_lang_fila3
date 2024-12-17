<?php

declare(strict_types=1);

namespace Modules\Lang\Providers;

use Filament\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Infolists\Components\Entry;
use Filament\Support\Components\Component;
use Filament\Support\Concerns\Configurable;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\View;
use Modules\Lang\Actions\Filament\AutoLabelAction;
use Modules\Lang\Services\TranslatorService;
use Modules\Xot\Providers\XotBaseServiceProvider;
use Modules\Xot\Services\BladeService;
use Webmozart\Assert\Assert;

/**
 * Undocumented class.
 */
class LangServiceProvider extends XotBaseServiceProvider
{
    public string $module_name = 'lang';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();
        // BladeService::registerComponents($this->module_dir.'/../View/Components', 'Modules\\Lang');
        // $this->registerTranslator();
        $this->translatableComponents();
        $this->registerFilamentLabel();
    }

    public function register(): void
    {
        parent::register();
        // --dalla doc in register ... ma non funziona, funziona in boot
        // $this->registerTranslator();
    }

    protected function translatableComponents(): void
    {
        $components = [Field::class, BaseFilter::class, Placeholder::class, Column::class, Entry::class];
        foreach ($components as $component) {
            /* @var Configurable $component */
            $component::configureUsing(function (Component $translatable): void {
                /* @phpstan-ignore method.notFound */
                $translatable->translateLabel();
            });
        }
    }

    public function registerFilamentLabel(): void
    {
        Field::configureUsing(function (Field $component) {
            $component = app(AutoLabelAction::class)->execute($component);
            Assert::isInstanceOf($component, Field::class);
            $component->validationMessages(__('user::validation'));

            return $component;
        });

        BaseFilter::configureUsing(function (BaseFilter $component) {
            $component = app(AutoLabelAction::class)->execute($component);

            return $component;
        });

        Column::configureUsing(function (Column $component) {
            $component = app(AutoLabelAction::class)->execute($component);
            Assert::isInstanceOf($component, Column::class);
            // $component = $component
            //         ->wrapHeader()
            //         ->verticallyAlignStart()
            //         ->grow()
            //         // ->wrap()
            // ;

            return $component;
        });
        Step::configureUsing(function (Step $component) {
            $component = app(AutoLabelAction::class)->execute($component);

            // ->translateLabel()
            return $component;
        });
        Action::configureUsing(function (Action $component) {
            $component = app(AutoLabelAction::class)->execute($component);

            // ->translateLabel()
            return $component;
        });
        // Method Filament\Widgets\StatsOverviewWidget\Stat::configureUsing does not exist.
        /*
        Stat::configureUsing(function (Stat $component) {
            $component = app(AutoLabelAction::class)->execute($component);

            // ->translateLabel()
            return $component;
        });
        */
    }

    public function registerTranslator(): void
    {
        $this->app->singleton('translator', function (Container $app): TranslatorService {
            $loader = $app['translation.loader'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            Assert::string($locale = $app['config']['app.locale']);
            Assert::string($fallback_locale = $app['config']['app.fallback_locale']);

            $translatorService = new TranslatorService($loader, $locale);

            $translatorService->setFallback($fallback_locale);

            /*
            if($app->bound('translation-manager')){
                $trans->setTranslationManager($app['translation-manager']);
            }
            */
            return $translatorService;
        });
    }
}

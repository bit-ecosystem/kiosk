<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center justify-between gap-x-2">
            <div class="flex items-center gap-x-2">
                <a href="http://mwkyckapd01.my.ds.amkor.com:8080/realms/ATM/account/" rel="noopener noreferrer"
                    target="_blank">
                    <x-filament-panels::avatar.user size="lg" :user="$user" />
                </a>

                <div class="flex flex-col">
                    <h2 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        {{ __('filament-panels::widgets/account-widget.welcome', ['app' => config('app.name')]) }}
                    </h2>
                    <p class="text-sm font-bold text-gray-500 dark:text-gray-400">
                        {{ session('cu.name') }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <br>as {{ session('cu.jobtitle') }}
                        <br>in {{ session('cu.department') }}
                        <br>{{ session('cu.email') }}
                    </p>
                </div>
            </div>
            <div class="flex flex-col justify-end gap-y-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <x-filament::link color="gray" href="http://atmportal/home.asp"
                        icon="heroicon-m-globe-asia-australia" rel="noopener noreferrer" target="_blank">
                        To ATM Portal
                    </x-filament::link>
                </p>
                <form action="{{ filament()->getLogoutUrl() }}" method="post" class="my-auto">
                    @csrf

                    <x-filament::button color="gray" icon="heroicon-m-arrow-left-on-rectangle"
                        icon-alias="panels::widgets.account.logout-button" labeled-from="sm" tag="button"
                        type="submit">
                        {{ __('filament-panels::widgets/account-widget.actions.logout.label') }}
                    </x-filament::button>
                </form>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

<x-filament-panels::page>

    @php
        $composer = \Modules\Heartbeat\Dto\ComposerInformation::make()->configuration()->lockfile();
        $git = \Modules\Heartbeat\Dto\GitInformation::make()->discover();
    @endphp

    <div class="flex flex-col gap-4">

        {{-- Project information --}}
        <x-filament::section compact collapsible icon="heroicon-o-server-stack">
            <x-slot name="heading">
                Laravel {{ Illuminate\Foundation\Application::VERSION }} (PHP {{ PHP_VERSION }})
            </x-slot>
            <x-slot name="description">
                {{ PHP_OS_FAMILY }}, {{ PHP_OS }}
            </x-slot>
            <div class="flex flex-col gap-2 w-full">

                <p class="flex flex-row gap-2 w-full">
                    <strong>Branch</strong>
                    <span>{{ $git->branch }}</span>
                </p>

                <p class="flex flex-row gap-2 w-full">
                    <strong>Head</strong>
                    <span>{{ $git->head }}</span>
                </p>

                <p class="flex flex-row gap-2 w-full">
                    <strong>Modified at</strong>
                    <span x-tooltip="{content: '{{ $git->headModifiedAt->timestamp }}'}">
                        {{ $git->headModifiedAt->format('Y.m.d. H:i:s') }}
                    </span>
                </p>

            </div>
        </x-filament::section>

        {{-- Composer configuration --}}
        <x-filament::section compact collapsible icon="heroicon-o-cog-6-tooth">
            <x-slot name="heading">
                composer.json
            </x-slot>
            <div class="flex flex-col gap-4">

                <x-filament::section compact collapsible collapsed>
                    <x-slot name="heading">
                        <div class="flex gap-x-3">
                            <x-filament::badge>{{ $composer->require->count() }}</x-filament::badge>
                            <span>require</span>
                        </div>
                    </x-slot>
                    @foreach($composer->require as $require)
                        <p class="flex flex-row gap-2 w-full">
                            <strong>{{ $require->name }}</strong>
                            <span>{{ $require->version }}</span>
                        </p>
                    @endforeach
                </x-filament::section>

                <x-filament::section compact collapsible collapsed>
                    <x-slot name="heading">
                        <div class="flex gap-x-3">
                            <x-filament::badge>{{ $composer->requireDev->count() }}</x-filament::badge>
                            <span>require-dev</span>
                        </div>
                    </x-slot>
                    @foreach($composer->requireDev as $requireDev)
                        <p class="flex flex-row gap-2 w-full">
                            <strong>{{ $requireDev->name }}</strong>
                            <span>{{ $requireDev->version }}</span>
                        </p>
                    @endforeach
                </x-filament::section>

            </div>
        </x-filament::section>

        {{-- Composer lockfile --}}
        <x-filament::section compact collapsible icon="heroicon-o-lock-closed">
            <x-slot name="heading">
                composer.lock
            </x-slot>
            <div class="flex flex-col gap-4">

                <x-filament::section compact collapsible collapsed>
                    <x-slot name="heading">
                        <div class="flex gap-x-3">
                            <x-filament::badge>{{ $composer->locked->count() }}</x-filament::badge>
                            <span>packages</span>
                        </div>
                    </x-slot>
                    <div class="flex flex-col gap-2 w-full">
                        @foreach($composer->locked as $locked)
                            <p class="flex flex-row gap-2 w-full">
                                <x-heroicon-o-code-bracket-square class="w-6 h-6 text-gray-500"/>
                                <a class="underline" href="{{ $locked->source }}" target="_blank">Source</a>
                                <x-heroicon-o-arrow-down-on-square class="w-6 h-6 text-gray-500"/>
                                <a class="underline" href="{{ $locked->dist }}" target="_blank">Dist</a>
                                <x-heroicon-o-cube class="w-6 h-6 text-gray-500"/>
                                <strong x-tooltip="{content: '{{ $locked->description }}'}">{{ $locked->name }}</strong>
                                <span>{{ $locked->version }}</span>
                            </p>
                        @endforeach
                    </div>
                </x-filament::section>

                <x-filament::section compact collapsible collapsed>
                    <x-slot name="heading">
                        <div class="flex gap-x-3">
                            <x-filament::badge>{{ $composer->lockedDev->count() }}</x-filament::badge>
                            <span>packages-dev</span>
                        </div>
                    </x-slot>
                    <div class="flex flex-col gap-2 w-full">
                        @foreach($composer->lockedDev as $lockedDev)
                            <p class="flex flex-row gap-2 w-full">
                                <x-heroicon-o-code-bracket-square class="w-6 h-6 text-gray-500"/>
                                <a class="underline" href="{{ $lockedDev->source }}" target="_blank">Source</a>
                                <x-heroicon-o-arrow-down-on-square class="w-6 h-6 text-gray-500"/>
                                <a class="underline" href="{{ $lockedDev->dist }}" target="_blank">Dist</a>
                                <x-heroicon-o-cube class="w-6 h-6 text-gray-500"/>
                                <strong x-tooltip="{content: '{{ $lockedDev->description }}'}">{{ $lockedDev->name }}</strong>
                                <span>{{ $lockedDev->version }}</span>
                            </p>
                        @endforeach
                    </div>
                </x-filament::section>

            </div>
        </x-filament::section>

    </div>

</x-filament-panels::page>

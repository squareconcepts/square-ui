
@if(!$hasError)
    <img src="{{asset('vendor/squareconcepts/square-ui/flags/'.$country.'.svg')}}" alt="{{$country}}" {{$attributes}}>
@else
    @production
        <x-square-ui.alerts::error>
            <x-slot:message>
                <p>
                    <u>{{ $country }}.svg </u> is niet gevonden op de server.
                </p>
            </x-slot:message>
        </x-square-ui.alerts::error>
    @endproduction
    @if(!$app->isProduction())
        <x-square-ui.tooltip>
            <x-slot:toggle>
                <x-square-ui.alerts::error>
                    <x-slot:message>
                        <p>
                            <u>{{ $country }}.svg </u> is niet gevonden op de server.
                        </p>
                    </x-slot:message>
                </x-square-ui.alerts::error>

            </x-slot:toggle>
            <x-slot:content class="grid grid-cols-12">
                    @foreach($options as $option)
                        <flux:badge size="sm">
                            <span class="mr-2">{{$option}}</span>
                            <img src="{{asset('vendor/squareconcepts/square-ui/flags/'.$option.'.svg')}}" alt="{{$option}}" style="width: 25px" ">

                        </flux:badge>
                    @endforeach
            </x-slot:content>
        </x-square-ui.tooltip>

   @endif
@endif

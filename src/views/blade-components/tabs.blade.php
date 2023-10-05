<div x-data="{activeTab: '{{$activeTab}}' }">
    <ul class="nav nav-link-tabs flex" role="tablist" >
    @foreach($__laravel_slots as $itemKey => $item)
       @if($itemKey == '__default')
           @continue
        @endif
       <li  class="nav-item flex-1">
           <button
               class="nav-link w-full p-2 whitespace-nowrap"
               :class="activeTab == '{{$itemKey}}' && 'border-b border-blue-500' "
               type="button"
               x-on:click="activeTab = '{{$itemKey}}';"
               role="tab"
           > {{$itemKey}}
           </button>
       </li>
    @endforeach
    </ul>
    <div class="tab-content mt-5">
        @foreach($__laravel_slots as $itemKey => $item)
            @if($itemKey == '__default')
                @continue
            @endif
            <div x-show="activeTab == '{{$itemKey}}'" class="tab-pane leading-relaxed active" role="tabpanel" >
               {{$item}}
            </div>
        @endforeach

    </div>

</div>

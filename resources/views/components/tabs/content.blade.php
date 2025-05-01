<div 
    x-show="activeTab === '{{ $name }}'" 
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    role="tabpanel"
    id="tab-panel-{{ $name }}"
    :aria-labelledby="'tab-{{ $name }}'"
    class="tab-content"
>
    {{ $slot }}
</div>
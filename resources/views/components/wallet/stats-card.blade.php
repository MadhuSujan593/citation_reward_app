@props(['title', 'value', 'icon', 'color' => 'indigo'])

<div class="bg-white/80 backdrop-blur-xl rounded-xl p-4 shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600">{{ $title }}</p>
            <p class="text-xl font-bold text-{{ $color }}-600">{{ $value }}</p>
        </div>
        <div class="w-10 h-10 bg-{{ $color }}-100 rounded-lg flex items-center justify-center">
            <i class="{{ $icon }} text-{{ $color }}-600"></i>
        </div>
    </div>
</div> 
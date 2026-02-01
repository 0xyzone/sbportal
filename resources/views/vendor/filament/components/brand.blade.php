@vite('resources/css/app.css')
<div class="flex items-center" style="gap: 10px;">

    <div>
        <img src="{{ asset('images/favicon.png') }}" alt="favicon" style="height: 4rem;"> 
    </div>
    <p class="font-bold text-2xl text-gray-800 dark:text-gray-200 flex lg:text-3xl items-center">
        <span>Spelling</span>
        <span>Bee</span>
        <span style="color: #eab308" class="font-bold">Hive</span>
        <span class="text-sm px-2 py-1 bg-amber-400 text-black ml-2 capitalize rounded-lg">{{ Filament\Facades\Filament::getCurrentPanel()?->getId() }}</span>
    </p>
</div>
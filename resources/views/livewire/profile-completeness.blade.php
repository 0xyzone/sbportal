<x-filament-widgets::widget>
    <x-filament::section>
        @php
            $percentage = auth()->user()->getProfileCompleteness();
        @endphp
        
        <style>
            .completeness-text {
                font-size: 1.25rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
            }

            .progress-container {
                background-color: rgba(100, 100, 100, 0.2);
                border-radius: 8px;
                overflow: hidden;
                height: 20px;
                width: 100%;
            }

            .progress-bar-fill {
                height: 100%;
                background-color: rgb(34, 197, 94);
                transition: width 1s ease-in-out linear; /* Smooth transition instead of keyframes */
                border-radius: 8px;
            }
        </style>

        <div>
            <p class="completeness-text">Profile {{ $percentage }}% Complete</p>
            <div class="progress-container">
                <div class="progress-bar-fill" style="width: {{ $percentage }}%;"></div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
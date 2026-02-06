<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spelling Bee | The Golden Hive</title>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <style>
        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: #080808; /* Deeper black */
            overflow-x: hidden;
        }

        /* Golden Honey Color Variables */
        :root {
            --honey: #eab308; /* Darker, richer gold */
            --honey-glow: rgba(234, 179, 8, 0.4);
        }

        /* Animated Glowing Background */
        .hive-container {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 50% 50%, #121212 0%, #050505 100%);
            z-index: -1;
        }

        /* Darker Honeycomb SVG Pattern */
        .honeycomb-overlay {
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='100' viewBox='0 0 56 100'%3E%3Cpath d='M28 66L0 50L0 16L28 0L56 16L56 50L28 66L28 100' fill='none' stroke='%23eab308' stroke-width='1.5' stroke-opacity='0.15'/%3E%3C/svg%3E");
            mask-image: radial-gradient(circle at center, black, transparent 85%);
        }

        /* Pulsing Glow */
        @keyframes pulse-glow {
            0%, 100% { filter: drop-shadow(0 0 8px var(--honey)); }
            50% { filter: drop-shadow(0 0 25px var(--honey)); }
        }

        .glow-hex { animation: pulse-glow 5s ease-in-out infinite; }

        /* Floating Bee Animation */
        @keyframes float-bee {
            0% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(40px, -60px) rotate(15deg); }
            66% { transform: translate(-30px, -30px) rotate(-15deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }

        .bee {
            position: absolute; font-size: 2.2rem; pointer-events: none; z-index: 1;
            filter: drop-shadow(0 0 15px rgba(234, 179, 8, 0.3));
        }

        .bee-1 { top: 10%; left: 15%; animation: float-bee 9s infinite ease-in-out; }
        .bee-2 { bottom: 15%; right: 10%; animation: float-bee 11s infinite ease-in-out reverse; }

        .hover-glow:hover {
            box-shadow: 0 0 40px rgba(234, 179, 8, 0.15);
            border-color: var(--honey);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-6">

    <div class="hive-container"><div class="honeycomb-overlay"></div></div>
    <div class="bee bee-1 w-12 h-auto"><img src="{{ asset('images/mordern_bee.png') }}" alt=""></div>
    <div class="bee bee-2 w-12 h-auto"><img src="{{ asset('images/mordern_bee.png') }}" alt=""></div>

    <div class="relative z-10 text-center mb-16">
        <div class="glow-hex inline-block mb-6">
            <img src="{{ asset('images/favicon.png') }}" alt="" class="w-32 h-auto">
        </div>
        <h1 class="text-6xl md:text-8xl font-black text-white tracking-tighter uppercase leading-none">
            THE <span class="text-[#eab308] drop-shadow-[0_0_20px_rgba(234,179,8,0.5)]">HIVE</span>
        </h1>
        <p class="text-gray-500 mt-6 text-xl font-medium tracking-widest uppercase italic">Select your portal</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-5xl w-full z-10">
        
        <a href="{{ url('/school') }}" class="group relative bg-[#111] border border-amber-400/50 p-12 rounded-[3rem] transition-all duration-500 hover-glow transform hover:-translate-y-2 shadow-amber-400/20 shadow-2xl">
            <div class="relative z-10">
                <div class="text-4xl mb-8 bg-[#1a1a1a] w-20 h-20 flex items-center justify-center rounded-3xl border border-white/50 group-hover:bg-[#eab308] group-hover:text-black transition-all duration-500">🏫</div>
                <h2 class="text-3xl font-bold text-white mb-4 tracking-tight">School Portal</h2>
                <p class="text-gray-400 text-lg leading-relaxed mb-8">Manage student registries, institutional rankings, and competition heat data.</p>
                <span class="text-[#eab308] font-black tracking-widest text-xs uppercase group-hover:pl-4 transition-all duration-300">Entrance &rarr;</span>
            </div>
        </a>

        <a href="{{ url('/me') }}" class="group relative bg-[#eab308] p-12 rounded-[3rem] transition-all duration-500 hover:scale-[1.03] shadow-[0_30px_70px_rgba(234,179,8,0.2)]">
            <div class="relative z-10 text-black">
                <div class="text-4xl mb-8 bg-black w-20 h-20 flex items-center justify-center rounded-3xl shadow-2xl p-4">
                    <img src="{{ asset('images/mordern_bee.png') }}" alt="">
                </div>
                <h2 class="text-3xl font-black mb-4 tracking-tight">Individual Portal</h2>
                <p class="text-black/70 text-lg font-medium leading-relaxed mb-8">Ready to spell? Access your training room, live competitions, and trophies.</p>
                <span class="bg-black text-white px-6 py-2 rounded-full font-black tracking-widest text-xs uppercase">Start Buzzing &rarr;</span>
            </div>
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, black 1px, transparent 0); background-size: 20px 20px;"></div>
        </a>

    </div>

    <p class="mt-20 text-gray-700 font-bold tracking-[0.4em] text-[10px] z-10 uppercase">
        © {{ now()->year }} Spelling Bee Competition • Precision In Every Word
    </p>

</body>
</html>
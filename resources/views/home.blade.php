<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VELTRA — Precision Horology</title>

    <!-- Google Fonts: Montserrat, Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600&family=Outfit:wght@400;450;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #0b0f12;
            color: #d1d6da;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            /* Authentic dark brushed titanium & slate background */
            background-image: 
                radial-gradient(ellipse 70% 30% at 50% 0%, #151b20 0%, transparent 60%),
                radial-gradient(circle at 85% 35%, rgba(94, 163, 144, 0.05) 0%, transparent 40%),
                repeating-linear-gradient(180deg, rgba(255,255,255,0.012) 0px, rgba(255,255,255,0.012) 1px, transparent 1px, transparent 3px),
                linear-gradient(180deg, #101518 0%, #0c0f12 50%, #080a0c 100%);
            background-attachment: fixed;
        }

        /* Ambient watch dial blueprint wireframe in the background */
        .ambient-wireframe {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            width: 100%;
            height: calc(100vh - 80px);
            padding-right: 6vw;
            pointer-events: none;
            opacity: 0.35;
        }

        .wireframe-svg {
            width: 520px;
            max-width: 90vw;
            height: auto;
        }
    </style>
</head>
<body>
    <!-- Top Navigation Component -->
    <x-navbar />

    <!-- Ambient Horology Blueprint Vector Background -->
    <div class="ambient-wireframe">
        <svg class="wireframe-svg" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="250" cy="250" r="230" stroke="#5ea391" stroke-width="0.8" stroke-dasharray="3 4"/>
            <circle cx="250" cy="250" r="190" stroke="#5ea391" stroke-width="1"/>
            <circle cx="250" cy="250" r="150" stroke="#5ea391" stroke-width="0.6"/>
            <circle cx="250" cy="250" r="90" stroke="#5ea391" stroke-width="0.9"/>
            <circle cx="250" cy="250" r="40" stroke="#5ea391" stroke-width="1.2"/>
            <line x1="250" y1="20" x2="250" y2="480" stroke="#5ea391" stroke-width="0.6" stroke-dasharray="5 5"/>
            <line x1="20" y1="250" x2="480" y2="250" stroke="#5ea391" stroke-width="0.6" stroke-dasharray="5 5"/>
            <path d="M 120 180 C 180 120, 320 120, 380 180" stroke="#c98565" stroke-width="0.8" opacity="0.6"/>
            <path d="M 140 330 C 190 380, 310 380, 360 330" stroke="#c98565" stroke-width="0.8" opacity="0.6"/>
        </svg>
    </div>
</body>
</html>

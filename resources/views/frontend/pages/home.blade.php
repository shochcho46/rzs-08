<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RZS08 - Cricket Event Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --royal-purple: #7C3AED;
            --deep-purple: #6D28D9;
            --light-purple: #A855F7;
            --electric-purple: #C084FC;
            --neon-blue: #06B6D4;
            --neon-pink: #EC4899;
            --cricket-gold: #F59E0B;
            --stadium-green: #10B981;
            --bg-dark: #0F0A1F;
            --bg-card: #1A1433;
            --text-primary: #F3F4F6;
            --text-secondary: #D1D5DB;
            --border: rgba(124,58,237,0.2);
            --success: #10B981;
            --danger: #EF4444;
            --gradient-1: linear-gradient(135deg, #7C3AED 0%, #A855F7 50%, #C084FC 100%);
            --gradient-fire: linear-gradient(135deg, #F59E0B 0%, #EF4444 50%, #EC4899 100%);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }
        .lightning-text {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            background: linear-gradient(135deg, #7C3AED 0%, #A855F7 25%, #FFFFFF 50%, #A855F7 75%, #7C3AED 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 8px rgba(124,58,237,0.3), 0 0 15px rgba(168,85,247,0.2);
            filter: drop-shadow(0 0 5px rgba(124,58,237,0.4));
            animation: electricPulse 3s ease-in-out infinite;
        }
        @keyframes electricPulse {
            0%, 100% { filter: drop-shadow(0 0 5px rgba(124,58,237,0.4)) brightness(1); }
            50% { filter: drop-shadow(0 0 10px rgba(124,58,237,0.5)) brightness(1.1); }
        }
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -3;
            background: radial-gradient(ellipse at top, rgba(124,58,237,0.15), transparent),
                        radial-gradient(ellipse at bottom right, rgba(6,182,212,0.1), transparent),
                        radial-gradient(ellipse at bottom left, rgba(236,72,153,0.08), transparent);
        }
        .stadium-lights {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            pointer-events: none;
        }
        .light-beam {
            position: absolute;
            width: 200px;
            height: 400px;
            filter: blur(40px);
            animation: lightBeam 6s ease-in-out infinite;
            opacity: 0.6;
        }
        .light-beam:nth-child(1) { left: 10%; top: -100px; background: linear-gradient(180deg, rgba(124,58,237,0.3), transparent); }
        .light-beam:nth-child(2) { right: 10%; top: -100px; background: linear-gradient(180deg, rgba(6,182,212,0.3), transparent); animation-delay: 2s; }
        .light-beam:nth-child(3) { left: 30%; top: -100px; background: linear-gradient(180deg, rgba(236,72,153,0.3), transparent); animation-delay: 4s; }
        .light-beam:nth-child(4) { right: 30%; top: -100px; background: linear-gradient(180deg, rgba(245,158,11,0.3), transparent); animation-delay: 3s; }
        @keyframes lightBeam {
            0%, 100% { opacity: 0.4; transform: scaleY(1); }
            50% { opacity: 0.8; transform: scaleY(1.3); }
        }
        .parallax-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }
        .parallax-foreground {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            pointer-events: none;
            overflow: hidden;
        }
        /* ── Cricket ball ── */
        .cricket-ball-falling {
            position: absolute;
            left: var(--start-left, 50%);
            top: -70px;
            width: var(--ball-size, 44px);
            height: var(--ball-size, 44px);
            border-radius: 50%;
            overflow: hidden;
            background: #B80F27;
            box-shadow:
                0 10px 28px rgba(150,8,28,0.70),
                inset -5px -6px 12px rgba(0,0,0,0.58),
                inset 3px 3px 6px rgba(255,255,255,0.10);
            animation: ballFallRandom var(--duration, 7.5s) linear infinite;
            animation-delay: var(--delay, 0s);
            transform-origin: center;
            opacity: 0;
        }
        /* Single white seam exactly in the center */
        .cricket-ball-falling::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 7%;
            width: 86%;
            height: 3px;
            transform: translateY(-50%);
            background: #FFFFFF;
            border-radius: 999px;
            box-shadow: 0 0 3px rgba(255,255,255,0.65);
        }
        .cricket-ball-falling::after {
            content: none;
        }
        .cricket-ball-falling.ball-1 { --start-left: 12%; --drift-x: 30px;  --duration: 7.8s; --delay: 0s;   --ball-size: 40px; }
        .cricket-ball-falling.ball-2 { --start-left: 46%; --drift-x: -26px; --duration: 8.6s; --delay: 1.5s; --ball-size: 44px; }
        .cricket-ball-falling.ball-3 { --start-left: 80%; --drift-x: 38px;  --duration: 8.0s; --delay: 0.9s; --ball-size: 36px; }
        @keyframes ballFallRandom {
            0%   { top: -70px; transform: translate3d(0, 0, 0) rotate(0deg);   opacity: 0; }
            8%   { opacity: 1; }
            92%  { opacity: 1; }
            100% { top: 115%; transform: translate3d(var(--drift-x,0px), 0, 0) rotate(1080deg); opacity: 0; }
        }
        .cricket-ball-ground {
            position: absolute;
            bottom: 20%;
            right: 15%;
            width: 30px;
            height: 30px;
            background: radial-gradient(circle at 30% 30%, #EF4444, #B91C1C);
            border-radius: 50%;
            box-shadow: 0 6px 20px rgba(239,68,68,0.7);
            animation: ballBounce 3s ease-in-out infinite;
        }
        @keyframes ballBounce {
            0%, 100% { transform: translateY(0) scale(1); }
            25% { transform: translateY(-40px) scale(1.1) rotate(180deg); }
            50% { transform: translateY(0) scale(0.95) rotate(360deg); }
            75% { transform: translateY(-20px) scale(1.05) rotate(540deg); }
        }
        .boundary-rope {
            position: absolute;
            bottom: 10%;
            left: 0;
            right: 0;
            height: 3px;
            background: repeating-linear-gradient(
                90deg,
                #7C3AED 0px,
                #7C3AED 20px,
                transparent 20px,
                transparent 40px,
                #EC4899 40px,
                #EC4899 60px,
                transparent 60px,
                transparent 80px
            );
            opacity: 0.4;
            animation: ropeShift 8s linear infinite;
        }
        @keyframes ropeShift {
            0% { background-position: 0 0; }
            100% { background-position: 80px 0; }
        }
        .field-circle {
            position: absolute;
            bottom: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 600px;
            border: 2px dashed rgba(124,58,237,0.2);
            border-radius: 50%;
            animation: fieldRotate 30s linear infinite;
        }
        @keyframes fieldRotate {
            0% { transform: translateX(-50%) rotate(0deg) scale(1); }
            50% { transform: translateX(-50%) rotate(180deg) scale(1.1); }
            100% { transform: translateX(-50%) rotate(360deg) scale(1); }
        }
        .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; pointer-events: none; }
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            opacity: 0.6;
            animation: floatUp linear infinite;
        }
        @keyframes floatUp {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 0.6; }
            90% { opacity: 0.6; }
            100% { transform: translateY(-100px) scale(1); opacity: 0; }
        }
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(15,10,31,0.95);
            backdrop-filter: blur(20px);
            border-bottom: 2px solid var(--border);
            padding: 0 clamp(16px, 4vw, 32px);
            box-shadow: 0 4px 30px rgba(124,58,237,0.2);
        }
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            height: clamp(60px, 15vw, 80px);
        }
        .logo {
            display: flex;
            align-items: center;
            gap: clamp(10px, 2vw, 14px);
            text-decoration: none;
        }
        .logo-icon {
            width: clamp(40px, 10vw, 56px);
            height: clamp(40px, 10vw, 56px);
            background: var(--gradient-1);
            border-radius: clamp(12px, 3vw, 16px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: clamp(14px, 3.5vw, 20px);
            color: white;
            box-shadow: 0 6px 25px rgba(124,58,237,0.6);
            animation: logoPulse 3s ease-in-out infinite;
        }
        @keyframes logoPulse {
            0%, 100% { box-shadow: 0 6px 25px rgba(124,58,237,0.6); }
            50% { box-shadow: 0 8px 35px rgba(124,58,237,0.9), 0 0 50px rgba(168,85,247,0.4); }
        }
        .logo-text {
            font-size: clamp(20px, 5vw, 28px);
            font-weight: 900;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero {
            padding: clamp(40px, 10vw, 100px) clamp(16px, 4vw, 24px) clamp(30px, 8vw, 80px);
            text-align: center;
            position: relative;
        }
        .hero h1 {
            font-size: clamp(28px, 8vw, 72px);
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: clamp(16px, 4vw, 24px);
            letter-spacing: -2px;
        }
        .hero .dates {
            font-size: clamp(14px, 3.5vw, 20px);
            color: var(--text-secondary);
            margin-top: clamp(12px, 3vw, 16px);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(8px, 2vw, 12px);
            flex-wrap: wrap;
        }
        .hero .dates i { color: var(--cricket-gold); }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: clamp(20px, 5vw, 40px) clamp(16px, 4vw, 24px);
        }
        .jersey-card {
            background: var(--bg-card);
            border: 2px solid var(--border);
            border-radius: clamp(20px, 5vw, 28px);
            overflow: hidden;
            margin-top: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4), 0 0 100px rgba(124,58,237,0.15);
            position: relative;
        }
        .jersey-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-1);
        }
        .jersey-card-header {
            padding: clamp(24px, 6vw, 40px);
            text-align: center;
            background: linear-gradient(180deg, rgba(124,58,237,0.1), transparent);
            border-bottom: 1px solid var(--border);
        }
        .jersey-card-header h2 {
            font-size: clamp(20px, 5vw, 32px);
            font-weight: 800;
            margin-bottom: clamp(8px, 2vw, 12px);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(8px, 2vw, 16px);
            flex-wrap: wrap;
        }
        .jersey-form { padding: clamp(24px, 6vw, 40px); }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
            gap: clamp(20px, 5vw, 28px);
        }
        .form-group { display: flex; flex-direction: column; gap: 10px; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label {
            font-size: clamp(12px, 3vw, 14px);
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .form-group label i { color: var(--electric-purple); }
        .form-group label .required { color: var(--danger); }
        .form-control {
            background: var(--bg-dark);
            border: 2px solid var(--border);
            border-radius: clamp(12px, 3vw, 14px);
            padding: clamp(12px, 3vw, 16px) clamp(16px, 4vw, 20px);
            color: var(--text-primary);
            font-size: clamp(14px, 3.5vw, 16px);
            font-family: inherit;
            transition: all 0.3s ease;
            outline: none;
            width: 100%;
        }
        .form-control:focus {
            border-color: var(--royal-purple);
            box-shadow: 0 0 0 4px rgba(124,58,237,0.2);
        }
        .form-control.valid {
            border-color: var(--success);
        }
        .form-control.invalid {
            border-color: var(--danger);
        }
        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%237C3AED' viewBox='0 0 16 16'%3E%3Cpath d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right clamp(12px, 3vw, 20px) center;
            padding-right: clamp(40px, 10vw, 50px);
        }
        .error-message, .success-message {
            font-size: clamp(11px, 2.8vw, 13px);
            margin-top: 6px;
            display: none;
            align-items: center;
            gap: 6px;
        }
        .error-message { color: var(--danger); }
        .success-message { color: var(--success); }
        .error-message.show, .success-message.show { display: flex; }
        .custom-size-fields {
            display: none;
            grid-template-columns: repeat(auto-fit, minmax(min(200px, 100%), 1fr));
            gap: clamp(16px, 4vw, 20px);
            padding: clamp(20px, 5vw, 24px);
            background: rgba(124,58,237,0.08);
            border: 2px dashed var(--border);
            border-radius: 16px;
            margin-top: 12px;
        }
        .custom-size-fields.show { display: grid; }
        .sleeve-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(150px, 100%), 1fr));
            gap: clamp(12px, 3vw, 16px);
        }
        .sleeve-option input[type="radio"] { display: none; }
        .sleeve-option label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: clamp(8px, 2vw, 12px);
            padding: clamp(16px, 4vw, 24px) clamp(12px, 3vw, 20px);
            background: var(--bg-dark);
            border: 3px solid var(--border);
            border-radius: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        .sleeve-option label i {
            font-size: clamp(24px, 6vw, 32px);
            color: var(--text-secondary);
        }
        .sleeve-option label img {
            filter: brightness(1.5) contrast(1.2);
            transition: all 0.3s ease;
        }
        .sleeve-option input[type="radio"]:checked + label {
            border-color: var(--neon-pink);
            background: rgba(236,72,153,0.15);
            box-shadow: 0 8px 24px rgba(236,72,153,0.3);
        }
        .sleeve-option input[type="radio"]:checked + label i { color: var(--neon-pink); }
        .sleeve-option input[type="radio"]:checked + label img {
            filter: brightness(2) contrast(1.3) drop-shadow(0 0 12px rgba(236,72,153,0.8));
        }
        .guest-section {
            margin-top: clamp(30px, 8vw, 40px);
            padding-top: clamp(30px, 8vw, 40px);
            border-top: 2px solid var(--border);
        }
        .guest-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: clamp(20px, 5vw, 28px);
            flex-wrap: wrap;
            gap: clamp(12px, 3vw, 16px);
        }
        .guest-header h3 {
            font-size: clamp(18px, 4.5vw, 22px);
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: clamp(8px, 2vw, 12px);
        }
        .guest-count {
            background: var(--gradient-fire);
            color: white;
            padding: clamp(4px, 1vw, 6px) clamp(12px, 3vw, 16px);
            border-radius: 100px;
            font-size: clamp(12px, 3vw, 14px);
            font-weight: 800;
        }
        .guest-list { display: flex; flex-direction: column; gap: clamp(16px, 4vw, 20px); }
        .guest-item {
            background: var(--bg-dark);
            border: 2px solid var(--border);
            border-radius: clamp(16px, 4vw, 20px);
            padding: clamp(20px, 5vw, 28px);
            transition: all 0.3s ease;
        }
        .guest-item-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: clamp(16px, 4vw, 24px);
            flex-wrap: wrap;
            gap: 12px;
        }
        .guest-remove {
            background: rgba(239,68,68,0.15);
            border: 2px solid rgba(239,68,68,0.3);
            color: var(--danger);
            width: clamp(36px, 9vw, 40px);
            height: clamp(36px, 9vw, 40px);
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .form-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(180px, 100%), 1fr));
            gap: clamp(16px, 4vw, 20px);
            margin-top: clamp(40px, 10vw, 50px);
            padding-top: clamp(30px, 8vw, 40px);
            border-top: 2px solid var(--border);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: clamp(8px, 2vw, 10px);
            padding: clamp(14px, 3.5vw, 18px) clamp(24px, 6vw, 36px);
            border-radius: clamp(12px, 3vw, 14px);
            font-weight: 700;
            font-size: clamp(14px, 3.5vw, 16px);
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            font-family: inherit;
            width: 100%;
        }
        .btn-primary {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 6px 25px rgba(124,58,237,0.4);
        }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
        .registration-over-note {
            margin-top: 18px;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid rgba(239,68,68,0.35);
            background: rgba(239,68,68,0.12);
            color: #FCA5A5;
            font-weight: 600;
            font-size: clamp(13px, 3vw, 14px);
            text-align: center;
        }
        .parallax-item {
            position: absolute;
            pointer-events: none;
            will-change: transform;
        }
        .wickets-parallax {
            display: flex;
            align-items: flex-end;
            gap: 7px;
            opacity: 0.9;
            filter: drop-shadow(0 8px 20px rgba(245,158,11,0.4));
        }
        .wickets-parallax span {
            width: 7px;
            height: 78px;
            border-radius: 4px;
            background: linear-gradient(180deg, #FBBF24 0%, #D97706 55%, #7C2D12 100%);
            position: relative;
            animation: wicketPulse 3.5s ease-in-out infinite;
        }
        .wickets-parallax span:nth-child(2) { animation-delay: 0.2s; }
        .wickets-parallax span:nth-child(3) { animation-delay: 0.4s; }
        .wickets-parallax span::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -2px;
            width: 12px;
            height: 4px;
            border-radius: 2px;
            background: #EF4444;
        }
        .wickets-left {
            left: 6%;
            bottom: 20%;
            animation: wicketSwayLeft 6s ease-in-out infinite;
        }
        .title-stump-parallax {
            right: 10%;
            top: 19%;
            transform: scale(0.9);
            animation: titleStumpFloat 6s ease-in-out infinite;
        }
        /* ── Cricket bat parallax (opposite side of stumps) ── */
        .bat-side-parallax {
            left: 5%;
            top: 20%;
            width: 28px;
            height: 140px;
            opacity: 0.88;
            animation: batSideFloat 7s ease-in-out infinite;
        }
        /* blade */
        .bat-side-parallax::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 28px;
            height: 108px;
            background: linear-gradient(180deg,
                #F2C97E 0%,
                #D4943A 28%,
                #B5721E 60%,
                #8A4E10 100%);
            border-radius: 4px 4px 10px 10px;
            box-shadow:
                2px 0 8px rgba(0,0,0,0.35),
                inset 3px 0 6px rgba(255,255,255,0.18);
        }
        /* handle */
        .bat-side-parallax::after {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 10px;
            height: 42px;
            background: repeating-linear-gradient(180deg,
                #5C3310 0 5px,
                #8A5220 5px 10px);
            border-radius: 5px 5px 2px 2px;
            box-shadow: 1px 0 4px rgba(0,0,0,0.4);
        }
        @keyframes batSideFloat {
            0%,100% { transform: translateY(0) rotate(-4deg); }
            50%      { transform: translateY(-14px) rotate(2deg); }
        }
        .ball-orbit-parallax {
            left: 18%;
            top: 20%;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 1px dashed rgba(239,68,68,0.45);
            opacity: 0.85;
            animation: orbitSpin 10s linear infinite;
            box-shadow: 0 0 26px rgba(239,68,68,0.25);
        }
        .ball-orbit-parallax::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, #EF4444 0%, #B91C1C 80%);
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 0 20px rgba(239,68,68,0.8);
        }
        @keyframes wicketPulse {
            0%, 100% { box-shadow: 0 0 0 rgba(245,158,11,0.2); }
            50% { box-shadow: 0 0 18px rgba(245,158,11,0.45); }
        }
        @keyframes wicketSwayLeft {
            0%, 100% { transform: translateY(0) rotate(-2deg); }
            50% { transform: translateY(-8px) rotate(2deg); }
        }
        @keyframes titleStumpFloat {
            0%, 100% { transform: scale(0.9) translateY(0) rotate(1deg); }
            50% { transform: scale(0.95) translateY(-10px) rotate(-1deg); }
        }
        @keyframes orbitSpin {
            0% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.05); }
            100% { transform: rotate(360deg) scale(1); }
        }

        .btn-secondary {
            background: rgba(6,182,212,0.15);
            color: var(--neon-blue);
            border: 2px solid rgba(6,182,212,0.3);
        }
        .btn-participants {
            background: var(--gradient-fire);
            color: white;
            box-shadow: 0 6px 25px rgba(245,158,11,0.4);
            margin-top: 20px;
        }
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(10px);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .modal-overlay.show {
            display: flex;
            opacity: 1;
        }
        .modal {
            background: var(--bg-card);
            border: 3px solid var(--royal-purple);
            border-radius: 24px;
            width: 100%;
            max-width: 900px;
            max-height: 85vh;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(124,58,237,0.6);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        .modal-overlay.show .modal {
            transform: scale(1);
        }
        .modal-header {
            padding: 24px 28px;
            background: linear-gradient(135deg, rgba(124,58,237,0.2), rgba(236,72,153,0.1));
            border-bottom: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-header h2 {
            font-size: clamp(20px, 4vw, 28px);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .modal-close {
            background: rgba(239,68,68,0.2);
            border: 2px solid rgba(239,68,68,0.4);
            color: var(--danger);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .modal-close:hover {
            background: var(--danger);
            color: white;
        }
        .modal-body {
            padding: 28px;
            max-height: calc(85vh - 100px);
            overflow-y: auto;
        }
        .participant-card {
            background: var(--bg-dark);
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            transition: all 0.3s ease;
        }
        .participant-card:hover {
            border-color: var(--electric-purple);
            box-shadow: 0 8px 24px rgba(124,58,237,0.3);
        }
        .participant-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .participant-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--electric-purple);
        }
        .participant-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }
        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        .detail-item i {
            color: var(--cricket-gold);
            width: 20px;
        }
        .toast-container {
            position: fixed;
            bottom: clamp(20px, 5vw, 30px);
            right: clamp(16px, 4vw, 30px);
            z-index: 3000;
            display: flex;
            flex-direction: column;
            gap: clamp(12px, 3vw, 16px);
            max-width: calc(100vw - 32px);
        }
        .toast {
            background: var(--bg-card);
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: clamp(16px, 4vw, 20px) clamp(20px, 5vw, 24px);
            display: flex;
            align-items: center;
            gap: clamp(12px, 3vw, 14px);
            box-shadow: 0 12px 48px rgba(0,0,0,0.5);
            min-width: min(320px, calc(100vw - 32px));
        }
        .toast-icon {
            width: clamp(36px, 9vw, 44px);
            height: clamp(36px, 9vw, 44px);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(16px, 4vw, 20px);
            flex-shrink: 0;
        }
        .toast-success .toast-icon { background: rgba(16,185,129,0.2); color: var(--success); }
        .toast-error .toast-icon { background: rgba(239,68,68,0.2); color: var(--danger); }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: var(--royal-purple); border-radius: 4px; }
    </style>
</head>
<body>
    <div class="bg-animation"></div>

    <div class="stadium-lights">
        <div class="light-beam"></div>
        <div class="light-beam"></div>
        <div class="light-beam"></div>
        <div class="light-beam"></div>
    </div>

    <div class="parallax-container">
        <div class="field-circle"></div>
        <div class="boundary-rope"></div>
        <div class="parallax-item wickets-left" data-parallax-depth="0.26">
            <div class="wickets-parallax">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="parallax-item title-stump-parallax" data-parallax-depth="0.2">
            <div class="wickets-parallax">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="parallax-item bat-side-parallax" data-parallax-depth="0.22"></div>
        <div class="parallax-item ball-orbit-parallax" data-parallax-depth="0.22"></div>
        <div class="cricket-ball-ground"></div>
    </div>

    <div class="parallax-foreground">
        <div class="cricket-ball-falling ball-1"></div>
        <div class="cricket-ball-falling ball-2"></div>
        <div class="cricket-ball-falling ball-3"></div>
    </div>

    <div class="particles" id="particles"></div>

    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="logo">
                <div class="logo-icon">RZ</div>
                <span class="logo-text">RZS08</span>
            </a>
        </div>
    </nav>

    @if($events->count() > 0)
        @foreach($events as $event)
        @php
            $isRegistrationOver = now()->startOfDay()->gt($event->end_date);
        @endphp
        <section class="hero">
            <div class="hero-content">
                <h1>
                    <span class="lightning-text">{{ $event->title }}</span>
                </h1>
                <div class="dates">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ $event->start_date->format('M d, Y') }} - {{ $event->end_date->format('M d, Y') }}</span>
                </div>
                <div class="dates" style="margin-top: 12px;">
                    <i class="fas fa-door-open"></i>
                    <span style="color: {{ $isRegistrationOver ? 'var(--danger)' : 'var(--stadium-green)' }}; font-weight: 600;">
                        {{ $isRegistrationOver ? 'Registration Over' : 'Registration Open' }}
                    </span>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="jersey-card">
                <div class="jersey-card-header">
                    @if($event->logo)
                        <img src="{{ asset('storage/'.$event->logo) }}" alt="{{ $event->title }}" style="max-width:140px;max-height:100px;margin:0 auto 20px;border-radius:16px;">
                    @endif
                    <h2><i class="fas fa-user-circle"></i> <span class="lightning-text">Registration</span></h2>
                </div>
                <form class="jersey-form registration-form" data-event-id="{{ $event->id }}" data-registration-open="{{ $isRegistrationOver ? '0' : '1' }}">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <input type="hidden" name="is_guest_jersey" value="0">

                    @if($isRegistrationOver)
                        <div class="registration-over-note">
                            Registration is over for this event. New registration is closed.
                        </div>
                    @endif

                    <div class="form-grid">
                        <div class="form-group">
                            <label><i class="fas fa-phone"></i> <span class="lightning-text">Mobile Number</span> <span class="required">*</span></label>
                            <input type="tel" name="mobile" class="form-control mobile-input" placeholder="01XXXXXXXXX" required>
                            <div class="error-message mobile-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>This mobile number is already registered!</span>
                            </div>
                            <div class="success-message mobile-success">
                                <i class="fas fa-check-circle"></i>
                                <span>Available for registration</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-tshirt"></i> Jersey Name <span class="required">*</span></label>
                            <input type="text" name="jersey_name" class="form-control" placeholder="Name on jersey back" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-hashtag"></i> Jersey Number <span class="required">*</span></label>
                            <input type="text" name="jersey_number" class="form-control" placeholder="00-99" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-ruler"></i> Jersey Size <span class="required">*</span></label>
                            <select name="size" class="form-control size-select" required>
                                <option value="">Select Size</option>
                                <option value="SM">S (Chest-36/Height-26)</option>
                                <option value="M">M (Chest-38/Height-27)</option>
                                <option value="L">L (Chest-40/Height-28)</option>
                                <option value="XL">XL (Chest-42/Height-29)</option>
                                <option value="XXL">XXL (Chest-44/Height-30)</option>
                                <option value="XXXL">XXXL (Chest-46/Height-31)</option>
                                <option value="custom">Custom Size</option>
                            </select>
                        </div>
                        <div class="form-group full-width">
                            <div class="custom-size-fields custom-size-container">
                                <div class="form-group" style="margin:0;">
                                    <label><i class="fas fa-arrows-alt-h"></i> Width (inches)</label>
                                    <input type="text" name="custom_width" class="form-control" placeholder="e.g., 42">
                                </div>
                                <div class="form-group" style="margin:0;">
                                    <label><i class="fas fa-arrows-alt-v"></i> Height (inches)</label>
                                    <input type="text" name="custom_height" class="form-control" placeholder="e.g., 28">
                                </div>
                            </div>
                        </div>
                        <div class="form-group full-width">
                            <label><i class="fas fa-cut"></i> <span class="lightning-text">Sleeve Type</span> <span class="required">*</span></label>
                            <div class="sleeve-options">
                                <div class="sleeve-option">
                                    <input type="radio" name="sleeve_type" id="half_{{ $event->id }}" value="half_sleeve" checked>
                                    <label for="half_{{ $event->id }}">
                                        <i class="fas fa-tshirt"></i>
                                        <span>Half Sleeve</span>
                                    </label>
                                </div>
                                <div class="sleeve-option">
                                    <input type="radio" name="sleeve_type" id="full_{{ $event->id }}" value="full_sleeve">
                                    <label for="full_{{ $event->id }}">
                                        <img src="https://cdn-icons-png.flaticon.com/512/10934/10934966.png" alt="Full Sleeve" style="width: clamp(24px, 6vw, 32px); height: clamp(24px, 6vw, 32px);">
                                        <span>Full Sleeve</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-running"></i> Participation <span class="required">*</span></label>
                            <select name="play_status" class="form-control" required>
                                <option value="1">Player (Will Play)</option>
                                <option value="0">Jersey Only</option>
                            </select>
                        </div>
                    </div>

                    <div class="guest-section">
                        <div class="guest-header">
                            <h3><i class="fas fa-users"></i> <span class="lightning-text">Guest Jerseys</span></h3>
                            <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                                <span class="guest-count" id="guestCount_{{ $event->id }}">0 Guests</span>
                                <button type="button" class="btn btn-secondary" style="width:auto;min-width:auto;padding:10px 20px;font-size:14px;" onclick="addGuest({{ $event->id }})">
                                    <i class="fas fa-plus"></i> <span>Add Guest</span>
                                </button>
                            </div>
                        </div>
                        <div class="guest-list" id="guestList_{{ $event->id }}"></div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary submit-btn" {{ $isRegistrationOver ? 'disabled' : '' }}>
                            <i class="fas fa-check-circle"></i> <span>Complete Registration</span>
                        </button>
                        <button type="reset" class="btn btn-secondary" onclick="resetForm({{ $event->id }})">
                            <i class="fas fa-undo"></i> <span>Reset Form</span>
                        </button>
                    </div>

                    <button type="button" class="btn btn-participants" onclick="viewParticipants({{ $event->id }})">
                        <i class="fas fa-users"></i> <span>View All Participants</span>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    @else
        <section class="hero">
            <div class="hero-content">
                <h1>
                    <span class="lightning-text">Check Back Soon</span>
                </h1>
            </div>
        </section>
    @endif

    <div class="modal-overlay" id="participantsModal">
        <div class="modal">
            <div class="modal-header">
                <h2><i class="fas fa-users"></i> <span class="lightning-text">Event Participants</span></h2>
                <button class="modal-close" onclick="closeParticipants()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div style="padding: 20px; border-bottom: 2px solid var(--border);">
                <input type="text" id="participantSearch" class="form-control" placeholder="Search by name or mobile..."
                       style="background: var(--bg-dark); border: 2px solid var(--border); border-radius: 12px; padding: 12px 16px; color: var(--text-primary); font-size: 14px;">
            </div>
            <div class="modal-body" id="participantsList" style="max-height: 60vh; overflow-y: auto;">
                <p style="text-align:center;color:var(--text-secondary);">Loading...</p>
            </div>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script>
        let guestCounters = {};
        let mobileCheckTimeout;

        document.addEventListener('DOMContentLoaded', function() {
            function initParallaxMotion() {
                const parallaxItems = document.querySelectorAll('.parallax-item[data-parallax-depth]');
                if (!parallaxItems.length) return;

                let targetX = 0;
                let targetY = 0;
                let currentX = 0;
                let currentY = 0;
                let rafId = null;

                function animateParallax() {
                    currentX += (targetX - currentX) * 0.08;
                    currentY += (targetY - currentY) * 0.08;

                    const scrollOffset = window.scrollY * 0.03;

                    parallaxItems.forEach((item) => {
                        const depth = Number(item.dataset.parallaxDepth || 0.12);
                        const moveX = currentX * depth * 30;
                        const moveY = (currentY * depth * 24) + (scrollOffset * depth);
                        item.style.transform = `translate3d(${moveX}px, ${moveY}px, 0)`;
                    });

                    rafId = requestAnimationFrame(animateParallax);
                }

                window.addEventListener('mousemove', function(e) {
                    const centerX = window.innerWidth / 2;
                    const centerY = window.innerHeight / 2;
                    targetX = (e.clientX - centerX) / centerX;
                    targetY = (e.clientY - centerY) / centerY;
                });

                window.addEventListener('scroll', function() {
                    if (!rafId) {
                        rafId = requestAnimationFrame(animateParallax);
                    }
                }, { passive: true });

                if (!rafId) {
                    rafId = requestAnimationFrame(animateParallax);
                }
            }

            function createParticles() {
                const container = document.getElementById('particles');
                if (!container) return;
                const colors = ['#7C3AED', '#06B6D4', '#EC4899', '#F59E0B'];
                for (let i = 0; i < 50; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                    particle.style.animationDuration = (Math.random() * 20 + 15) + 's';
                    particle.style.animationDelay = Math.random() * 10 + 's';
                    container.appendChild(particle);
                }
            }
            createParticles();
            initParallaxMotion();

            document.querySelectorAll('.size-select').forEach(function(select) {
                select.addEventListener('change', function() {
                    const customFields = this.closest('.jersey-form').querySelector('.custom-size-container');
                    const widthInput = customFields.querySelector('input[name="custom_width"]');
                    const heightInput = customFields.querySelector('input[name="custom_height"]');
                    if (this.value === 'custom') {
                        customFields.classList.add('show');
                        widthInput.required = true;
                        heightInput.required = true;
                    } else {
                        customFields.classList.remove('show');
                        widthInput.required = false;
                        heightInput.required = false;
                        widthInput.value = '';
                        heightInput.value = '';
                    }
                });
            });

            document.querySelectorAll('.mobile-input').forEach(function(input) {
                input.addEventListener('input', function() {
                    clearTimeout(mobileCheckTimeout);
                    const form = this.closest('.registration-form');
                    const eventId = form.dataset.eventId;
                    const errorDiv = form.querySelector('.mobile-error');
                    const successDiv = form.querySelector('.mobile-success');

                    errorDiv.classList.remove('show');
                    successDiv.classList.remove('show');
                    this.classList.remove('valid', 'invalid');

                    const mobile = this.value.trim();
                    if (mobile.length < 10) return;

                    mobileCheckTimeout = setTimeout(() => {
                        fetch('/check-mobile?mobile=' + encodeURIComponent(mobile) + '&event_id=' + eventId)
                            .then(response => response.json())
                            .then(data => {
                                if (data.registration_over) {
                                    showToast('Registration is over for this event.', 'error');
                                    errorDiv.classList.remove('show');
                                    successDiv.classList.remove('show');
                                    input.classList.add('invalid');
                                    input.classList.remove('valid');
                                    return;
                                }

                                if (data.exists) {
                                    errorDiv.classList.add('show');
                                    successDiv.classList.remove('show');
                                    input.classList.add('invalid');
                                    input.classList.remove('valid');
                                } else {
                                    successDiv.classList.add('show');
                                    errorDiv.classList.remove('show');
                                    input.classList.add('valid');
                                    input.classList.remove('invalid');
                                }
                            });
                    }, 500);
                });
            });

            document.querySelectorAll('.registration-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (this.dataset.registrationOpen !== '1') {
                        showToast('Registration is over for this event.', 'error');
                        return;
                    }

                    const mobileInput = this.querySelector('.mobile-input');
                    if (mobileInput.classList.contains('invalid')) {
                        showToast('Please use a different mobile number', 'error');
                        return;
                    }

                    const submitBtn = this.querySelector('.submit-btn');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Processing...</span>';

                    const formData = new FormData(this);

                    const eventId = this.dataset.eventId;
                    const guestList = document.getElementById('guestList_' + eventId);
                    const guests = [];

                    if (guestList) {
                        guestList.querySelectorAll('.guest-item').forEach(function(item) {
                            guests.push({
                                name: item.querySelector('input[name="guest_name"]').value,
                                jersey_name: item.querySelector('input[name="guest_jersey_name"]').value,
                                jersey_number: item.querySelector('input[name="guest_jersey_number"]').value,
                                size: item.querySelector('select[name="guest_size"]').value,
                                custom_width: item.querySelector('input[name="guest_custom_width"]')?.value,
                                custom_height: item.querySelector('input[name="guest_custom_height"]')?.value,
                                sleeve_type: item.querySelector('select[name="guest_sleeve"]').value
                            });
                        });
                    }

                    formData.append('guests', JSON.stringify(guests));

                    fetch('/event-registration', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (!response.ok) {
                            throw data;
                        }
                        return data;
                    })
                    .then(data => {
                        if (data.success) {
                            showToast('Registration successful!', 'success');
                            this.reset();
                            this.querySelector('.custom-size-container').classList.remove('show');
                            if (guestList) guestList.innerHTML = '';
                            updateGuestCount(eventId);
                            mobileInput.classList.remove('valid', 'invalid');
                            this.querySelector('.mobile-success').classList.remove('show');
                        } else {
                            showToast(data.message || 'Registration failed', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const firstValidationError = error?.errors ? Object.values(error.errors)[0]?.[0] : null;
                        showToast(firstValidationError || error?.message || 'An error occurred', 'error');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                });
            });
        });

        function addGuest(eventId) {
            const form = document.querySelector('.registration-form[data-event-id="' + eventId + '"]');
            if (form && form.dataset.registrationOpen !== '1') {
                showToast('Registration is over for this event.', 'error');
                return;
            }

            if (!guestCounters[eventId]) guestCounters[eventId] = 0;
            guestCounters[eventId]++;

            const guestList = document.getElementById('guestList_' + eventId);
            if (!guestList) return;

            const guestItem = document.createElement('div');
            guestItem.className = 'guest-item';
            guestItem.id = 'guest_' + eventId + '_' + guestCounters[eventId];

            guestItem.innerHTML = `
                <div class="guest-item-header">
                    <span style="color:var(--neon-pink);font-weight:800;font-size:15px;">GUEST #${guestCounters[eventId]}</span>
                    <button type="button" class="guest-remove" onclick="removeGuest(${eventId}, ${guestCounters[eventId]})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="form-grid" style="gap:16px;">
                    <div class="form-group" style="margin:0;">
                        <label style="font-size:12px;"><i class="fas fa-user"></i> Name</label>
                        <input type="text" name="guest_name" class="form-control" placeholder="Guest name" style="padding:12px 16px;" required>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label style="font-size:12px;"><i class="fas fa-tshirt"></i> Jersey Name</label>
                        <input type="text" name="guest_jersey_name" class="form-control" placeholder="Back name" style="padding:12px 16px;" required>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label style="font-size:12px;"><i class="fas fa-hashtag"></i> Number</label>
                        <input type="text" name="guest_jersey_number" class="form-control" placeholder="00-99" style="padding:12px 16px;" required>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label style="font-size:12px;"><i class="fas fa-ruler"></i> Size</label>
                        <select name="guest_size" class="form-control guest-size-select" style="padding:12px 16px;" onchange="toggleGuestCustomSize(this)" required>
                            <option value="">Select</option>
                            <option value="SM">SM</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                            <option value="XXXL">XXXL</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label style="font-size:12px;"><i class="fas fa-cut"></i> Sleeve</label>
                        <select name="guest_sleeve" class="form-control" style="padding:12px 16px;" required>
                            <option value="half_sleeve">Half Sleeve</option>
                            <option value="full_sleeve">Full Sleeve</option>
                        </select>
                    </div>
                    <div class="form-group guest-custom-size" style="margin:0;grid-column:1/-1;display:none;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;padding:16px;background:rgba(124,58,237,0.08);border-radius:12px;">
                            <div>
                                <label style="font-size:11px;">Width</label>
                                <input type="text" name="guest_custom_width" class="form-control" placeholder="42" style="padding:10px 12px;">
                            </div>
                            <div>
                                <label style="font-size:11px;">Height</label>
                                <input type="text" name="guest_custom_height" class="form-control" placeholder="28" style="padding:10px 12px;">
                            </div>
                        </div>
                    </div>
                </div>
            `;

            guestList.appendChild(guestItem);
            updateGuestCount(eventId);
            showToast('Guest #' + guestCounters[eventId] + ' added', 'success');
        }

        function toggleGuestCustomSize(select) {
            const customDiv = select.closest('.form-grid').querySelector('.guest-custom-size');
            const widthInput = customDiv.querySelector('input[name="guest_custom_width"]');
            const heightInput = customDiv.querySelector('input[name="guest_custom_height"]');
            if (select.value === 'custom') {
                customDiv.style.display = 'block';
                widthInput.required = true;
                heightInput.required = true;
            } else {
                customDiv.style.display = 'none';
                widthInput.required = false;
                heightInput.required = false;
                widthInput.value = '';
                heightInput.value = '';
            }
        }

        function removeGuest(eventId, guestId) {
            const guestItem = document.getElementById('guest_' + eventId + '_' + guestId);
            if (guestItem) {
                guestItem.style.opacity = '0';
                guestItem.style.transform = 'translateX(-50px)';
                setTimeout(() => {
                    guestItem.remove();
                    updateGuestCount(eventId);
                }, 300);
            }
        }

        function updateGuestCount(eventId) {
            const guestList = document.getElementById('guestList_' + eventId);
            const count = guestList ? guestList.children.length : 0;
            const guestCountEl = document.getElementById('guestCount_' + eventId);
            if (guestCountEl) {
                guestCountEl.textContent = count + ' Guest' + (count !== 1 ? 's' : '');
            }
        }

        function resetForm(eventId) {
            const guestList = document.getElementById('guestList_' + eventId);
            if (guestList) guestList.innerHTML = '';
            guestCounters[eventId] = 0;
            updateGuestCount(eventId);
            showToast('Form reset', 'success');
        }

        function viewParticipants(eventId) {
            const modal = document.getElementById('participantsModal');
            const listContainer = document.getElementById('participantsList');
            const searchInput = document.getElementById('participantSearch');

            modal.classList.add('show');
            listContainer.innerHTML = '<p style="text-align:center;color:var(--text-secondary);">Loading participants...</p>';
            searchInput.value = '';

            fetch('/event-registrations/' + eventId)
                .then(response => response.json())
                .then(data => {
                    if (data.registrations && data.registrations.length > 0) {
                        let allRegistrations = data.registrations;

                        function renderParticipants(filter = '') {
                            let html = '';
                            let count = 0;

                            allRegistrations.forEach((reg, index) => {
                                const searchText = filter.toLowerCase();
                                const matchMain = reg.name.toLowerCase().includes(searchText) ||
                                                reg.mobile.toLowerCase().includes(searchText);

                                let matchGuest = false;
                                if (reg.guests && reg.guests.length > 0) {
                                    matchGuest = reg.guests.some(g =>
                                        g.name.toLowerCase().includes(searchText)
                                    );
                                }

                                if (matchMain || matchGuest) {
                                    count++;
                                    html += `
                                        <div class="participant-card" data-index="${index}">
                                            <div class="participant-header">
                                                <span class="participant-name">#${count} ${reg.name}</span>
                                                ${reg.guests && reg.guests.length > 0 ?
                                                    `<span class="badge bg-primary" style="font-size:12px;padding:4px 10px;border-radius:12px;">${reg.guests.length} Guest(s)</span>`
                                                    : ''}
                                            </div>
                                            <div class="participant-details">
                                                <div class="detail-item"><i class="fas fa-phone"></i> ${reg.mobile}</div>
                                                <div class="detail-item"><i class="fas fa-tshirt"></i> ${reg.jersey_name} #${reg.jersey_number}</div>
                                                <div class="detail-item"><i class="fas fa-ruler"></i> Size: ${reg.size}</div>
                                                <div class="detail-item"><i class="fas fa-cut"></i> ${reg.sleeve_type.replace('_', ' ')}</div>
                                                <div class="detail-item"><i class="fas fa-running"></i> ${reg.play_status ? 'Player' : 'Jersey Only'}</div>
                                            </div>
                                    `;

                                    if (reg.guests && reg.guests.length > 0) {
                                        html += `
                                            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                                                <h6 style="color:var(--neon-pink);margin-bottom:12px;font-size:14px;">
                                                    <i class="fas fa-users"></i> Guest Details:
                                                </h6>
                                        `;

                                        reg.guests.forEach((guest, gIndex) => {
                                            html += `
                                                <div style="background:rgba(236,72,153,0.1);padding:12px;border-radius:10px;margin-bottom:10px;border-left:3px solid var(--neon-pink);">
                                                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:8px;font-size:13px;">
                                                        <div><strong style="color:var(--neon-pink);">Guest #${gIndex + 1}:</strong> ${guest.name}</div>
                                                        <div><i class="fas fa-tshirt"></i> ${guest.jersey_name} #${guest.jersey_number}</div>
                                                        <div><i class="fas fa-ruler"></i> ${guest.size}</div>
                                                        <div><i class="fas fa-cut"></i> ${guest.sleeve_type.replace('_', ' ')}</div>
                                                    </div>
                                                </div>
                                            `;
                                        });

                                        html += `</div>`;
                                    }

                                    html += `</div>`;
                                }
                            });

                            if (count === 0) {
                                html = '<p style="text-align:center;color:var(--text-secondary);">No participants found matching your search</p>';
                            }

                            listContainer.innerHTML = html;
                        }

                        renderParticipants();

                        searchInput.addEventListener('input', function() {
                            renderParticipants(this.value);
                        });
                    } else {
                        listContainer.innerHTML = '<p style="text-align:center;color:var(--text-secondary);">No participants yet</p>';
                    }
                })
                .catch(error => {
                    listContainer.innerHTML = '<p style="text-align:center;color:var(--danger);">Failed to load participants</p>';
                });
        }

        function closeParticipants() {
            document.getElementById('participantsModal').classList.remove('show');
        }

        function showToast(message, type) {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = 'toast toast-' + type;
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-times-circle';

            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fas ${icon}"></i>
                </div>
                <div class="toast-content">
                    <h4>${type === 'success' ? 'Success' : 'Error'}</h4>
                    <p>${message}</p>
                </div>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(120%)';
                setTimeout(() => toast.remove(), 500);
            }, 4500);
        }
    </script>
</body>
</html>

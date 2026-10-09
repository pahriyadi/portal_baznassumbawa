<style>
        :root {
            --baznas-green: #006937;
            --baznas-green-light: #0b7c44;
            --soft-bg: #eef5f1;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: var(--soft-bg);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .app-header {
            background: linear-gradient(135deg, #006937 0%, #0b7c44 100%);
            color: #fff;
            padding: 12px 20px;
            box-shadow: 0 4px 12px rgba(0, 105, 55, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
            max-width: 1200px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .app-header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .app-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .app-logo:hover { color: #F4C10F; }
        .app-nav { display: flex; gap: 8px; flex-wrap: wrap; }
        .nav-link {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: #fff; }
        .nav-link.active { background: rgba(255,255,255,0.25); color: #fff; }

        .container {
            max-width: 800px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: var(--baznas-green);
            font-size: 1.8rem;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 40px;
        }
        .mode-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-top: 20px;
        }
        .mode-card {
            background: #fff;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            color: inherit;
            display: block;
            border: 3px solid transparent;
            transition: all 0.3s ease;
        }
        .mode-card:hover {
            border-color: var(--baznas-green);
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 105, 55, 0.15);
        }
        .mode-card-icon {
            font-size: 3rem;
            margin-bottom: 16px;
        }
        .mode-card h2 {
            color: var(--baznas-green);
            font-size: 1.25rem;
            margin: 0 0 8px;
        }
        .mode-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }
        .mode-card-badge {
            display: inline-block;
            background: #e8f5e9;
            color: var(--baznas-green);
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 12px;
        }
        @media (max-width: 600px) {
            .app-header-inner { flex-direction: column; align-items: stretch; }
            .app-nav { justify-content: center; }
        }
    </style>
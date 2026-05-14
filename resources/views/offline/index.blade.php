<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>LBE — Révision offline</title>
    <style>
        :root {
            --primary:       #2c5f8a;
            --primary-dark:  #1e4268;
            --primary-light: #d6e8f5;
            --success:       #2e7d32;
            --success-light: #e8f5e9;
            --danger:        #c62828;
            --danger-light:  #fdecea;
            --warning:       #e65100;
            --warning-light: #fff3e0;
            --text:          #1a1a1a;
            --text-muted:    #666;
            --border:        #c8d8e8;
            --bg:            #f4f7fb;
            --white:         #ffffff;
            --card-bg:       #ffffff;
            --card-revealed: #fffde7;
            --radius:        10px;
            --shadow:        0 2px 8px rgba(0,0,0,0.10);
            --shadow-lg:     0 4px 16px rgba(0,0,0,0.14);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Header ─────────────────────────────────────────────── */
        header {
            background: var(--primary);
            color: white;
            padding: 0 1.2rem;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-brand { font-size: 1.05rem; font-weight: 700; letter-spacing: 0.3px; white-space: nowrap; }
        .header-right  { display: flex; align-items: center; gap: 0.6rem; }
        .status-pill {
            display: flex; align-items: center; gap: 0.35rem;
            background: rgba(255,255,255,0.15);
            padding: 0.2rem 0.6rem; border-radius: 20px;
            font-size: 0.78rem; color: rgba(255,255,255,0.9);
        }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; background: #aaa; flex-shrink: 0; }
        .status-dot.online  { background: #66bb6a; }
        .status-dot.offline { background: #ef5350; }

        /* ── Boutons ─────────────────────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.3rem;
            padding: 0.4rem 0.9rem; border-radius: var(--radius);
            font-size: 0.85rem; font-weight: 600; border: none; cursor: pointer;
            transition: background 0.15s, opacity 0.15s;
            text-decoration: none; white-space: nowrap; font-family: inherit;
        }
        .btn:disabled { opacity: 0.45; cursor: not-allowed; }
        .btn-white  { background: white; color: var(--primary); }
        .btn-white:hover:not(:disabled)  { background: #e8f0fe; }
        .btn-ghost  { background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3); }
        .btn-ghost:hover:not(:disabled)  { background: rgba(255,255,255,0.25); }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover:not(:disabled) { background: var(--primary-dark); }
        .btn-secondary { background: #e8eef5; color: var(--primary); border: 1px solid var(--border); }
        .btn-secondary:hover:not(:disabled) { background: var(--primary-light); }
        .btn-sm { padding: 0.3rem 0.7rem; font-size: 0.80rem; }

        /* ── Alertes ─────────────────────────────────────────────── */
        .alert { padding: 0.65rem 1rem; border-radius: var(--radius); margin: 0.6rem 1rem; font-size: 0.88rem; }
        .alert-info    { background: #e1f5fe; color: #0277bd; border-left: 4px solid #0277bd; }
        .alert-success { background: var(--success-light); color: var(--success); border-left: 4px solid var(--success); }
        .alert-danger  { background: var(--danger-light);  color: var(--danger);  border-left: 4px solid var(--danger); }
        .alert-warning { background: var(--warning-light); color: var(--warning); border-left: 4px solid var(--warning); }

        /* ── Panneau de contrôle ─────────────────────────────────── */
        .controls { background: var(--white); border-bottom: 1px solid var(--border); padding: 0.8rem 1rem; }
        .controls-row { display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center; margin-bottom: 0.5rem; }
        .controls-row:last-child { margin-bottom: 0; }
        .controls-label {
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.5px; color: var(--text-muted); width: 100%; margin-bottom: 0.2rem;
        }
        .controls-sep { width: 100%; border: none; border-top: 1px solid var(--border); margin: 0.4rem 0; }

        .form-control {
            padding: 0.4rem 0.7rem; border: 1px solid var(--border); border-radius: var(--radius);
            font-size: 0.88rem; font-family: inherit; background: white; color: var(--text);
            transition: border-color 0.15s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 2px rgba(44,95,138,0.12); }
        select.form-control { min-width: 130px; }
        input[type="text"].form-control   { min-width: 130px; }
        input[type="number"].form-control { width: 100px; min-width: unset; }

        .stats-bar {
            font-size: 0.78rem; color: var(--text-muted);
            margin-top: 0.5rem; padding-top: 0.4rem; border-top: 1px solid var(--border);
        }
        .stats-bar strong { color: var(--primary); }

        /* ── Zone principale ─────────────────────────────────────── */
        main { padding: 0.8rem 0.8rem 4rem; }

        /* ── Grille de fiches ────────────────────────────────────── */
        .cards-grid { display: flex; flex-direction: column; gap: 0.6rem; }

        /* ── Fiche ───────────────────────────────────────────────── */
        .card-item {
            background: var(--card-bg); border-radius: var(--radius);
            box-shadow: var(--shadow); border: 1px solid var(--border);
            overflow: hidden; transition: box-shadow 0.15s;
        }
        .card-item.revealed { background: var(--card-revealed); border-color: #f9a825; }
        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.45rem 0.8rem; background: var(--primary-light); border-bottom: 1px solid var(--border);
        }
        .card-meta { display: flex; gap: 0.4rem; align-items: center; flex-wrap: wrap; }
        .badge { font-size: 0.72rem; font-weight: 700; padding: 0.1rem 0.5rem; border-radius: 20px; }
        .badge-lang { background: var(--primary); color: white; }
        .badge-ref  { background: #e8eaf6; color: #3949ab; }

        .card-body { padding: 0.75rem 0.8rem; }
        .card-french { font-size: 1rem; font-weight: 600; color: var(--text); line-height: 1.4; margin-bottom: 0.5rem; }

        .card-translation-wrapper { margin-top: 0.4rem; }
        .card-translation {
            font-size: 0.97rem; color: #5d4037; font-weight: 500; line-height: 1.4;
            padding: 0.5rem 0.7rem; background: rgba(249,168,37,0.12);
            border-radius: 6px; border-left: 3px solid #f9a825;
        }
        .card-translation-hidden {
            display: flex; align-items: center; justify-content: center; gap: 0.4rem;
            padding: 0.5rem; color: var(--text-muted); font-size: 0.82rem; font-style: italic;
            cursor: pointer; border: 1px dashed var(--border); border-radius: 6px;
            user-select: none; -webkit-tap-highlight-color: transparent; transition: background 0.15s;
        }
        .card-translation-hidden:hover { background: var(--primary-light); }

        .card-note-toggle { margin-top: 0.5rem; }
        .btn-note {
            font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 20px;
            border: 1px solid #9c27b0; background: white; color: #6a1b9a;
            cursor: pointer; font-family: inherit; transition: background 0.15s;
        }
        .btn-note:hover { background: #f3e5f5; }
        .card-note {
            margin-top: 0.4rem; font-size: 0.82rem; color: #6a1b9a;
            padding: 0.4rem 0.6rem; background: #f3e5f5;
            border-radius: 6px; border-left: 3px solid #9c27b0;
        }

        /* ── Écrans spéciaux ─────────────────────────────────────── */
        #screen-loading, #screen-nodata { text-align: center; padding: 3rem 1.5rem; color: var(--text-muted); }
        #screen-nodata h2 { font-size: 1.2rem; margin-bottom: 0.8rem; color: var(--primary); }
        .spinner {
            width: 40px; height: 40px; border: 4px solid var(--primary-light);
            border-top-color: var(--primary); border-radius: 50%;
            animation: spin 0.8s linear infinite; margin: 1rem auto;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Toast ───────────────────────────────────────────────── */
        #toast {
            position: fixed; bottom: 1.5rem; left: 50%;
            transform: translateX(-50%) translateY(4rem);
            background: #333; color: white; padding: 0.6rem 1.2rem;
            border-radius: 20px; font-size: 0.88rem; opacity: 0;
            transition: transform 0.3s, opacity 0.3s; z-index: 999;
            white-space: nowrap; pointer-events: none;
        }
        #toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }

        @media (min-width: 600px) {
            .cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 0.8rem; }
            main { padding: 1rem 1rem 4rem; }
        }
    </style>
</head>
<body>

<header>
    <span class="header-brand">📚 LBE — Révision offline</span>
    <div class="header-right">
        <div class="status-pill">
            <div class="status-dot" id="statusDot"></div>
            <span id="statusText">…</span>
        </div>
        <button class="btn btn-white btn-sm" id="btnSync" onclick="syncFromServer()">↻ Sync</button>
        <a href="{{ url('/') }}" class="btn btn-ghost btn-sm">← Retour</a>
    </div>
</header>

<div id="alertZone"></div>

<div id="screen-loading">
    <div class="spinner"></div>
    <p>Chargement…</p>
</div>

<div id="screen-nodata" style="display:none">
    <h2>Aucune donnée locale</h2>
    <p>Connectez-vous au réseau et appuyez sur <strong>↻ Sync</strong> pour télécharger les fiches.</p>
    <div style="margin-top:1.2rem;">
        <button class="btn btn-primary" onclick="syncFromServer()">↻ Synchroniser maintenant</button>
    </div>
</div>

<div id="screen-main" style="display:none">
    <div class="controls">
        <div class="controls-label">Filtres</div>
        <div class="controls-row">
            <select class="form-control" id="filterLang" onchange="applyFilters()">
                <option value="">Toutes les langues</option>
                <option value="english">Anglais</option>
                <option value="german">Allemand</option>
                <option value="spanish">Espagnol</option>
            </select>
            <input class="form-control" type="text" id="filterRef"
                   placeholder="Référence…" oninput="applyFilters()">
            <input class="form-control" type="number" id="randomCount"
                   placeholder="N aléatoires" min="1">
            <button class="btn btn-primary btn-sm" onclick="applyFilters()">Afficher</button>
        </div>
        <hr class="controls-sep">
        <div class="controls-label">Actions</div>
        <div class="controls-row">
            <button class="btn btn-secondary btn-sm" onclick="revealAll()">👁 Tout révéler</button>
            <button class="btn btn-secondary btn-sm" onclick="hideAll()">🙈 Tout cacher</button>
            <button class="btn btn-secondary btn-sm" onclick="shuffleCards()">🔀 Mélanger</button>
        </div>
        <div class="stats-bar" id="statsBar"></div>
    </div>
    <main>
        <div class="cards-grid" id="cardsGrid"></div>
        <div id="screen-empty" style="display:none; text-align:center; padding:2rem; color:var(--text-muted);">
            <p>Aucune fiche trouvée — modifiez les filtres.</p>
        </div>
    </main>
</div>

<div id="toast"></div>

<script>
    const API_URL    = '{{ url('/api/cards/export') }}';
    const DB_NAME    = 'lbe_offline';
    const DB_VERSION = 1;
    const STORE_NAME = 'cards';

    let allCards     = [];
    let displayCards = [];

    function openDB() {
        return new Promise((resolve, reject) => {
            const req = indexedDB.open(DB_NAME, DB_VERSION);
            req.onupgradeneeded = e => {
                const db = e.target.result;
                if (!db.objectStoreNames.contains(STORE_NAME))
                    db.createObjectStore(STORE_NAME, { keyPath: 'id' });
            };
            req.onsuccess = e => resolve(e.target.result);
            req.onerror   = e => reject(e.target.error);
        });
    }

    async function saveCardsToDB(cards) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            store.clear();
            cards.forEach(c => store.put(c));
            tx.oncomplete = () => resolve();
            tx.onerror    = e => reject(e.target.error);
        });
    }

    async function loadCardsFromDB() {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx  = db.transaction(STORE_NAME, 'readonly');
            const req = tx.objectStore(STORE_NAME).getAll();
            req.onsuccess = e => resolve(e.target.result);
            req.onerror   = e => reject(e.target.error);
        });
    }

    async function syncFromServer() {
        if (!navigator.onLine) {
            showAlert('Vous êtes hors ligne. Connectez-vous pour synchroniser.', 'warning');
            return;
        }
        const btn = document.getElementById('btnSync');
        btn.disabled = true; btn.textContent = '…';
        try {
            const resp = await fetch(API_URL);
            if (!resp.ok) throw new Error(`Erreur serveur : ${resp.status}`);
            const data = await resp.json();
            await saveCardsToDB(data.cards);
            allCards = data.cards;
            applyFilters();
            showScreen('main');
            const syncDate = new Date().toLocaleString('fr-FR');
            localStorage.setItem('lbe_last_sync',  syncDate);
            localStorage.setItem('lbe_sync_count', data.count);
            showAlert(`✓ ${data.count} fiches synchronisées (${syncDate})`, 'success');
            toast(`${data.count} fiches synchronisées`);
        } catch (err) {
            showAlert('Erreur lors de la synchronisation : ' + err.message, 'danger');
        } finally {
            btn.disabled = false; btn.textContent = '↻ Sync';
        }
    }

    async function init() {
        updateOnlineStatus();
        window.addEventListener('online',  updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
        try {
            const cards = await loadCardsFromDB();
            if (cards.length === 0) {
                showScreen('nodata');
            } else {
                allCards = cards;
                applyFilters();
                showScreen('main');
                const lastSync = localStorage.getItem('lbe_last_sync');
                const count    = localStorage.getItem('lbe_sync_count');
                if (lastSync) showAlert(`Données locales : ${count} fiches — dernière sync : ${lastSync}`, 'info');
            }
        } catch (err) {
            showAlert('Erreur IndexedDB : ' + err.message, 'danger');
            showScreen('nodata');
        }
    }

    function applyFilters() {
        const lang = document.getElementById('filterLang').value.toLowerCase();
        const ref  = document.getElementById('filterRef').value.toLowerCase().trim();
        const n    = parseInt(document.getElementById('randomCount').value, 10);

        let filtered = allCards.filter(c => {
            const matchLang = !lang || c.language === lang;
            const matchRef  = !ref  || (c.reference || '').toLowerCase().includes(ref);
            return matchLang && matchRef;
        });

        if (!isNaN(n) && n > 0 && n < filtered.length) {
            for (let i = filtered.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [filtered[i], filtered[j]] = [filtered[j], filtered[i]];
            }
            filtered = filtered.slice(0, n);
        }

        displayCards = filtered;
        renderCards();
    }

    function shuffleCards() {
        for (let i = displayCards.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [displayCards[i], displayCards[j]] = [displayCards[j], displayCards[i]];
        }
        renderCards();
        toast('Fiches mélangées');
    }

    function renderCards() {
        const grid  = document.getElementById('cardsGrid');
        const empty = document.getElementById('screen-empty');
        const stats = document.getElementById('statsBar');
        grid.innerHTML = '';
        if (displayCards.length === 0) {
            empty.style.display = 'block';
            stats.innerHTML = 'Aucune fiche';
            return;
        }
        empty.style.display = 'none';
        const shown = displayCards.length;
        const total = allCards.length;
        stats.innerHTML = `<strong>${shown}</strong> fiche${shown > 1 ? 's' : ''} affichée${shown > 1 ? 's' : ''} sur <strong>${total}</strong> en base`;
        const langLabel = { english: 'Anglais', german: 'Allemand', spanish: 'Espagnol' };
        displayCards.forEach(card => {
            const el     = document.createElement('div');
            el.className = 'card-item';
            const hasNote = card.note && card.note.trim() !== '';
            const label   = langLabel[card.language] || card.language;
            el.innerHTML = `
                <div class="card-header">
                    <div class="card-meta">
                        <span class="badge badge-lang">${label}</span>
                        ${card.reference && card.reference !== 'XXXXXXXX'
                            ? `<span class="badge badge-ref">${escapeHtml(card.reference)}</span>` : ''}
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-french">${escapeHtml(card.french)}</div>
                    <div class="card-translation-wrapper">
                        <div class="card-translation-hidden" onclick="toggleTranslation(this)">
                            👆 Appuyer pour voir la traduction
                        </div>
                        <div class="card-translation" style="display:none">${escapeHtml(card.translation)}</div>
                    </div>
                    ${hasNote ? `
                    <div class="card-note-toggle">
                        <button class="btn-note" onclick="toggleNote(this)">📝 Voir la note</button>
                        <div class="card-note" style="display:none">${escapeHtml(card.note)}</div>
                    </div>` : ''}
                </div>`;
            grid.appendChild(el);
        });
    }

    function toggleTranslation(hiddenEl) {
        const wrapper     = hiddenEl.closest('.card-translation-wrapper');
        const translation = wrapper.querySelector('.card-translation');
        const cardItem    = hiddenEl.closest('.card-item');
        const isHidden    = translation.style.display === 'none';
        if (isHidden) {
            hiddenEl.style.display    = 'none';
            translation.style.display = 'block';
            cardItem.classList.add('revealed');
        } else {
            hiddenEl.style.display    = 'flex';
            translation.style.display = 'none';
            cardItem.classList.remove('revealed');
        }
    }

    function toggleNote(btn) {
        const note    = btn.nextElementSibling;
        const visible = note.style.display !== 'none';
        note.style.display = visible ? 'none' : 'block';
        btn.textContent    = visible ? '📝 Voir la note' : '📝 Masquer la note';
    }

    function revealAll() {
        document.querySelectorAll('.card-translation-hidden').forEach(el => {
            el.style.display = 'none';
            el.closest('.card-translation-wrapper').querySelector('.card-translation').style.display = 'block';
            el.closest('.card-item').classList.add('revealed');
        });
    }

    function hideAll() {
        document.querySelectorAll('.card-translation').forEach(el => {
            el.style.display = 'none';
            el.closest('.card-translation-wrapper').querySelector('.card-translation-hidden').style.display = 'flex';
            el.closest('.card-item').classList.remove('revealed');
        });
        document.querySelectorAll('.card-note').forEach(note => {
            note.style.display = 'none';
            const btn = note.previousElementSibling;
            if (btn) btn.textContent = '📝 Voir la note';
        });
    }

    function showScreen(name) {
        document.getElementById('screen-loading').style.display = name === 'loading' ? 'block' : 'none';
        document.getElementById('screen-nodata').style.display  = name === 'nodata'  ? 'block' : 'none';
        document.getElementById('screen-main').style.display    = name === 'main'    ? 'block' : 'none';
    }

    function showAlert(msg, type = 'info') {
        const zone = document.getElementById('alertZone');
        zone.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
        setTimeout(() => { zone.innerHTML = ''; }, 6000);
    }

    function toast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2500);
    }

    function updateOnlineStatus() {
        const dot  = document.getElementById('statusDot');
        const text = document.getElementById('statusText');
        if (navigator.onLine) {
            dot.className    = 'status-dot online';
            text.textContent = 'En ligne';
        } else {
            dot.className    = 'status-dot offline';
            text.textContent = 'Hors ligne';
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    document.addEventListener('DOMContentLoaded', init);
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Project Tracker</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    

    <style>
        :root {
            --bg: #f0f4f8;
            --surface: #ffffff;
            --surface2: #f8fafc;
            --border: #dde3ec;
            --border2: #c8d3e0;
            --blue: #2563eb;
            --blue-light: #eff6ff;
            --blue-mid: #3b82f6;
            --blue-dark: #1e40af;
            --slate: #475569;
            --slate-light: #94a3b8;
            --text: #1e293b;
            --success: #059669;
            --success-bg: #ecfdf5;
            --warning: #d97706;
            --warning-bg: #fffbeb;
            --danger: #dc2626;
            --danger-bg: #fef2f2;
            --font: 'Plus Jakarta Sans', sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --radius: 10px;
            --shadow: 0 1px 3px rgba(0, 0, 0, .08), 0 1px 2px rgba(0, 0, 0, .04);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, .08), 0 2px 4px rgba(0, 0, 0, .04);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font);
            font-size: 14px;
            min-height: 100vh;
            line-height: 1.5;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border2);
            border-radius: 99px;
        }

        .header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 1.5rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 30;
            box-shadow: var(--shadow);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            font-size: 1rem;
            color: var(--blue-dark);
            letter-spacing: -0.02em;
        }

        .logo-icon {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, var(--blue), var(--blue-mid));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.85rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            font-family: var(--font);
            cursor: pointer;
            border: none;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--blue);
            color: #fff;
            box-shadow: 0 1px 3px rgba(37, 99, 235, .3);
        }

        .btn-primary:hover {
            background: var(--blue-dark);
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success);
            color: #fff;
            box-shadow: 0 1px 3px rgba(5, 150, 105, .3);
        }

        .btn-success:hover {
            background: #047857;
            transform: translateY(-1px);
        }

        .btn-ghost {
            background: transparent;
            color: var(--slate);
            border: 1px solid var(--border2);
        }

        .btn-ghost:hover {
            background: var(--surface2);
            border-color: var(--blue-mid);
            color: var(--blue);
        }

        .btn-danger {
            background: transparent;
            color: var(--danger);
            border: 1px solid #fca5a5;
        }

        .btn-danger:hover {
            background: var(--danger-bg);
        }

        .filter-bar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0.6rem 1.5rem;
            display: flex;
            gap: 0.6rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--slate-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .filter-input,
        .filter-select {
            background: var(--surface2);
            border: 1px solid var(--border);
            color: var(--text);
            padding: 0.38rem 0.7rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-family: var(--font);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .filter-input:focus,
        .filter-select:focus {
            border-color: var(--blue-mid);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
        }

        .main {
            padding: 1.5rem;
            max-width: 960px;
            margin: 0 auto;
        }

        .project-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: 1rem;
            box-shadow: var(--shadow);
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .project-card:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--border2);
        }

        .project-header {
            padding: 1rem 1.25rem;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .project-meta {
            flex: 1;
            min-width: 0;
        }

        .project-title-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 0.2rem;
        }

        .project-name {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.01em;
        }

        .project-dates {
            font-size: 0.7rem;
            color: var(--slate-light);
            font-family: var(--mono);
            margin-bottom: 0.65rem;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.7rem;
            color: var(--slate-light);
            margin-bottom: 0.3rem;
        }

        .progress-pct {
            color: var(--blue);
            font-weight: 600;
            font-family: var(--mono);
        }

        .progress-track {
            height: 6px;
            background: var(--blue-light);
            border-radius: 99px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, var(--blue), var(--blue-mid));
            transition: width 0.5s ease;
        }

        .dep-tags {
            display: flex;
            gap: 0.3rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }

        .dep-tag {
            font-size: 0.65rem;
            padding: 0.1rem 0.5rem;
            border-radius: 99px;
            background: var(--blue-light);
            color: var(--blue);
            border: 1px solid #bfdbfe;
            font-family: var(--mono);
        }

        .project-actions {
            display: flex;
            gap: 0.4rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .icon-btn {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            border: 1px solid var(--border);
            background: var(--surface2);
            color: var(--slate-light);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: all 0.15s;
        }

        .icon-btn:hover {
            border-color: var(--blue-mid);
            color: var(--blue);
            background: var(--blue-light);
        }

        .icon-btn.green:hover {
            border-color: var(--success);
            color: var(--success);
            background: var(--success-bg);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.18rem 0.55rem;
            border-radius: 99px;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            font-family: var(--mono);
        }

        .badge-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .badge-draft {
            background: #f1f5f9;
            color: var(--slate);
            border: 1px solid var(--border);
        }

        .badge-draft .badge-dot {
            background: var(--slate-light);
        }

        .badge-in_progress {
            background: #eff6ff;
            color: var(--blue);
            border: 1px solid #bfdbfe;
        }

        .badge-in_progress .badge-dot {
            background: var(--blue-mid);
            animation: pulse 1.5s infinite;
        }

        .badge-done {
            background: var(--success-bg);
            color: var(--success);
            border: 1px solid #a7f3d0;
        }

        .badge-done .badge-dot {
            background: var(--success);
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .35
            }
        }

        .task-list {
            border-top: 1px solid var(--border);
            background: var(--surface2);
        }

        .task-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 1.25rem;
            border-bottom: 1px solid var(--border);
            transition: background 0.1s;
            gap: 0.75rem;
        }

        .task-row:last-child {
            border-bottom: none;
        }

        .task-row:hover {
            background: var(--blue-light);
        }

        .task-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            min-width: 0;
        }

        .task-name {
            font-size: 0.82rem;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .task-bobot {
            font-size: 0.65rem;
            color: var(--slate-light);
            font-family: var(--mono);
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
            flex-shrink: 0;
        }

        .task-right {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            flex-shrink: 0;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: var(--slate-light);
        }

        .empty-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            opacity: 0.4;
        }

        .empty-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--slate);
            margin-bottom: 0.25rem;
        }

        .empty-sub {
            font-size: 0.82rem;
        }

        .task-empty {
            padding: 0.7rem 1.25rem;
            font-size: 0.78rem;
            color: var(--slate-light);
            font-style: italic;
        }

        #overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .35);
            backdrop-filter: blur(2px);
            z-index: 40;
            display: none;
            opacity: 0;
            transition: opacity 0.25s;
        }

        #overlay.active {
            display: block;
            opacity: 1;
        }

        #slide-panel {
            position: fixed;
            top: 0;
            right: 0;
            height: 100%;
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            border-left: 1px solid var(--border);
            box-shadow: -4px 0 24px rgba(0, 0, 0, .1);
            z-index: 50;
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #slide-panel.open {
            transform: translateX(0);
        }

        .panel-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--surface2);
        }

        .panel-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text);
        }

        .panel-close {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--slate-light);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            transition: all 0.15s;
        }

        .panel-close:hover {
            border-color: var(--danger);
            color: var(--danger);
            background: var(--danger-bg);
        }

        .panel-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem;
        }

        .panel-footer {
            padding: 0.9rem 1.25rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--surface2);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--slate);
            margin-bottom: 0.35rem;
        }

        .form-control {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text);
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 0.84rem;
            font-family: var(--font);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--blue-mid);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .form-hint {
            font-size: 0.72rem;
            color: var(--slate-light);
            margin-top: 0.3rem;
        }

        .form-error {
            background: var(--danger-bg);
            border: 1px solid #fca5a5;
            color: var(--danger);
            padding: 0.55rem 0.75rem;
            border-radius: 8px;
            font-size: 0.78rem;
            margin-top: 0.5rem;
            display: none;
        }

        .skeleton {
            background: linear-gradient(90deg, var(--border) 25%, #e2e8f0 50%, var(--border) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 6px;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0
            }

            100% {
                background-position: 200% 0
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        .dep-section {
            margin-top: 1.25rem;
            border-top: 1px solid var(--border);
            padding-top: 1rem;
        }

        .dep-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--slate);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .dep-add-row {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .dep-add-row .form-control {
            flex: 1;
        }

        .dep-add-row .btn {
            flex-shrink: 0;
            padding: .45rem .75rem;
            font-size: .78rem;
        }

        .dep-list {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .dep-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.4rem 0.65rem;
            font-size: 0.8rem;
        }

        .dep-item-name {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dep-item-arrow {
            font-size: 0.65rem;
            color: var(--slate-light);
            flex-shrink: 0;
        }

        .dep-remove {
            width: 22px;
            height: 22px;
            border-radius: 5px;
            border: 1px solid #fca5a5;
            background: transparent;
            color: var(--danger);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            flex-shrink: 0;
            transition: all .15s;
        }

        .dep-remove:hover {
            background: var(--danger-bg);
        }

        .dep-empty {
            font-size: 0.78rem;
            color: var(--slate-light);
            font-style: italic;
            text-align: center;
            padding: 0.5rem;
        }

        .dep-section {
            margin-top: 1.25rem;
            border-top: 1px solid var(--border);
            padding-top: 1rem;
        }

        .dep-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--slate);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .dep-add-row {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .dep-add-row .form-control {
            flex: 1;
        }

        .dep-add-row .btn {
            flex-shrink: 0;
            padding: .45rem .75rem;
            font-size: .78rem;
        }

        .dep-list {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .dep-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.4rem 0.65rem;
            font-size: 0.8rem;
            gap: 0.5rem;
        }

        .dep-item-name {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .dep-remove {
            width: 22px;
            height: 22px;
            border-radius: 5px;
            border: 1px solid #fca5a5;
            background: transparent;
            color: var(--danger);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
            transition: all .15s;
        }

        .dep-remove:hover {
            background: var(--danger-bg);
        }

        .dep-empty {
            font-size: 0.78rem;
            color: var(--slate-light);
            font-style: italic;
            padding: 0.4rem 0;
        }

        .swal2-popup {
            font-family: var(--font) !important;
            border-radius: 12px !important;
        }

        .swal2-confirm {
            background: var(--blue) !important;
            font-family: var(--font) !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
        }

        .swal2-cancel {
            font-family: var(--font) !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="header">
        <div class="logo">
            <div class="logo-icon">◈</div>
            Project Tracker
        </div>
        <div style="display:flex;gap:.5rem">
            <button class="btn btn-primary" id="btn-add-project">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="3">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Add Project
            </button>
            <button class="btn btn-success" id="btn-add-task">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="3">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Add Task
            </button>
        </div>
    </header>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <span class="filter-label">Filter:</span>
        <select id="filter-status" class="filter-select">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="in_progress">In Progress</option>
            <option value="done">Done</option>
        </select>
        <input id="filter-search" type="text" class="filter-input" placeholder="Cari task..." style="width:210px">
        <button class="btn btn-ghost" id="btn-clear-filter" style="padding:.38rem .75rem">Reset</button>
    </div>

    <!-- Main -->
    <main class="main">
        <div id="projects-container">
            <div id="loading-skel">
                <div class="project-card" style="padding:1.25rem">
                    <div class="skeleton" style="height:14px;width:38%;margin-bottom:.6rem"></div>
                    <div class="skeleton" style="height:9px;width:22%;margin-bottom:1rem"></div>
                    <div class="skeleton" style="height:6px;width:100%"></div>
                </div>
                <div class="project-card" style="padding:1.25rem">
                    <div class="skeleton" style="height:14px;width:52%;margin-bottom:.6rem"></div>
                    <div class="skeleton" style="height:9px;width:28%;margin-bottom:1rem"></div>
                    <div class="skeleton" style="height:6px;width:100%"></div>
                </div>
            </div>
        </div>
    </main>

    <!-- Overlay -->
    <div id="overlay"></div>

    <!-- Slide Panel -->
    <div id="slide-panel">
        <div class="panel-header">
            <span class="panel-title" id="panel-title">Add Project</span>
            <button class="panel-close" id="btn-close-panel">✕</button>
        </div>

        <div class="panel-body">

            <!-- Project Form -->
            <form id="project-form" style="display:none" onsubmit="return false">
                <input type="hidden" id="project-id">
                <div class="form-group">
                    <label class="form-label">Nama Project *</label>
                    <input type="text" id="project-nama" class="form-control" placeholder="Contoh: Website Redesign">
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Start Date *</label>
                        <input type="date" id="project-start" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Date *</label>
                        <input type="date" id="project-end" class="form-control">
                    </div>
                </div>
                <div class="form-error" id="project-error"></div>

                <div class="dep-section" id="project-dep-section" style="display:none">
                    <div class="dep-section-title">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                        </svg>
                        Project Dependencies
                    </div>
                    <div class="dep-add-row">
                        <select id="proj-dep-select" class="form-control">
                            <option value="">-- Pilih project yang harus selesai dulu --</option>
                        </select>
                        <button class="btn btn-primary" id="btn-proj-dep-add">+ Tambah</button>
                    </div>
                    <div class="dep-list" id="proj-dep-list">
                        <div class="dep-empty">Belum ada dependency</div>
                    </div>
                </div>
            </form>

            <!-- Task Form -->
            <form id="task-form" style="display:none" onsubmit="return false">
                <input type="hidden" id="task-id">
                <div class="form-group">
                    <label class="form-label">Project *</label>
                    <select id="task-project-id" class="form-control">
                        <option value="">-- Pilih Project --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Parent Task</label>
                    <select id="task-parent-id" class="form-control">
                        <option value="">-- Root Task (tanpa parent) --</option>
                    </select>
                    <div class="form-hint">Kosongkan jika ini task utama</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Task *</label>
                    <input type="text" id="task-nama" class="form-control" placeholder="Contoh: Setup Database">
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Bobot *</label>
                        <input type="number" id="task-bobot" class="form-control" min="1" value="1">
                        <div class="form-hint">Untuk kalkulasi progress</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select id="task-status" class="form-control">
                            <option value="draft">Draft</option>
                            <option value="in_progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                </div>
                <div class="form-error" id="task-error"></div>

                <div class="dep-section" id="task-dep-section" style="display:none">
                    <div class="dep-section-title">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                        </svg>
                        Task Dependencies
                    </div>
                    <div class="dep-add-row">
                        <select id="task-dep-select" class="form-control">
                            <option value="">-- Pilih task yang harus selesai dulu --</option>
                        </select>
                        <button class="btn btn-primary" id="btn-task-dep-add">+ Tambah</button>
                    </div>
                    <div class="dep-list" id="task-dep-list">
                        <div class="dep-empty">Belum ada dependency</div>
                    </div>
                </div>
            </form>

        </div>

        <div class="panel-footer">
            <button class="btn btn-danger" id="btn-hapus" style="display:none">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5">
                    <polyline points="3,6 5,6 21,6" />
                    <path d="M19,6l-1,14H6L5,6" />
                    <path d="M10,11v6M14,11v6" />
                    <path d="M9,6V4h6v2" />
                </svg>
                Hapus
            </button>
            <div style="display:flex;gap:.5rem;margin-left:auto">
                <button class="btn btn-ghost" id="btn-cancel">Batal</button>
                <button class="btn btn-primary" id="btn-save">Simpan</button>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.all.min.js"></script>

    <script>
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });

            const BASE = '{{ url('') }}';

            const ROUTES = {
                projects: {
                    list: '{{ route('projects.list') }}',
                    store: '{{ route('projects.store') }}',
                    show: id => `${BASE}/projects/${id}`,
                    update: id => `${BASE}/projects/${id}`,
                    destroy: id => `${BASE}/projects/${id}`,
                    tasks: id => `${BASE}/projects/${id}/tasks`,
                    tasksStore: id => `${BASE}/projects/${id}/tasks`,
                    depStore: id => `${BASE}/projects/${id}/dependencies`,
                    depDestroy: (id, depId) => `${BASE}/projects/${id}/dependencies/${depId}`,
                },
                tasks: {
                    show: id => `${BASE}/tasks/${id}`,
                    update: id => `${BASE}/tasks/${id}`,
                    destroy: id => `${BASE}/tasks/${id}`,
                    depStore: id => `${BASE}/tasks/${id}/dependencies`,
                    depDestroy: (id, depId) => `${BASE}/tasks/${id}/dependencies/${depId}`,
                },
            };

            let projects = [];
            let projectMap = {};
            let taskMap = {}; 
            let panelMode = null;
            let editId = null;
            let forProject = null;
            let searchTimer = null;

            const toast = {
                ok: msg => Swal.fire({
                    icon: 'success',
                    title: msg,
                    showConfirmButton: false,
                    timer: 1800,
                    timerProgressBar: true,
                }),
                err: msg => Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: msg,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#2563eb',
                }),
                warn: msg => Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: msg,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb',
                }),
            };

            function api(method, url, body) {
                const opts = {
                    method,
                    url
                };
                if (body !== undefined) opts.data = JSON.stringify(body);
                return $.ajax(opts);
            }

            async function load() {
                try {
                    const res = await api('GET', ROUTES.projects.list);
                    projects = res.data || [];

                    projectMap = {};
                    taskMap = {};
                    projects.forEach(p => {
                        projectMap[p.id] = p;
                    });

                    const status = $('#filter-status').val();
                    const search = $('#filter-search').val();

                    await Promise.all(projects.map(p => fetchTasks(p, status, search)));
                    render();
                } catch (e) {
                    toast.err('Gagal memuat data.');
                }
            }

            async function fetchTasks(project, status, search) {
                const params = {};
                if (status) params.status = status;
                if (search) params.search = search;
                const qs = $.param(params);
                const url = ROUTES.projects.tasks(project.id) + (qs ? '?' + qs : '');
                try {
                    const res = await api('GET', url);
                    project._tasks = res.data || [];
                    project._tasks.forEach(t => indexTask(t));
                } catch {
                    project._tasks = [];
                }
            }

            function indexTask(t) {
                taskMap[t.id] = t;
                (t.all_children || []).forEach(c => indexTask(c));
            }

            function render() {
                $('#loading-skel').remove();
                const $c = $('#projects-container').empty();

                if (!projects.length) {
                    $c.html(`<div class="empty-state">
                <div class="empty-icon">📋</div>
                <div class="empty-title">Belum ada project</div>
                <div class="empty-sub">Klik "Add Project" untuk mulai</div>
            </div>`);
                    return;
                }

                projects.forEach(p => $c.append(makeProjectCard(p)));
            }

            function makeProjectCard(p) {
                const pct = parseFloat(p.completion_progress) || 0;
                const deps = (p.dependencies || [])
                    .map(d => `<span class="dep-tag">${esc(d.nama)}</span>`)
                    .join('');

                const $card = $(`
            <div class="project-card" data-id="${p.id}">
                <div class="project-header">
                    <div class="project-meta">
                        <div class="project-title-row">
                            <span class="project-name">${esc(p.nama)}</span>
                            ${badge(p.status)}
                        </div>
                        <div class="project-dates">
                            ${fmtDate(p.start_date)} → ${fmtDate(p.end_date)}
                        </div>
                        <div class="progress-label">
                            <span>Completion</span>
                            <span class="progress-pct">${pct.toFixed(1)}%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="width:${pct}%"></div>
                        </div>
                        ${deps ? `<div class="dep-tags">${deps}</div>` : ''}
                    </div>
                    <div class="project-actions">
                        <button class="icon-btn green btn-proj-add-task"
                            data-pid="${p.id}" title="Add Task">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                        <button class="icon-btn btn-proj-edit"
                            data-pid="${p.id}" title="Edit Project">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                    </div>
                </div>
                <div class="task-list"></div>
            </div>`);

                /* Build task rows */
                const $tl = $card.find('.task-list');
                if (p._tasks && p._tasks.length) {
                    p._tasks.forEach(t => makeTaskRow(t, $tl, 0));
                } else {
                    $tl.html('<div class="task-empty">Belum ada task — klik + untuk menambah</div>');
                }

                $card.find('.btn-proj-add-task').on('click', function(e) {
                    e.stopPropagation();
                    openPanel('task', null, +$(this).data('pid'));
                });

                $card.find('.btn-proj-edit').on('click', function(e) {
                    e.stopPropagation();
                    const proj = projectMap[+$(this).data('pid')];
                    openPanel('project', proj);
                });

                return $card;
            }

            function makeTaskRow(t, $container, depth) {
                const indent = depth * 20;
                const $row = $(`
            <div class="task-row" data-tid="${t.id}">
                <div class="task-left">
                    <span style="padding-left:${indent}px;flex-shrink:0"></span>
                    ${depth ? '<span style="color:#cbd5e1;font-size:.7rem;flex-shrink:0">└</span>' : ''}
                    <span class="task-name">${esc(t.nama)}</span>
                    ${badge(t.status)}
                    <span class="task-bobot">w:${t.bobot}</span>
                </div>
                <div class="task-right">
                    <button class="icon-btn btn-task-edit" data-tid="${t.id}" title="Edit Task">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                </div>
            </div>`);

                $row.find('.btn-task-edit').on('click', function(e) {
                    e.stopPropagation();
                    const task = taskMap[+$(this).data('tid')];
                    openPanel('task', task);
                });

                $container.append($row);

                (t.all_children || []).forEach(c => makeTaskRow(c, $container, depth + 1));
            }

            function badge(status) {
                const lbl = {
                    draft: 'Draft',
                    in_progress: 'In Progress',
                    done: 'Done'
                };
                return `<span class="badge badge-${status}">
            <span class="badge-dot"></span>${lbl[status] || status}
        </span>`;
            }

            /* ─── Panel ──────────────────────────────── */
            function openPanel(mode, data = null, projectId = null) {
                panelMode = mode;
                editId = data?.id || null;
                forProject = projectId;

                $('#project-form, #task-form').hide();
                $('#project-error, #task-error').hide().text('');
                $('#project-dep-section, #task-dep-section').hide();
                $('#btn-hapus').hide();

                if (mode === 'project') {
                    $('#panel-title').text(data ? 'Edit Project' : 'Tambah Project');
                    $('#project-form').show();

                    if (data) {
                        $('#project-id').val(data.id);
                        $('#project-nama').val(data.nama);
                        $('#project-start').val((data.start_date || '').substring(0, 10));
                        $('#project-end').val((data.end_date || '').substring(0, 10));
                        $('#btn-hapus').show();
                        // Tampilkan & load dep section hanya saat edit
                        $('#project-dep-section').show();
                        loadProjDeps(data.id);
                    } else {
                        $('#project-form')[0].reset();
                        $('#project-id').val('');
                    }
                }

                if (mode === 'task') {
                    $('#panel-title').text(data ? 'Edit Task' : 'Tambah Task');
                    $('#task-form').show();
                    fillProjectSelect();

                    if (data) {
                        $('#task-id').val(data.id);
                        $('#task-project-id').val(data.project_id);
                        $('#task-nama').val(data.nama);
                        $('#task-bobot').val(data.bobot);
                        $('#task-status').val(data.status);
                        fillParentSelect(data.project_id, data.parent_id);
                        $('#btn-hapus').show();
                        // Tampilkan & load dep section hanya saat edit
                        $('#task-dep-section').show();
                        loadTaskDeps(data);
                    } else {
                        $('#task-form')[0].reset();
                        $('#task-id').val('');
                        $('#task-bobot').val(1);
                        if (projectId) {
                            $('#task-project-id').val(projectId);
                            fillParentSelect(projectId, null);
                        }
                    }
                }

                $('#overlay').addClass('active');
                $('#slide-panel').addClass('open');
            }

            function closePanel() {
                $('#slide-panel').removeClass('open');
                $('#overlay').removeClass('active');
                panelMode = editId = forProject = null;
            }

            const SPINNER =
                `<svg class="btn-spin" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin .7s linear infinite;flex-shrink:0;vertical-align:middle;margin-right:4px"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>`;

            function btnLoading($btn, label) {
                $btn.prop('disabled', true)
                    .data('orig', $btn.html())
                    .html(SPINNER + (label || 'Menyimpan...'));
            }

            function btnReset($btn) {
                $btn.prop('disabled', false)
                    .html($btn.data('orig') || $btn.html());
            }

            function save() {
                if (panelMode === 'project') saveProject();
                else if (panelMode === 'task') saveTask();
            }

            function saveProject() {
                const isEdit = !!$('#project-id').val();
                const id = $('#project-id').val();
                const payload = {
                    nama: $('#project-nama').val().trim(),
                    start_date: $('#project-start').val(),
                    end_date: $('#project-end').val(),
                };

                if (!payload.nama || !payload.start_date || !payload.end_date) {
                    return showErr('#project-error', 'Semua field wajib diisi.');
                }

                const $btn = $('#btn-save');
                const url = isEdit ? ROUTES.projects.update(id) : ROUTES.projects.store;

                btnLoading($btn);

                api(isEdit ? 'PUT' : 'POST', url, payload)
                    .done(() => {
                        btnReset($btn);
                        closePanel();
                        toast.ok(isEdit ? 'Project diperbarui!' : 'Project dibuat!');
                        load();
                    })
                    .fail(xhr => {
                        btnReset($btn);
                        showErr('#project-error', getErrMsg(xhr));
                    });
            }

            function saveTask() {
                const isEdit = !!$('#task-id').val();
                const id = $('#task-id').val();
                const projectId = $('#task-project-id').val();
                const payload = {
                    nama: $('#task-nama').val().trim(),
                    bobot: parseInt($('#task-bobot').val()) || 1,
                    status: $('#task-status').val(),
                    parent_id: $('#task-parent-id').val() || null,
                };

                if (!projectId) return showErr('#task-error', 'Pilih project terlebih dahulu.');
                if (!payload.nama) return showErr('#task-error', 'Nama task wajib diisi.');

                const $btn = $('#btn-save');
                const url = isEdit ? ROUTES.tasks.update(id) : ROUTES.projects.tasksStore(projectId);

                btnLoading($btn);

                api(isEdit ? 'PUT' : 'POST', url, payload)
                    .done(() => {
                        btnReset($btn);
                        closePanel();
                        toast.ok(isEdit ? 'Task diperbarui!' : 'Task dibuat!');
                        load();
                    })
                    .fail(xhr => {
                        btnReset($btn);
                        showErr('#task-error', getErrMsg(xhr));
                    });
            }

            function doDelete() {
                if (!editId || !panelMode) return;
                const isProject = panelMode === 'project';

                Swal.fire({
                    title: `Hapus ${isProject ? 'Project' : 'Task'}?`,
                    text: isProject ?
                        'Semua task di dalam project ini juga akan terhapus.' :
                        'Task ini akan dihapus secara permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#dc2626',
                }).then(r => {
                    if (!r.isConfirmed) return;

                    const $btn = $('#btn-hapus');
                    const url = isProject ?
                        ROUTES.projects.destroy(editId) :
                        ROUTES.tasks.destroy(editId);

                    btnLoading($btn, 'Menghapus...');

                    api('DELETE', url)
                        .done(() => {
                            btnReset($btn);
                            closePanel();
                            toast.ok('Berhasil dihapus!');
                            load();
                        })
                        .fail(() => {
                            btnReset($btn);
                            toast.err('Gagal menghapus, coba lagi.');
                        });
                });
            }

            /* --- Dependency: Project --- */

            function loadProjDeps(projectId) {
                const proj = projectMap[projectId];
                if (!proj) return;

                // Isi dropdown: semua project kecuali diri sendiri
                const $sel = $('#proj-dep-select').html(
                    '<option value="">-- Pilih project yang harus selesai dulu --</option>');
                Object.values(projectMap).forEach(p => {
                    if (p.id !== projectId) {
                        $sel.append(`<option value="${p.id}">${esc(p.nama)}</option>`);
                    }
                });

                renderDepList(
                    '#proj-dep-list',
                    proj.dependencies || [],
                    (depId) => removeProjDep(projectId, depId)
                );
            }

            function removeProjDep(projectId, depId) {
                Swal.fire({
                    title: 'Hapus dependency ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#dc2626',
                }).then(r => {
                    if (!r.isConfirmed) return;
                    api('DELETE', ROUTES.projects.depDestroy(projectId, depId))
                        .done(async () => {
                            const res = await api('GET', ROUTES.projects.show(projectId));
                            if (res.data) {
                                projectMap[projectId] = res.data;
                            }
                            loadProjDeps(projectId);
                            load();
                            toast.ok('Dependency dihapus!');
                        })
                        .fail(() => toast.err('Gagal menghapus dependency.'));
                });
            }

            $('#btn-proj-dep-add').on('click', function() {
                const projectId = +$('#project-id').val();
                const depId = +$('#proj-dep-select').val();
                if (!depId) return toast.warn('Pilih project dependency terlebih dahulu.');

                const $btn = $(this);
                btnLoading($btn, 'Menambah...');

                api('POST', ROUTES.projects.depStore(projectId), {
                        depends_on_project_id: depId
                    })
                    .done(async () => {
                        const res = await api('GET', ROUTES.projects.show(projectId));
                        if (res.data) projectMap[projectId] = res.data;
                        btnReset($btn);
                        loadProjDeps(projectId);
                        load();
                        toast.ok('Dependency ditambahkan!');
                    })
                    .fail(xhr => {
                        btnReset($btn);
                        toast.err(getErrMsg(xhr));
                    });
            });

            /* --- Dependency: Task --- */

            function loadTaskDeps(task) {
                // Isi dropdown: semua task dalam project yang sama, kecuali diri sendiri
                const $sel = $('#task-dep-select').html(
                    '<option value="">-- Pilih task yang harus selesai dulu --</option>');
                const projId = task.project_id;

                api('GET', ROUTES.projects.tasks(projId)).done(res => {
                    (res.data || []).forEach(t => addTaskDepOption($sel, t, 0, task.id));
                });

                // Render list dependency yang sudah ada
                renderDepList(
                    '#task-dep-list',
                    task.dependencies || [],
                    (depId) => removeTaskDep(task.id, depId)
                );
            }

            function addTaskDepOption($sel, t, depth, excludeId) {
                if (t.id === excludeId) return;
                const prefix = '  '.repeat(depth * 2);
                $sel.append(`<option value="${t.id}">${prefix}${depth ? '└ ' : ''}${esc(t.nama)}</option>`);
                (t.all_children || []).forEach(c => addTaskDepOption($sel, c, depth + 1, excludeId));
            }

            function removeTaskDep(taskId, depId) {
                Swal.fire({
                    title: 'Hapus dependency ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#dc2626',
                }).then(r => {
                    if (!r.isConfirmed) return;
                    api('DELETE', ROUTES.tasks.depDestroy(taskId, depId))
                        .done(async () => {
                            const res = await api('GET', ROUTES.tasks.show(taskId));
                            if (res.data) taskMap[taskId] = res.data;
                            loadTaskDeps(taskMap[taskId]);
                            load();
                            toast.ok('Dependency dihapus!');
                        })
                        .fail(() => toast.err('Gagal menghapus dependency.'));
                });
            }

            $('#btn-task-dep-add').on('click', function() {
                const taskId = +$('#task-id').val();
                const depId = +$('#task-dep-select').val();
                if (!depId) return toast.warn('Pilih task dependency terlebih dahulu.');

                const $btn = $(this);
                btnLoading($btn, 'Menambah...');

                api('POST', ROUTES.tasks.depStore(taskId), {
                        depends_on_task_id: depId
                    })
                    .done(async () => {
                        const res = await api('GET', ROUTES.tasks.show(taskId));
                        if (res.data) taskMap[taskId] = res.data;
                        btnReset($btn);
                        loadTaskDeps(taskMap[taskId]);
                        load();
                        toast.ok('Dependency ditambahkan!');
                    })
                    .fail(xhr => {
                        btnReset($btn);
                        toast.err(getErrMsg(xhr));
                    });
            });

            /* --- Render dep list (shared) --- */
            function renderDepList(selector, deps, onRemove) {
                const $list = $(selector).empty();
                if (!deps || !deps.length) {
                    $list.html('<div class="dep-empty">Belum ada dependency</div>');
                    return;
                }
                deps.forEach(d => {
                    const $item = $(`
                <div class="dep-item">
                    <span class="dep-item-name">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;color:var(--blue)"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        ${esc(d.nama)}
                    </span>
                    ${badge(d.status)}
                    <button class="dep-remove" title="Hapus">✕</button>
                </div>`);
                    $item.find('.dep-remove').on('click', () => onRemove(d.id));
                    $list.append($item);
                });
            }

            function fillProjectSelect() {
                const $s = $('#task-project-id').html('<option value="">-- Pilih Project --</option>');
                projects.forEach(p => {
                    $s.append(`<option value="${p.id}">${esc(p.nama)}</option>`);
                });
            }

            function fillParentSelect(projectId, selectedId) {
                const $s = $('#task-parent-id').html('<option value="">-- Root Task (tanpa parent) --</option>');
                if (!projectId) return;

                api('GET', ROUTES.projects.tasks(projectId)).done(res => {
                    (res.data || []).forEach(t => appendOpt($s, t, 0, selectedId));
                });
            }

            function appendOpt($s, t, depth, sel) {
                const prefix = '\u00a0\u00a0'.repeat(depth * 2);
                const $o = $(`<option value="${t.id}">${prefix}${depth ? '└ ' : ''}${esc(t.nama)}</option>`);
                if (t.id == sel) $o.prop('selected', true);
                $s.append($o);
                (t.all_children || []).forEach(c => appendOpt($s, c, depth + 1, sel));
            }

            function showErr(sel, msg) {
                $(sel).text(msg).show();
            }

            function getErrMsg(xhr) {
                const json = xhr.responseJSON;
                if (!json) return 'Terjadi kesalahan server.';
                if (json.message) return json.message;
                if (json.errors) return Object.values(json.errors).flat().join(' ');
                return 'Terjadi kesalahan.';
            }

            function esc(str) {
                return $('<div>').text(str || '').html();
            }

            function fmtDate(d) {
                if (!d) return '–';
                return new Date(d).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                });
            }

            $('#btn-add-project').on('click', () => openPanel('project'));
            $('#btn-add-task').on('click', () => openPanel('task'));
            $('#btn-close-panel, #btn-cancel').on('click', closePanel);
            $('#overlay').on('click', closePanel);
            $('#btn-save').on('click', save);
            $('#btn-hapus').on('click', doDelete);

            $('#task-project-id').on('change', function() {
                fillParentSelect($(this).val(), null);
            });

            $('#filter-status').on('change', load);

            $('#filter-search').on('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(load, 400);
            });

            $('#btn-clear-filter').on('click', () => {
                $('#filter-status').val('');
                $('#filter-search').val('');
                load();
            });

            load();
        });
    </script>
</body>

</html>

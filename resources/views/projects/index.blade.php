<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Project Tracker</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --font: 'Plus Jakarta Sans', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }

        body {
            font-family: var(--font);
            background-color: #f8f9fa;
            font-size: 14px;
        }

        /* Header */
        .header {
            background: white;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
        }

        .logo-icon {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        /* Progress Bar Custom */
        .progress {
            height: 6px;
            background-color: #eff6ff;
            border-radius: 99px;
        }

        .progress-bar {
            background: linear-gradient(90deg, #2563eb, #3b82f6);
        }

        /* Badge Custom */
        .badge {
            font-family: var(--mono);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            padding: 0.25rem 0.6rem;
        }

        .badge-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 4px;
        }

        .badge-draft {
            background-color: #f1f5f9;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }

        .badge-draft .badge-dot {
            background: #94a3b8;
        }

        .badge-in_progress {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .badge-in_progress .badge-dot {
            background: #3b82f6;
            animation: pulse 1.5s infinite;
        }

        .badge-done {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .badge-done .badge-dot {
            background: #059669;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.35;
            }
        }

        /* Task Row */
        .task-row {
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.15s;
        }

        .task-row:last-child {
            border-bottom: none;
        }

        .task-row:hover {
            background-color: #eff6ff;
        }

        .task-bobot {
            font-size: 11px;
            color: #94a3b8;
            font-family: var(--mono);
            background: white;
            border: 1px solid #e5e7eb;
            padding: 2px 8px;
            border-radius: 4px;
        }

        /* Dependency Tags */
        .dep-tag {
            font-size: 11px;
            padding: 2px 10px;
            border-radius: 99px;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            font-family: var(--mono);
            display: inline-block;
            margin-right: 4px;
            margin-bottom: 4px;
        }

        /* Offcanvas */
        .offcanvas {
            width: 420px !important;
        }

        @media (max-width: 576px) {
            .offcanvas {
                width: 100% !important;
            }
        }

        /* Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #e5e7eb 25%, #e2e8f0 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 6px;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        /* Spinner for buttons */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-spin {
            animation: spin 0.7s linear infinite;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f8f9fa;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 99px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Small adjustments */
        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 0.35rem;
        }

        .form-control,
        .form-select {
            font-size: 14px;
            border-color: #e5e7eb;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
        }

        .project-dates {
            font-size: 12px;
            color: #94a3b8;
            font-family: var(--mono);
        }

        .progress-pct {
            color: #2563eb;
            font-weight: 600;
            font-family: var(--mono);
            font-size: 12px;
        }

        .dep-section-title {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dep-item {
            background: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.4rem 0.65rem;
            font-size: 13px;
        }

        .task-empty {
            padding: 1rem;
            font-size: 13px;
            color: #94a3b8;
            font-style: italic;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="header sticky-top">
        <div class="container-fluid py-2">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="logo-icon">◈</div>
                    <h5 class="mb-0 fw-bold text-primary">Project Tracker</h5>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm" id="btn-add-project">
                        <i class="bi bi-plus-lg"></i> Add Project
                    </button>
                    <button class="btn btn-success btn-sm" id="btn-add-task">
                        <i class="bi bi-plus-lg"></i> Add Task
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Filter Bar -->
    <div class="bg-white border-bottom py-2">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <span class="text-uppercase fw-semibold text-muted small">Filter:</span>
                </div>
                <div class="col-auto">
                    <select id="filter-status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="draft">Draft</option>
                        <option value="in_progress">In Progress</option>
                        <option value="done">Done</option>
                    </select>
                </div>
                <div class="col-auto">
                    <input id="filter-search" type="text" class="form-control form-control-sm"
                        placeholder="Cari task..." style="width:210px">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-secondary btn-sm" id="btn-clear-filter">
                        <i class="bi bi-x-circle"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container py-4" style="max-width: 960px;">
        <div id="projects-container">
            <!-- Loading Skeleton -->
            <div id="loading-skel">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="skeleton mb-2" style="height:14px;width:38%;"></div>
                        <div class="skeleton mb-3" style="height:9px;width:22%;"></div>
                        <div class="skeleton" style="height:6px;width:100%;"></div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="skeleton mb-2" style="height:14px;width:52%;"></div>
                        <div class="skeleton mb-3" style="height:9px;width:28%;"></div>
                        <div class="skeleton" style="height:6px;width:100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Offcanvas Slide Panel -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="slide-panel" data-bs-backdrop="true"
        data-bs-keyboard="true">
        <div class="offcanvas-header bg-light border-bottom">
            <h5 class="offcanvas-title fw-bold" id="panel-title">Add Project</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <!-- Project Form -->
            <form id="project-form" style="display:none" onsubmit="return false">
                <input type="hidden" id="project-id">

                <div class="mb-3">
                    <label class="form-label">Nama Project *</label>
                    <input type="text" id="project-nama" class="form-control" placeholder="Contoh: Website Redesign">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Start Date *</label>
                        <input type="date" id="project-start" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label">End Date *</label>
                        <input type="date" id="project-end" class="form-control">
                    </div>
                </div>

                <div class="alert alert-danger d-none align-items-center" id="project-error" role="alert"></div>

                <!-- Project Dependencies -->
                <div id="project-dep-section" class="border-top pt-3 mt-4" style="display:none">
                    <div class="dep-section-title mb-3">
                        <i class="bi bi-link-45deg"></i> Project Dependencies
                    </div>
                    <div class="d-flex gap-2 mb-3">
                        <select id="proj-dep-select" class="form-select form-select-sm flex-grow-1">
                            <option value="">-- Pilih project yang harus selesai dulu --</option>
                        </select>
                        <button class="btn btn-primary btn-sm" id="btn-proj-dep-add">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                    </div>
                    <div id="proj-dep-list">
                        <div class="text-muted fst-italic small text-center py-2">Belum ada dependency</div>
                    </div>
                </div>
            </form>

            <!-- Task Form -->
            <form id="task-form" style="display:none" onsubmit="return false">
                <input type="hidden" id="task-id">

                <div class="mb-3">
                    <label class="form-label">Project *</label>
                    <select id="task-project-id" class="form-select">
                        <option value="">-- Pilih Project --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Parent Task</label>
                    <select id="task-parent-id" class="form-select">
                        <option value="">-- Root Task (tanpa parent) --</option>
                    </select>
                    <div class="form-text">Kosongkan jika ini task utama</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Task *</label>
                    <input type="text" id="task-nama" class="form-control" placeholder="Contoh: Setup Database">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Bobot *</label>
                        <input type="number" id="task-bobot" class="form-control" min="1" value="1">
                        <div class="form-text">Untuk kalkulasi progress</div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Status</label>
                        <select id="task-status" class="form-select">
                            <option value="draft">Draft</option>
                            <option value="in_progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                </div>

                <div class="alert alert-danger d-none align-items-center" id="task-error" role="alert"></div>

                <!-- Task Dependencies -->
                <div id="task-dep-section" class="border-top pt-3 mt-4" style="display:none">
                    <div class="dep-section-title mb-3">
                        <i class="bi bi-link-45deg"></i> Task Dependencies
                    </div>
                    <div class="d-flex gap-2 mb-3">
                        <select id="task-dep-select" class="form-select form-select-sm flex-grow-1">
                            <option value="">-- Pilih task yang harus selesai dulu --</option>
                        </select>
                        <button class="btn btn-primary btn-sm" id="btn-task-dep-add">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                    </div>
                    <div id="task-dep-list">
                        <div class="text-muted fst-italic small text-center py-2">Belum ada dependency</div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer Actions -->
        <div class="offcanvas-footer bg-light border-top p-3">
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-danger btn-sm" id="btn-hapus" style="display:none">
                    <i class="bi bi-trash"></i> Hapus
                </button>
                <div class="ms-auto d-flex gap-2">
                    <button class="btn btn-secondary btn-sm" id="btn-cancel"
                        data-bs-dismiss="offcanvas">Batal</button>
                    <button class="btn btn-primary btn-sm" id="btn-save">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- SweetAlert2 -->
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
            let bsOffcanvas = null;

            const toast = {
                ok: msg => Swal.fire({
                    title: 'Berhasil!',
                    text: msg,
                    icon: 'success'
                }),
                err: msg => Swal.fire({
                    title: 'Gagal!',
                    text: msg,
                    icon: 'error'
                }),
                warn: msg => Swal.fire({
                    title: 'Perhatian',
                    text: msg,
                    icon: 'warning'
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
                    $c.html(`
                        <div class="text-center py-5">
                            <div class="display-1 text-muted mb-3">📋</div>
                            <h4 class="text-muted">Belum ada project</h4>
                            <p class="text-muted">Klik "Add Project" untuk mulai</p>
                        </div>
                    `);
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
                    <div class="card shadow-sm mb-3" data-id="${p.id}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <h6 class="mb-0 fw-bold">${esc(p.nama)}</h6>
                                        ${badge(p.status)}
                                    </div>
                                    <div class="project-dates mb-3">
                                        ${fmtDate(p.start_date)} → ${fmtDate(p.end_date)}
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1 small">
                                        <span class="text-muted">Completion</span>
                                        <span class="progress-pct">${pct.toFixed(1)}%</span>
                                    </div>
                                    <div class="progress mb-2">
                                        <div class="progress-bar" style="width:${pct}%"></div>
                                    </div>
                                    ${deps ? `<div class="mt-2">${deps}</div>` : ''}
                                </div>
                                <div class="d-flex gap-1 ms-2">
                                    <button class="btn btn-success btn-sm btn-proj-add-task" data-pid="${p.id}" title="Add Task">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm btn-proj-edit" data-pid="${p.id}" title="Edit Project">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="task-list bg-light"></div>
                    </div>
                `);

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
                    <div class="task-row d-flex justify-content-between align-items-center p-2" data-tid="${t.id}">
                        <div class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden">
                            <span style="padding-left:${indent}px;flex-shrink:0"></span>
                            ${depth ? '<span class="text-muted small flex-shrink-0">└</span>' : ''}
                            <span class="text-truncate">${esc(t.nama)}</span>
                            ${badge(t.status)}
                            <span class="task-bobot">w:${t.bobot}</span>
                        </div>
                        <button class="btn btn-outline-primary btn-sm btn-task-edit" data-tid="${t.id}">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                `);

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

            function openPanel(mode, data = null, projectId = null) {
                panelMode = mode;
                editId = data?.id || null;
                forProject = projectId;

                $('#project-form, #task-form').hide();
                $('#project-error, #task-error').hide().removeClass('d-none').addClass('d-none');
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

                if (!bsOffcanvas) {
                    bsOffcanvas = new bootstrap.Offcanvas(document.getElementById('slide-panel'));
                }
                bsOffcanvas.show();
            }

            function closePanel() {
                if (bsOffcanvas) {
                    bsOffcanvas.hide();
                }
                panelMode = editId = forProject = null;
            }

            const SPINNER = `<i class="bi bi-arrow-repeat btn-spin me-1"></i>`;

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

                // Hide error sebelum submit
                $('#project-error').addClass('d-none').text('');

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
                        const errMsg = getErrMsg(xhr);
                        console.log('Error response:', xhr.responseJSON);
                        console.log('Error message:', errMsg);
                        showErr('#project-error', errMsg);

                        // Scroll to error
                        $('#project-error')[0].scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
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

                // Hide error sebelum submit
                $('#task-error').addClass('d-none').text('');

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
                        const errMsg = getErrMsg(xhr);
                        console.log('Error response:', xhr.responseJSON);
                        console.log('Error message:', errMsg);
                        showErr('#task-error', errMsg);

                        // Scroll to error
                        $('#task-error')[0].scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
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

            function loadProjDeps(projectId) {
                const proj = projectMap[projectId];
                if (!proj) return;

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

            function loadTaskDeps(task) {
                const $sel = $('#task-dep-select').html(
                    '<option value="">-- Pilih task yang harus selesai dulu --</option>');
                const projId = task.project_id;

                api('GET', ROUTES.projects.tasks(projId)).done(res => {
                    (res.data || []).forEach(t => addTaskDepOption($sel, t, 0, task.id));
                });

                renderDepList(
                    '#task-dep-list',
                    task.dependencies || [],
                    (depId) => removeTaskDep(task.id, depId)
                );
            }

            function addTaskDepOption($sel, t, depth, excludeId) {
                if (t.id === excludeId) return;
                const prefix = '  '.repeat(depth * 2);
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

            function renderDepList(selector, deps, onRemove) {
                const $list = $(selector).empty();
                if (!deps || !deps.length) {
                    $list.html(
                        '<div class="text-muted fst-italic small text-center py-2">Belum ada dependency</div>');
                    return;
                }
                deps.forEach(d => {
                    const $item = $(`
                        <div class="dep-item d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden">
                                <i class="bi bi-link-45deg text-primary"></i>
                                <span class="text-truncate">${esc(d.nama)}</span>
                                ${badge(d.status)}
                            </div>
                            <button class="btn btn-outline-danger btn-sm dep-remove" title="Hapus">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    `);
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
                const $el = $(sel);
                $el.html(`<i class="bi bi-exclamation-triangle-fill me-2"></i>${msg}`)
                    .removeClass('d-none')
                    .show();
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

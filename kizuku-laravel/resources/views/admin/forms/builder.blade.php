@extends('layouts.admin')

@php
    $formTitleId = $form->getTranslation('title', 'id', false) ?: '';
    $formTitleJp = $form->getTranslation('title', 'jp', false) ?: '';
    $formDescriptionId = $form->getTranslation('description', 'id', false) ?: '';
    $formDescriptionJp = $form->getTranslation('description', 'jp', false) ?: '';
    $formSuccessId = $form->getTranslation('success_message', 'id', false) ?: '';
    $formSuccessJp = $form->getTranslation('success_message', 'jp', false) ?: '';
@endphp

@section('admin-title', 'Form Builder')

@section('admin-styles')
<style>
    /* Google Forms-like Aesthetics */
    :root {
        --google-purple: #673ab7;
        --google-purple-light: #f0ebf8;
        --google-bg: #f0ebf8;
    }

    body {
        background-color: var(--google-bg) !important;
    }

    main {
        background-color: var(--google-bg) !important;
    }

    .builder-container {
        max-width: 770px;
        margin: 0 auto;
        position: relative; /* For sidebar positioning */
    }

    .google-card {
        background: white;
        border-radius: 8px;
        border: 1px solid #dadce0;
        margin-bottom: 12px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        outline: none;
    }

    .google-card.active {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-left: 6px solid var(--google-purple);
        z-index: 10;
    }

    .header-card {
        border-top: 10px solid var(--google-purple);
    }

    .sticky-top-bar {
        background: white;
        border-bottom: 1px solid #dadce0;
        position: sticky;
        top: 0;
        z-index: 50;
        margin-left: -2rem;
        margin-right: -2rem;
        margin-top: -2rem;
        padding: 0.5rem 1.5rem;
    }

    /* Sidebar Floating - Desktop */
    .sidebar-floating {
        position: absolute;
        right: -60px;
        top: 0;
        width: 48px;
        background: white;
        border: 1px solid #dadce0;
        border-radius: 8px;
        padding: 8px 4px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        z-index: 40;
        transition: top 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @media (max-width: 1024px) {
        .sidebar-floating {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            top: auto !important;
            width: 100%;
            height: 56px;
            flex-direction: row;
            justify-content: center;
            border-radius: 0;
            border-top: 1px solid #dadce0;
            padding: 4px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            z-index: 60;
        }
        .builder-container {
            padding-bottom: 80px;
        }
    }

    .sidebar-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        color: #70757a;
        transition: all 0.2s;
        position: relative;
    }

    .sidebar-btn:hover:not(:disabled) {
        background-color: #f8f9fa;
        color: var(--google-purple);
    }

    .sidebar-btn:disabled {
        color: #e8eaed;
        cursor: not-allowed;
    }

    /* Tooltip simple */
    .sidebar-btn[title]::after {
        content: attr(title);
        position: absolute;
        left: 54px;
        top: 50%;
        transform: translateY(-50%);
        background: #3c4043;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s;
        pointer-events: none;
    }

    .sidebar-btn[title]:hover::after {
        opacity: 1;
        visibility: visible;
    }

    @media (max-width: 1024px) {
        .sidebar-btn[title]::after {
            display: none;
        }
    }

    .drag-handle {
        cursor: grab;
        color: #dadce0;
    }

    .drag-handle:active {
        cursor: grabbing;
    }

    /* Custom Inputs */
    .google-input {
        border: none;
        border-bottom: 1px solid transparent;
        padding: 4px 0;
        width: 100%;
        transition: border-bottom 0.2s;
        outline: none !important;
        box-shadow: none !important;
    }

    .google-input:focus {
        border-bottom: 2px solid var(--google-purple);
    }

    /* Card Views */
    .card-inactive-view {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .card-active-view {
        display: none;
    }

    .active .card-inactive-view {
        display: none;
    }

    .active .card-active-view {
        display: block;
    }

    .toast-container {
        position: fixed;
        bottom: 74px;
        left: 24px;
        z-index: 100;
    }
</style>
@endsection

@section('admin-content')
<div class="relative min-h-screen">
    
    <!-- Top Bar Sticky -->
    <div class="sticky-top-bar flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-purple-100 rounded-lg text-purple-700">
                <span class="material-symbols-outlined text-[28px]">description</span>
            </div>
            <div class="max-w-[200px] sm:max-w-md">
                <h1 class="text-lg font-medium text-slate-800 truncate">{{ $formTitleId ?: 'Untitled Form' }}</h1>
                <div class="flex items-center gap-2">
                    <span id="status-badge" class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider
                        {{ $form->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $form->status }}
                    </span>
                    <span class="text-[10px] text-slate-400">All changes saved</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-1 sm:gap-2">
            <a href="{{ route('admin.forms.index') }}" class="p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors" title="Back">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div class="w-px h-6 bg-slate-200 mx-1"></div>
            <a href="{{ route('admin.forms.preview', $form->id) }}" target="_blank" class="p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors" title="Preview">
                <span class="material-symbols-outlined">visibility</span>
            </a>
            <a href="{{ route('admin.forms.responses.index', $form->id) }}" class="p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors" title="Responses">
                <span class="material-symbols-outlined">inbox</span>
            </a>
            <div class="w-px h-6 bg-slate-200 mx-1"></div>
            @if($form->status === 'published')
                <button onclick="setFormStatus('draft')" class="px-3 sm:px-4 py-2 text-slate-600 font-bold text-xs sm:text-sm hover:bg-slate-50 rounded-lg transition-colors">
                    Draft
                </button>
            @else
                <button onclick="publishForm()" class="px-4 sm:px-6 py-2 bg-purple-600 text-white font-bold text-xs sm:text-sm rounded-lg hover:bg-purple-700 transition-colors shadow-md">
                    Kirim
                </button>
            @endif
        </div>
    </div>

    <!-- Main Canvas -->
    <div class="builder-container mt-8 pb-32 px-4 sm:px-0">
        
        <!-- Floating Sidebar -->
        <div class="sidebar-floating" id="floating-sidebar">
            <button onclick="addField()" class="sidebar-btn" title="Add Question">
                <span class="material-symbols-outlined">add_circle</span>
            </button>
            <button onclick="addSection()" class="sidebar-btn" title="Add Section">
                <span class="material-symbols-outlined">view_agenda</span>
            </button>
        </div>

        <!-- Form Header Card -->
        <div class="google-card header-card p-6 space-y-4 active" id="meta-card" onclick="activateCard(this)">
            <div class="space-y-1">
                <input type="text" id="form-title-id" value="{{ $formTitleId }}" 
                       class="google-input google-title text-2xl sm:text-3xl" placeholder="Form Title" 
                       onchange="saveMetadata()">
                <textarea id="form-desc-id" class="google-input text-sm text-slate-600 h-auto resize-none" 
                          placeholder="Form description" rows="1"
                          oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
                          onchange="saveMetadata()">{{ $formDescriptionId }}</textarea>
            </div>
            
            <div class="pt-4 border-t border-slate-100">
                <button onclick="document.getElementById('meta-advanced').classList.toggle('hidden')" 
                        class="text-[10px] font-bold text-purple-600 hover:underline uppercase tracking-wider">
                    Advanced Settings (JP & Success Msg)
                </button>
                <div id="meta-advanced" class="hidden mt-4 space-y-4 bg-slate-50 p-4 rounded-lg border border-slate-100">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Success Msg (ID)</label>
                            <input type="text" id="form-success-id" value="{{ $formSuccessId }}" class="w-full text-sm border-slate-200 rounded-md focus:ring-purple-200 focus:border-purple-600" onchange="saveMetadata()">
                        </div>
                    </div>
                </div>
            </div>

        <!-- Question Cards Container -->
        <div id="fields-container" class="space-y-4">
            <!-- Cards injected by JS -->
        </div>

    </div>
</div>

<div class="toast-container" id="toast-container"></div>

<!-- Template Card -->
<template id="field-card-template">
    <div class="google-card field-card group" tabindex="0">
        
        <!-- Drag handle -->
        <div class="h-6 flex items-center justify-center cursor-grab drag-handle text-slate-200 hover:text-slate-400 transition-colors">
            <span class="material-symbols-outlined text-[18px]">drag_indicator</span>
        </div>

        <!-- Inactive View -->
        <div class="card-inactive-view px-6 pb-6 pt-2">
            <div class="flex justify-between items-start gap-4">
                <div class="flex-1">
                    <p class="field-label-text text-base text-slate-800"></p>
                    <p class="field-summary-text text-xs text-slate-400 mt-1"></p>
                </div>
                <div class="field-type-badge text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50 px-2 py-1 rounded"></div>
            </div>
        </div>
        
        <!-- Active View -->
        <div class="card-active-view px-6 pb-4">
            <div class="flex flex-col sm:flex-row gap-4 items-start">
                <div class="flex-1 w-full space-y-4">
                    <div class="relative">
                        <input type="text" class="field-label-id google-input text-base font-medium py-2" placeholder="Question">
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[9px] font-mono text-slate-300">VARIABLE:</span>
                            <input type="text" class="field-name-input text-[10px] font-mono text-slate-400 bg-transparent border-none focus:ring-0 p-0 w-32" placeholder="field_name">
                        </div>
                    </div>
                    
                    <button type="button" class="toggle-adv-meta text-[9px] font-bold text-slate-400 hover:text-purple-600 uppercase tracking-widest">
                        + More Options (JP, Placeholder, Desc)
                    </button>
                    <div class="adv-meta-container hidden space-y-3 bg-slate-50 p-3 rounded border border-slate-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[9px] font-bold text-slate-400 uppercase">Placeholder (ID)</label>
                                <input type="text" class="field-placeholder-id w-full text-xs border-slate-200 rounded focus:ring-purple-100 focus:border-purple-600">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-bold text-slate-400 uppercase">Description (ID)</label>
                                <input type="text" class="field-description-id w-full text-xs border-slate-200 rounded focus:ring-purple-100 focus:border-purple-600">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="w-full sm:w-52 shrink-0">
                    <div class="relative">
                        <select class="field-type-select w-full pl-10 pr-4 py-2.5 text-sm border-slate-200 rounded focus:border-purple-600 focus:ring-purple-100 appearance-none bg-white">
                            <optgroup label="TEXT">
                                <option value="text">Short Answer</option>
                                <option value="textarea">Paragraph</option>
                                <option value="email">Email</option>
                                <option value="phone">Phone</option>
                                <option value="number">Number</option>
                            </optgroup>
                            <optgroup label="CHOICE">
                                <option value="radio">Multiple Choice</option>
                                <option value="checkbox">Checkboxes</option>
                                <option value="select">Dropdown</option>
                            </optgroup>
                            <optgroup label="OTHER">
                                <option value="date">Date</option>
                                <option value="file">File Upload</option>
                                <option value="section">Section</option>
                            </optgroup>
                        </select>
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[20px] type-icon-display">subject</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="options-container mt-8 hidden border-t border-slate-50 pt-4">
                <div class="options-list space-y-3"></div>
                <button type="button" class="add-option-btn mt-4 text-sm text-purple-600 font-medium hover:bg-purple-50 px-3 py-1.5 rounded transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span> Add Option
                </button>
            </div>

            <div class="file-config-container mt-8 hidden bg-slate-50 p-4 rounded-lg border border-slate-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Allowed Extensions</p>
                        <div class="grid grid-cols-2 gap-2 file-ext-checkboxes">
                            <label class="flex items-center text-xs p-2 bg-white border border-slate-200 rounded cursor-pointer hover:border-purple-300 transition-colors">
                                <input type="checkbox" value="pdf" class="rounded text-purple-600 mr-2"> PDF
                            </label>
                            <label class="flex items-center text-xs p-2 bg-white border border-slate-200 rounded cursor-pointer hover:border-purple-300 transition-colors">
                                <input type="checkbox" value="jpg" class="rounded text-purple-600 mr-2"> JPG
                            </label>
                            <label class="flex items-center text-xs p-2 bg-white border border-slate-200 rounded cursor-pointer hover:border-purple-300 transition-colors">
                                <input type="checkbox" value="png" class="rounded text-purple-600 mr-2"> PNG
                            </label>
                            <label class="flex items-center text-xs p-2 bg-white border border-slate-200 rounded cursor-pointer hover:border-purple-300 transition-colors">
                                <input type="checkbox" value="doc" class="rounded text-purple-600 mr-2"> DOC
                            </label>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Max Size (KB)</p>
                        <input type="number" class="file-max-size w-full text-sm border-slate-200 rounded p-2" value="2048">
                    </div>
                </div>
            </div>
            <div class="section-config-container mt-8 hidden bg-slate-50 p-4 rounded-lg border border-slate-100">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Section Icon</p>
                        <div class="grid grid-cols-5 sm:grid-cols-10 gap-2 section-icon-selector">
                            <!-- Icons injected by JS -->
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Section Accent Color</p>
                        <div class="flex flex-wrap gap-2 section-color-selector">
                            <!-- Colors injected by JS -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="error-area mt-4 hidden p-3 bg-red-50 border border-red-100 rounded text-red-600 text-xs font-medium space-y-1"></div>

            <div class="mt-8 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                <div class="field-settings-bar flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium text-slate-500">Required</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer field-required-toggle">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:ring-0 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>
                    <div class="w-px h-4 bg-slate-200"></div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Role</span>
                        <select class="field-role-select text-xs border-none bg-slate-50 text-slate-600 rounded focus:ring-0 py-1 pr-8">
                            <option value="none">None</option>
                            <option value="applicant_name">Full Name</option>
                            <option value="applicant_email">Email</option>
                            <option value="applicant_phone">WhatsApp</option>
                            <option value="applicant_birth_date">Birth Date</option>
                            <option value="applicant_gender">Gender</option>
                            <option value="applicant_pob">Place of Birth</option>
                            <option value="applicant_address">Address</option>
                            <option value="applicant_education">Education</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center gap-1">
                    <button class="save-field-btn p-2.5 text-emerald-600 hover:bg-emerald-50 rounded-full transition-all" title="Save">
                        <span class="material-symbols-outlined text-[22px]">check_circle</span>
                    </button>
                    <button class="duplicate-field-btn p-2.5 text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-full transition-all" title="Duplicate">
                        <span class="material-symbols-outlined text-[22px]">content_copy</span>
                    </button>
                    <button class="delete-field-btn p-2.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all" title="Delete">
                        <span class="material-symbols-outlined text-[22px]">delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

@endsection

@section('admin-scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    const formId = {{ $form->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    let fields = @json($form->fields);
    const container = document.getElementById('fields-container');
    const template = document.getElementById('field-card-template');
    const sidebar = document.getElementById('floating-sidebar');

    const typeIcons = {
        'text': 'subject', 'textarea': 'notes', 'email': 'alternate_email', 'phone': 'call',
        'number': '123', 'date': 'calendar_today', 'radio': 'radio_button_checked',
        'checkbox': 'check_box', 'select': 'arrow_drop_down_circle', 'file': 'upload_file',
        'section': 'view_agenda'
    };

    const iconList = [
        'info', 'person', 'school', 'work', 'language', 'cloud_upload', 'description',
        'checklist', 'calendar', 'phone', 'email', 'location_on', 'payments',
        'verified', 'medical_services', 'trophy', 'engineering', 'flight_takeoff',
        'apartment', 'groups'
    ];

    const colorList = ['#673ab7', '#3f51b5', '#2196f3', '#009688', '#4caf50', '#ff9800', '#f44336', '#e91e63', '#9c27b0', '#607d8b'];

    // Sortable
    new Sortable(container, {
        animation: 150, handle: '.drag-handle', ghostClass: 'opacity-40',
        onEnd: () => {
            reorderFields();
            updateSidebarPosition();
        }
    });

    function renderAll() {
        container.innerHTML = '';
        fields.sort((a,b) => a.sort_order - b.sort_order).forEach(field => renderCard(field));
        updateSidebarPosition();
    }

    function renderCard(field) {
        const clone = template.content.cloneNode(true);
        const card = clone.querySelector('.field-card');
        card.dataset.id = field.id;
        
        // Populate...
        card.querySelector('.field-label-text').textContent = field.label?.id || '(No Question)';
        card.querySelector('.field-type-badge').textContent = field.type;
        card.querySelector('.field-label-id').value = field.label?.id || '';
        card.querySelector('.field-name-input').value = field.field_name || '';
        card.querySelector('.field-type-select').value = field.type || 'text';
        card.querySelector('.field-role-select').value = field.field_role || 'none';
        card.querySelector('.field-required-toggle').checked = !!field.is_required;
        card.querySelector('.field-placeholder-id').value = field.placeholder?.id || '';
        card.querySelector('.field-description-id').value = field.description?.id || '';
        
        // Real-time label update for inactive view
        card.querySelector('.field-label-id').addEventListener('input', (e) => {
            const inactiveLabel = card.querySelector('.field-label-text');
            if (field.type === 'section') {
                const iconName = card.dataset.sectionIcon || field.settings?.section_icon || 'info';
                const color = card.dataset.sectionColor || field.settings?.section_color || '#673ab7';
                inactiveLabel.innerHTML = `<span class="material-symbols-outlined align-middle mr-1" style="color: ${color}">${iconName}</span> ${e.target.value || 'Untitled Section'}`;
            } else {
                inactiveLabel.textContent = e.target.value || '(No Question)';
            }
        });

        refreshInactiveView(card, field);

        if (field.is_locked) {
            card.querySelector('.field-name-input').disabled = true;
            card.querySelector('.delete-field-btn').remove();
        }

        // Listeners
        card.addEventListener('click', () => activateCard(card));
        
        card.querySelector('.field-type-select').addEventListener('change', (e) => {
            toggleTypeUI(card, e.target.value);
            updateTypeIcon(card, e.target.value);
        });

        card.querySelector('.toggle-adv-meta').addEventListener('click', (e) => {
            e.stopPropagation();
            card.querySelector('.adv-meta-container').classList.toggle('hidden');
        });

        card.querySelector('.add-option-btn').addEventListener('click', (e) => { e.stopPropagation(); addOptionRow(card); });
        card.querySelector('.save-field-btn').addEventListener('click', (e) => { e.stopPropagation(); saveField(card); });
        card.querySelector('.duplicate-field-btn').addEventListener('click', (e) => { e.stopPropagation(); duplicateField(field.id); });

        if (!field.is_locked) {
            card.querySelector('.delete-field-btn')?.addEventListener('click', (e) => { e.stopPropagation(); deleteField(field.id, card); });
        }

        toggleTypeUI(card, field.type);
        updateTypeIcon(card, field.type);
        if (['radio', 'checkbox', 'select'].includes(field.type)) renderOptions(card, field.options || []);
        if (field.type === 'file') {
            const exts = field.accepted_file_types || [];
            card.querySelectorAll('.file-ext-checkboxes input').forEach(cb => cb.checked = exts.includes(cb.value));
            card.querySelector('.file-max-size').value = field.max_file_size || 2048;
        }
        if (field.type === 'section') {
            renderSectionSelectors(card, field.settings || {});
        }

        container.appendChild(card);
    }

    function activateCard(card) {
        if (card.classList.contains('active')) return;
        document.querySelectorAll('.google-card').forEach(c => c.classList.remove('active'));
        card.classList.add('active');
        updateSidebarPosition();
    }

    function updateSidebarPosition() {
        if (window.innerWidth <= 1024) return;
        const activeCard = document.querySelector('.google-card.active');
        if (activeCard) {
            const containerTop = document.querySelector('.builder-container').getBoundingClientRect().top + window.scrollY;
            const cardTop = activeCard.getBoundingClientRect().top + window.scrollY;
            sidebar.style.top = (cardTop - containerTop) + 'px';
        } else {
            sidebar.style.top = '0px';
        }
    }

    // Update on scroll/resize
    window.addEventListener('resize', updateSidebarPosition);

    function updateTypeIcon(card, type) {
        card.querySelector('.type-icon-display').textContent = typeIcons[type] || 'help';
    }

    function toggleTypeUI(card, type) {
        const optsDiv = card.querySelector('.options-container');
        const fileDiv = card.querySelector('.file-config-container');
        optsDiv.classList.add('hidden');
        fileDiv.classList.add('hidden');
        if (['radio', 'checkbox', 'select'].includes(type)) {
            optsDiv.classList.remove('hidden');
            if (optsDiv.querySelector('.options-list').children.length === 0) addOptionRow(card);
        } else if (type === 'file') fileDiv.classList.remove('hidden');
        
        // Hide metadata for section
        const settingsBar = card.querySelector('.field-settings-bar');
        const sectionDiv = card.querySelector('.section-config-container');
        
        if (type === 'section') {
            settingsBar.classList.add('hidden');
            sectionDiv.classList.remove('hidden');
            card.classList.add('section-card', 'border-l-8');
            if (!card.style.borderLeftColor) card.style.borderLeftColor = '#673ab7';
        } else {
            settingsBar.classList.remove('hidden');
            sectionDiv.classList.add('hidden');
            card.classList.remove('section-card', 'border-l-8');
            card.style.borderLeftColor = '';
        }
    }

    function refreshInactiveView(card, field) {
        const labelText = card.querySelector('.field-label-text');
        const typeBadge = card.querySelector('.field-type-badge');
        
        if (field.type === 'section') {
            card.classList.add('section-card', 'border-l-8');
            card.style.borderLeftColor = field.settings?.section_color || '#673ab7';
            const iconName = field.settings?.section_icon || 'info';
            labelText.innerHTML = `<span class="material-symbols-outlined align-middle mr-1" style="color: ${field.settings?.section_color || '#673ab7'}">${iconName}</span> ${field.label?.id || 'Untitled Section'}`;
        } else {
            card.classList.remove('section-card', 'border-l-8');
            card.style.borderLeftColor = '';
            labelText.textContent = field.label?.id || '(No Question)';
        }
        
        typeBadge.textContent = field.type;
    }

    function renderSectionSelectors(card, settings) {
        const iconContainer = card.querySelector('.section-icon-selector');
        const colorContainer = card.querySelector('.section-color-selector');
        const currentIcon = settings.section_icon || 'info';
        const currentColor = settings.section_color || '#673ab7';

        iconContainer.innerHTML = '';
        iconList.forEach(icon => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `p-2 rounded hover:bg-slate-200 transition-colors ${icon === currentIcon ? 'bg-purple-100 text-purple-600 ring-1 ring-purple-600' : 'text-slate-400'}`;
            btn.innerHTML = `<span class="material-symbols-outlined text-[20px]">${icon}</span>`;
            btn.onclick = () => {
                iconContainer.querySelectorAll('button').forEach(b => b.className = 'p-2 rounded hover:bg-slate-200 transition-colors text-slate-400');
                btn.className = 'p-2 rounded bg-purple-100 text-purple-600 ring-1 ring-purple-600 transition-colors';
                card.dataset.sectionIcon = icon;
                
                // Real-time inactive view update
                const labelText = card.querySelector('.field-label-text');
                const color = card.dataset.sectionColor || '#673ab7';
                const labelValue = card.querySelector('.field-label-id').value || 'Untitled Section';
                labelText.innerHTML = `<span class="material-symbols-outlined align-middle mr-1" style="color: ${color}">${icon}</span> ${labelValue}`;
            };
            iconContainer.appendChild(btn);
        });
        card.dataset.sectionIcon = currentIcon;

        colorContainer.innerHTML = '';
        colorList.forEach(color => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `w-6 h-6 rounded-full border-2 transition-all ${color === currentColor ? 'border-slate-800 scale-110' : 'border-transparent hover:scale-105'}`;
            btn.style.backgroundColor = color;
            btn.onclick = () => {
                colorContainer.querySelectorAll('button').forEach(b => b.className = 'w-6 h-6 rounded-full border-2 border-transparent hover:scale-105 transition-all');
                btn.className = 'w-6 h-6 rounded-full border-2 border-slate-800 scale-110 transition-all';
                card.dataset.sectionColor = color;
                card.style.borderLeftColor = color;

                // Real-time inactive view update
                const labelText = card.querySelector('.field-label-text');
                const iconName = card.dataset.sectionIcon || 'info';
                const labelValue = card.querySelector('.field-label-id').value || 'Untitled Section';
                labelText.innerHTML = `<span class="material-symbols-outlined align-middle mr-1" style="color: ${color}">${iconName}</span> ${labelValue}`;
            };
            colorContainer.appendChild(btn);
        });
        card.dataset.sectionColor = currentColor;
    }

    function renderOptions(card, options) {
        const list = card.querySelector('.options-list');
        list.innerHTML = '';
        if (options.length === 0) addOptionRow(card);
        else options.forEach(opt => addOptionRow(card, opt));
    }

    function addOptionRow(card, data = null) {
        const list = card.querySelector('.options-list');
        const opt = data || { value: 'option_' + (list.children.length + 1), label: { id: 'Option ' + (list.children.length + 1), jp: '' } };
        const row = document.createElement('div');
        row.className = 'option-row flex items-start gap-2 group';
        row.innerHTML = `
            <div class="mt-2 text-slate-300"><span class="material-symbols-outlined text-[18px]">radio_button_unchecked</span></div>
            <div class="flex-1 space-y-2">
                <div class="flex gap-2">
                    <input type="text" class="opt-label-id flex-1 text-sm google-input" placeholder="Option Label (ID)" value="${opt.label?.id || ''}">
                    <input type="text" class="opt-value w-24 text-[10px] font-mono text-slate-400 bg-slate-50 border-none rounded px-2" placeholder="val" value="${opt.value || ''}">
                </div>
            </div>
            <button type="button" class="delete-option-btn p-1 text-slate-300 hover:text-red-500 opacity-0 group-hover:opacity-100 mt-1"><span class="material-symbols-outlined text-[18px]">close</span></button>
        `;
        row.querySelector('.delete-option-btn').addEventListener('click', () => { if (list.children.length > 1) row.remove(); });
        list.appendChild(row);
    }

    async function saveField(card) {
        const id = card.dataset.id;
        const type = card.querySelector('.field-type-select').value;
        const errArea = card.querySelector('.error-area');
        errArea.classList.add('hidden'); errArea.innerHTML = '';

        const payload = {
            program_id: {{ $form->program_id }}, schema_id: {{ $form->schema_id ?? 'null' }},
            label_id: card.querySelector('.field-label-id').value || 'Untitled',
            placeholder_id: card.querySelector('.field-placeholder-id').value,
            description_id: card.querySelector('.field-description-id').value,
            field_name: card.querySelector('.field-name-input').value,
            type: type, field_role: card.querySelector('.field-role-select').value,
            is_required: card.querySelector('.field-required-toggle').checked ? 1 : 0, status: 'aktif'
        };

        if (['radio', 'checkbox', 'select'].includes(type)) {
            const opts = [];
            card.querySelectorAll('.option-row').forEach(row => {
                opts.push({ value: row.querySelector('.opt-value').value, label: { id: row.querySelector('.opt-label-id').value } });
            });
            payload.options = JSON.stringify(opts);
        }

        if (type === 'file') {
            const exts = [];
            card.querySelectorAll('.file-ext-checkboxes input:checked').forEach(cb => exts.push(cb.value));
            payload.accepted_file_types = JSON.stringify(exts);
            payload.max_file_size = card.querySelector('.file-max-size').value;
        }

        if (type === 'section') {
            payload.settings = {
                section_icon: card.dataset.sectionIcon || 'info',
                section_color: card.dataset.sectionColor || '#673ab7'
            };
            payload.field_role = 'none';
            payload.is_required = 0;
        }

        try {
            const saveBtn = card.querySelector('.save-field-btn');
            const originalIcon = saveBtn.innerHTML;
            saveBtn.innerHTML = '<span class="material-symbols-outlined text-[22px] animate-spin">sync</span>';
            const res = await fetch(`/dashboard-admin/forms/${formId}/fields/${id}`, { method: 'PATCH', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify(payload) });
            const data = await res.json();
            if (res.ok) {
                const index = fields.findIndex(f => f.id == id);
                if (index !== -1) fields[index] = data.field;
                showToast('Field saved successfully');
                refreshInactiveView(card, data.field);
            } else if (res.status === 422) {
                errArea.classList.remove('hidden');
                Object.values(data.errors).flat().forEach(err => { const p = document.createElement('p'); p.textContent = '• ' + err; errArea.appendChild(p); });
            }
            saveBtn.innerHTML = originalIcon;
        } catch(e) { console.error(e); }
    }

    async function addSection() {
        const payload = {
            program_id: {{ $form->program_id }},
            schema_id: {{ $form->schema_id ?? 'null' }},
            label_id: 'Untitled Section',
            field_name: 'section_' + Date.now(),
            type: 'section',
            is_required: 0,
            status: 'aktif',
            settings: {
                section_icon: 'info',
                section_color: '#673ab7'
            }
        };

        try {
            const res = await fetch(`{{ route('admin.forms.fields.store', $form->id) }}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (res.ok) {
                fields.push(data.field);
                renderAll();
                const newCard = document.querySelector(`.field-card[data-id="${data.field.id}"]`);
                if (newCard) {
                    activateCard(newCard);
                    newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                showToast('Section added');
            } else {
                showToast(data.message || 'Error adding section', 'error');
            }
        } catch (e) {
            showToast('Network error', 'error');
        }
    }

    async function addField() {
        const payload = {
            program_id: {{ $form->program_id }}, schema_id: {{ $form->schema_id ?? 'null' }},
            label_id: 'New Question', field_name: 'q_' + Math.random().toString(36).substring(2, 8),
            type: 'text', status: 'aktif'
        };

        try {
            const res = await fetch(`/dashboard-admin/forms/${formId}/fields`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify(payload) });
            const data = await res.json();
            if (res.ok) {
                fields.push(data.field);
                renderAll();
                const newCard = container.lastElementChild;
                activateCard(newCard);
                newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                showToast('Question added');
            }
        } catch(e) { console.error(e); }
    }

    async function duplicateField(id) {
        try {
            const res = await fetch(`/dashboard-admin/forms/${formId}/fields/${id}/duplicate`, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
            const data = await res.json();
            if (res.ok) {
                fields.push(data.field);
                renderAll();
                // Find the new card (it will be after the original card in fields array but let's just find the last card for now or find by ID)
                const newCard = Array.from(container.children).find(c => c.dataset.id == data.field.id);
                if (newCard) {
                    activateCard(newCard);
                    newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                showToast('Question duplicated');
            }
        } catch(e) { console.error(e); }
    }

    async function deleteField(id, card) {
        if (!confirm('Delete this question?')) return;
        try {
            const res = await fetch(`/dashboard-admin/forms/${formId}/fields/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
            if (res.ok) {
                const wasActive = card.classList.contains('active');
                fields = fields.filter(f => f.id != id);
                card.remove();
                if (wasActive) {
                    const firstCard = container.querySelector('.google-card') || document.getElementById('meta-card');
                    if (firstCard) activateCard(firstCard);
                }
                showToast('Question deleted');
                updateSidebarPosition();
            }
        } catch(e) { console.error(e); }
    }

    async function reorderFields() {
        const order = Array.from(container.children).map(c => c.dataset.id);
        try { await fetch(`/dashboard-admin/forms/${formId}/fields/reorder`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ order }) }); showToast('Order updated'); } catch(e) { console.error(e); }
    }

    async function publishForm() {
        try {
            const res = await fetch(`/dashboard-admin/forms/${formId}/publish`, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
            const data = await res.json();
            if (res.ok) { showToast('Form published!'); setTimeout(() => location.reload(), 1000); } else alert(data.message || 'Validation failed.');
        } catch(e) { console.error(e); }
    }

    async function setFormStatus(status) {
        try { const res = await fetch(`/dashboard-admin/forms/${formId}/${status}`, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } }); if (res.ok) location.reload(); } catch(e) { console.error(e); }
    }

    function saveMetadata() {
        const payload = {
            program_id: {{ $form->program_id }}, schema_id: {{ $form->schema_id ?? 'null' }},
            title_id: document.getElementById('form-title-id').value,
            description_id: document.getElementById('form-desc-id').value,
            success_message_id: document.getElementById('form-success-id').value,
        };
        fetch(`/dashboard-admin/forms/${formId}`, { method: 'PATCH', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify(payload) }).then(() => showToast('Form metadata saved'));
    }

    function showToast(msg) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'bg-slate-800 text-white px-4 py-2.5 rounded-lg text-sm font-medium shadow-xl mb-3 translate-y-10 opacity-0 transition-all duration-300 flex items-center gap-2';
        toast.innerHTML = `<span class="material-symbols-outlined text-[18px] text-emerald-400">check_circle</span> ${msg}`;
        container.appendChild(toast);
        requestAnimationFrame(() => toast.classList.remove('translate-y-10', 'opacity-0'));
        setTimeout(() => { toast.classList.add('translate-y-10', 'opacity-0'); setTimeout(() => toast.remove(), 300); }, 3000);
    }

    renderAll();
</script>
@endsection

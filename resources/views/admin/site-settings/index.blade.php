@extends('admin.layouts.app')
@section('title', 'Configuración')

@section('content')
<div class="max-w-5xl">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Configuración</h1>
            <p class="text-sm text-gray-500 mt-1">Administra la información de tu negocio que se muestra en la tienda.</p>
        </div>
        <button type="submit" form="settingsForm" onclick="lockBtn(this, 'Guardando...')" class="bg-indigo-500 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-600 transition shadow-sm shadow-indigo-200 flex items-center gap-2 self-start">
            <i class="fas fa-save text-xs"></i> Guardar cambios
        </button>
    </div>

    <!-- Tabs navigation -->
    @php
        $groups = [
            'apariencia' => ['label' => 'Apariencia', 'icon' => 'fa-palette'],
            'negocio' => ['label' => 'Negocio', 'icon' => 'fa-building'],
            'redes' => ['label' => 'Redes Sociales', 'icon' => 'fa-share-nodes'],
            'seo' => ['label' => 'SEO', 'icon' => 'fa-magnifying-glass'],
            'notificaciones' => ['label' => 'Notificaciones', 'icon' => 'fa-bell'],
        ];
        $groupDescriptions = [
            'apariencia' => 'Personaliza el logo y favicon de tu tienda. Estos se mostrar&aacute;n en el header, sidebar del admin y la pesta&ntilde;a del navegador.',
            'negocio' => 'Datos legales, dirección y contacto de tu empresa. Esta información se muestra en el footer, páginas legales y formularios.',
            'redes' => 'Conecta tus perfiles de redes sociales. Los iconos se mostrarán automáticamente en el footer y la página de contacto.',
            'seo' => 'Optimiza cómo aparece tu tienda en los resultados de búsqueda de Google.',
            'notificaciones' => 'Configura los correos que recibir&aacute;n alertas del sistema y qu&eacute; notificaciones enviar.',
        ];

        $iconMap = [
            'site_logo' => 'fa-image',
            'site_favicon' => 'fa-star',
            'business_name' => 'fa-store',
            'legal_name' => 'fa-file-contract',
            'ruc' => 'fa-id-card',
            'address' => 'fa-map-marker-alt',
            'phone' => 'fa-phone',
            'contact_email' => 'fa-envelope',
            'business_hours' => 'fa-clock',
            'instagram_url' => 'fa-instagram fab',
            'facebook_url' => 'fa-facebook fab',
            'tiktok_url' => 'fa-tiktok fab',
            'pinterest_url' => 'fa-pinterest fab',
            'whatsapp_number' => 'fa-whatsapp fab',
            'whatsapp_message' => 'fa-comment-dots',
            'meta_description' => 'fa-align-left',
            'tagline' => 'fa-quote-right',
            'notification_emails' => 'fa-envelope',
            'notify_low_stock' => 'fa-box-open',
            'notify_new_order' => 'fa-shopping-bag',
            'notify_new_contact' => 'fa-comment-dots',
            'notify_new_complaint' => 'fa-book',
            'notify_new_review' => 'fa-star',
            'low_stock_threshold' => 'fa-layer-group',
        ];

        $iconColors = [
            'site_logo' => 'text-indigo-500 bg-indigo-50',
            'site_favicon' => 'text-amber-500 bg-amber-50',
            'instagram_url' => 'text-pink-500 bg-pink-50',
            'facebook_url' => 'text-blue-600 bg-blue-50',
            'tiktok_url' => 'text-gray-900 bg-gray-100',
            'pinterest_url' => 'text-red-600 bg-red-50',
            'whatsapp_number' => 'text-green-600 bg-green-50',
            'whatsapp_message' => 'text-green-600 bg-green-50',
            'notification_emails' => 'text-indigo-500 bg-indigo-50',
            'notify_low_stock' => 'text-amber-500 bg-amber-50',
            'notify_new_order' => 'text-emerald-500 bg-emerald-50',
            'notify_new_contact' => 'text-blue-500 bg-blue-50',
            'notify_new_complaint' => 'text-red-500 bg-red-50',
            'notify_new_review' => 'text-yellow-500 bg-yellow-50',
            'low_stock_threshold' => 'text-orange-500 bg-orange-50',
        ];
    @endphp

    <div class="flex gap-2 mb-6 border-b border-gray-200 overflow-x-auto pb-px">
        @foreach($groups as $groupKey => $groupInfo)
            <button type="button" onclick="switchTab('{{ $groupKey }}')"
                id="tab-{{ $groupKey }}"
                class="tab-btn flex items-center gap-2 px-5 py-3 text-sm font-medium border-b-2 transition-all whitespace-nowrap {{ $loop->first ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                <i class="fas {{ $groupInfo['icon'] }} text-xs"></i>
                {{ $groupInfo['label'] }}
            </button>
        @endforeach
    </div>

    <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach($settingsGroups as $group => $items)
            <div id="panel-{{ $group }}" class="tab-panel {{ $group === 'apariencia' ? '' : 'hidden' }}">

                <!-- Group description -->
                <div class="flex items-start gap-3 bg-indigo-50/60 rounded-xl px-5 py-4 mb-6">
                    <i class="fas fa-circle-info text-indigo-400 mt-0.5 text-sm"></i>
                    <p class="text-sm text-indigo-700/80">{!! $groupDescriptions[$group] ?? '' !!}</p>
                </div>

                @if($group === 'notificaciones')
                    {{-- Email recipients --}}
                    @php $emailsSetting = $items->firstWhere('key', 'notification_emails'); @endphp
                    @if($emailsSetting)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
                        <div class="px-6 py-5 border-b border-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl text-indigo-500 bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-users text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-800">Destinatarios</h3>
                                    <p class="text-xs text-gray-400 mt-0.5">Agrega los correos que recibir&aacute;n las notificaciones del sistema.</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            {{-- Email chips container --}}
                            <div id="emailChipsContainer" class="flex flex-wrap gap-2 mb-4 min-h-[2rem]">
                                {{-- Chips rendered by JS --}}
                            </div>

                            {{-- Add email input --}}
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-300"><i class="fas fa-at"></i></span>
                                    <input type="email" id="newEmailInput" placeholder="correo@ejemplo.com"
                                        class="w-full border border-gray-200 rounded-lg pl-9 pr-3.5 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition"
                                        onkeydown="if(event.key==='Enter'){event.preventDefault();addNotificationEmail();}">
                                </div>
                                <button type="button" onclick="addNotificationEmail()"
                                    class="bg-indigo-500 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-indigo-600 transition flex items-center gap-2 flex-shrink-0">
                                    <i class="fas fa-plus text-xs"></i> Agregar
                                </button>
                            </div>

                            {{-- Hidden input for form submission --}}
                            <input type="hidden" name="settings[notification_emails]" id="notificationEmailsHidden" value="{{ $emailsSetting->value }}">
                        </div>
                    </div>
                    @endif

                    {{-- Notification toggles --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
                        <div class="px-6 py-5 border-b border-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl text-violet-500 bg-violet-50 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-sliders text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-800">Tipos de notificaci&oacute;n</h3>
                                    <p class="text-xs text-gray-400 mt-0.5">Elige qu&eacute; eventos generan notificaciones por correo.</p>
                                </div>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @php
                                $toggleDescriptions = [
                                    'notify_low_stock' => 'Recibe alertas diarias cuando un producto tiene stock bajo.',
                                    'notify_new_order' => 'Recibe aviso cada vez que se registra una nueva venta.',
                                    'notify_new_contact' => 'Recibe aviso cuando llega un mensaje desde el formulario de contacto.',
                                    'notify_new_complaint' => 'Recibe aviso cuando se registra un reclamo en el libro.',
                                    'notify_new_review' => 'Recibe aviso cuando un cliente deja una rese&ntilde;a.',
                                ];
                            @endphp
                            @foreach($items->whereIn('type', ['toggle']) as $setting)
                                @php
                                    $icon = $iconMap[$setting->key] ?? 'fa-cog';
                                    $colorClass = $iconColors[$setting->key] ?? 'text-gray-500 bg-gray-50';
                                    $isChecked = old("settings.{$setting->key}", $setting->value) === '1';
                                @endphp
                                <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/40 transition">
                                    <div class="w-10 h-10 rounded-xl {{ $colorClass }} flex items-center justify-center flex-shrink-0">
                                        <i class="fas {{ $icon }} text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800">{{ $setting->label }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{!! $toggleDescriptions[$setting->key] ?? '' !!}</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                        <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                                        <input type="checkbox" name="settings[{{ $setting->key }}]" value="1" class="sr-only peer" {{ $isChecked ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-500"></div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Stock threshold --}}
                    @php $thresholdSetting = $items->firstWhere('key', 'low_stock_threshold'); @endphp
                    @if($thresholdSetting)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-4 px-6 py-5">
                            <div class="w-10 h-10 rounded-xl text-orange-500 bg-orange-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-layer-group text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <label for="setting_low_stock_threshold" class="block text-sm font-semibold text-gray-800">{{ $thresholdSetting->label }}</label>
                                <p class="text-xs text-gray-400 mt-0.5">Los productos con stock igual o menor a este n&uacute;mero generar&aacute;n alerta.</p>
                            </div>
                            <input type="number" id="setting_low_stock_threshold" name="settings[low_stock_threshold]"
                                value="{{ old('settings.low_stock_threshold', $thresholdSetting->value) }}"
                                min="1" max="100"
                                class="w-20 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-center font-semibold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        </div>
                    </div>
                    @endif

                    <!-- Save button mobile -->
                    <div class="mt-6 sm:hidden">
                        <button type="submit" onclick="lockBtn(this, 'Guardando...')" class="w-full bg-indigo-500 text-white py-3 rounded-xl text-sm font-semibold hover:bg-indigo-600 transition shadow-sm shadow-indigo-200">
                            <i class="fas fa-save mr-2"></i> Guardar cambios
                        </button>
                    </div>
                @elseif($group === 'apariencia')
                    {{-- Image upload cards --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach($items as $setting)
                        @php
                            $isLogo = $setting->key === 'site_logo';
                            $currentImage = $setting->value ? asset('storage/' . $setting->value) : null;
                        @endphp
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl {{ $iconColors[$setting->key] ?? 'text-gray-500 bg-gray-50' }} flex items-center justify-center flex-shrink-0">
                                        <i class="fas {{ $iconMap[$setting->key] ?? 'fa-cog' }} text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-800">{{ $setting->label }}</h3>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            @if($isLogo)
                                                PNG, JPG o WEBP. M&aacute;x 2MB. Fondo transparente recomendado.
                                            @else
                                                PNG, ICO o WEBP. M&aacute;x 2MB. Tama&ntilde;o recomendado: 512x512px.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                {{-- Current preview --}}
                                <div class="mb-4 flex items-center justify-center p-4 rounded-xl {{ $isLogo ? 'bg-gray-50 h-28' : 'bg-gray-900 h-24' }}">
                                    <img
                                        id="preview_{{ $setting->key }}"
                                        src="{{ $currentImage ?? asset($isLogo ? 'images/logo_arixna.png' : 'images/logo_arixna1024512_min.webp') }}"
                                        alt="{{ $setting->label }}"
                                        class="{{ $isLogo ? 'max-h-20' : 'max-h-12' }} max-w-full object-contain">
                                </div>

                                {{-- Upload area --}}
                                <label for="upload_{{ $setting->key }}"
                                    class="group flex flex-col items-center justify-center gap-2 p-5 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/30 transition-all"
                                    id="dropzone_{{ $setting->key }}">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 group-hover:bg-indigo-100 flex items-center justify-center transition">
                                        <i class="fas fa-cloud-arrow-up text-gray-400 group-hover:text-indigo-500 transition"></i>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm font-medium text-gray-600">
                                            <span class="text-indigo-500">Haz clic para subir</span> o arrastra aqu&iacute;
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5" id="filename_{{ $setting->key }}">Ning&uacute;n archivo seleccionado</p>
                                    </div>
                                    <input
                                        type="file"
                                        id="upload_{{ $setting->key }}"
                                        name="settings_file_{{ $setting->key }}"
                                        accept="image/png,image/jpeg,image/webp,image/x-icon,image/svg+xml"
                                        class="hidden"
                                        onchange="previewImage(this, '{{ $setting->key }}')">
                                </label>

                                @if($currentImage)
                                <p class="text-xs text-emerald-500 mt-3 flex items-center gap-1.5">
                                    <i class="fas fa-check-circle"></i> Imagen actual configurada
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Save button mobile -->
                    <div class="mt-6 sm:hidden">
                        <button type="submit" onclick="lockBtn(this, 'Guardando...')" class="w-full bg-indigo-500 text-white py-3 rounded-xl text-sm font-semibold hover:bg-indigo-600 transition shadow-sm shadow-indigo-200">
                            <i class="fas fa-save mr-2"></i> Guardar cambios
                        </button>
                    </div>
                @else
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="divide-y divide-gray-50">
                            @foreach($items as $setting)
                                @php
                                    $icon = $iconMap[$setting->key] ?? 'fa-cog';
                                    $isBrand = str_contains($icon, 'fab');
                                    $iconClass = $isBrand ? str_replace(' fab', '', $icon) : $icon;
                                    $iconPrefix = $isBrand ? 'fab' : 'fas';
                                    $colorClass = $iconColors[$setting->key] ?? 'text-gray-500 bg-gray-50';
                                @endphp
                                <div class="flex items-start gap-4 px-6 py-5 hover:bg-gray-50/40 transition">
                                    <div class="w-10 h-10 rounded-xl {{ $colorClass }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="{{ $iconPrefix }} {{ $iconClass }} text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <label for="setting_{{ $setting->key }}" class="block text-sm font-semibold text-gray-800 mb-1">
                                            {{ $setting->label }}
                                        </label>
                                        @if($setting->key === 'meta_description' || $setting->key === 'tagline' || $setting->key === 'whatsapp_message')
                                            <textarea
                                                id="setting_{{ $setting->key }}"
                                                name="settings[{{ $setting->key }}]"
                                                rows="2"
                                                class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition resize-none"
                                                placeholder="{{ $setting->label }}..."
                                            >{{ old("settings.{$setting->key}", $setting->value) }}</textarea>
                                            @if($setting->key === 'meta_description')
                                                <div class="flex items-center justify-between mt-1.5">
                                                    <p class="text-xs text-gray-400">Recomendado: 150-160 caracteres</p>
                                                    <p class="text-xs text-gray-400"><span id="metaCount">{{ strlen($setting->value ?? '') }}</span>/160</p>
                                                </div>
                                            @endif
                                        @else
                                            <div class="relative">
                                                @if($setting->type === 'url')
                                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-300"><i class="fas fa-link"></i></span>
                                                @elseif($setting->type === 'email')
                                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-300"><i class="fas fa-at"></i></span>
                                                @elseif($setting->type === 'tel')
                                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-300"><i class="fas fa-phone"></i></span>
                                                @endif
                                                <input
                                                    type="{{ $setting->type === 'url' ? 'url' : ($setting->type === 'email' ? 'email' : 'text') }}"
                                                    id="setting_{{ $setting->key }}"
                                                    name="settings[{{ $setting->key }}]"
                                                    value="{{ old("settings.{$setting->key}", $setting->value) }}"
                                                    class="w-full border border-gray-200 rounded-lg {{ in_array($setting->type, ['url', 'email', 'tel']) ? 'pl-9' : 'px-3.5' }} pr-3.5 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition"
                                                    placeholder="{{ $setting->label }}..."
                                                >
                                            </div>
                                            @if($setting->key === 'whatsapp_number')
                                                <p class="text-xs text-gray-400 mt-1.5">Formato: 51999999999 (código país + número, sin espacios ni +)</p>
                                            @elseif($setting->key === 'ruc')
                                                <p class="text-xs text-gray-400 mt-1.5">RUC de 11 dígitos</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Save button mobile -->
                    <div class="mt-6 sm:hidden">
                        <button type="submit" onclick="lockBtn(this, 'Guardando...')" class="w-full bg-indigo-500 text-white py-3 rounded-xl text-sm font-semibold hover:bg-indigo-600 transition shadow-sm shadow-indigo-200">
                            <i class="fas fa-save mr-2"></i> Guardar cambios
                        </button>
                    </div>
                @endif
            </div>
        @endforeach
    </form>
</div>

@endsection

@section('scripts')
<script>
function switchTab(group) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-indigo-500', 'text-indigo-600');
        b.classList.add('border-transparent', 'text-gray-400');
    });

    document.getElementById('panel-' + group).classList.remove('hidden');
    const btn = document.getElementById('tab-' + group);
    btn.classList.add('border-indigo-500', 'text-indigo-600');
    btn.classList.remove('border-transparent', 'text-gray-400');
}

function previewImage(input, key) {
    const preview = document.getElementById('preview_' + key);
    const filename = document.getElementById('filename_' + key);

    if (input.files && input.files[0]) {
        const file = input.files[0];
        filename.textContent = file.name;

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

// Drag & drop support
document.querySelectorAll('[id^="dropzone_"]').forEach(zone => {
    const key = zone.id.replace('dropzone_', '');
    const input = document.getElementById('upload_' + key);

    ['dragenter', 'dragover'].forEach(evt => {
        zone.addEventListener(evt, e => {
            e.preventDefault();
            zone.classList.add('border-indigo-400', 'bg-indigo-50/50');
        });
    });

    ['dragleave', 'drop'].forEach(evt => {
        zone.addEventListener(evt, e => {
            e.preventDefault();
            zone.classList.remove('border-indigo-400', 'bg-indigo-50/50');
        });
    });

    zone.addEventListener('drop', e => {
        if (e.dataTransfer.files.length > 0) {
            input.files = e.dataTransfer.files;
            previewImage(input, key);
        }
    });
});

// Notification emails management
const emailsHidden = document.getElementById('notificationEmailsHidden');
const emailChipsContainer = document.getElementById('emailChipsContainer');
const newEmailInput = document.getElementById('newEmailInput');

function getEmails() {
    if (!emailsHidden || !emailsHidden.value.trim()) return [];
    return emailsHidden.value.split(',').map(e => e.trim()).filter(e => e);
}

function saveEmails(emails) {
    emailsHidden.value = emails.join(',');
    renderEmailChips();
}

function renderEmailChips() {
    if (!emailChipsContainer) return;
    const emails = getEmails();
    emailChipsContainer.innerHTML = '';

    if (emails.length === 0) {
        emailChipsContainer.innerHTML = '<p class="text-sm text-gray-400 py-1">No hay correos configurados.</p>';
        return;
    }

    emails.forEach((email, i) => {
        const chip = document.createElement('span');
        chip.className = 'inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 pl-3 pr-1.5 py-1.5 rounded-lg text-sm font-medium';
        chip.innerHTML = `<i class="fas fa-envelope text-xs text-indigo-400"></i> ${email}
            <button type="button" onclick="removeNotificationEmail(${i})" class="ml-1 w-5 h-5 rounded-full hover:bg-indigo-200 flex items-center justify-center transition" title="Eliminar" aria-label="Eliminar">
                <i class="fas fa-times text-xs"></i>
            </button>`;
        emailChipsContainer.appendChild(chip);
    });
}

function addNotificationEmail() {
    if (!newEmailInput) return;
    const email = newEmailInput.value.trim().toLowerCase();
    if (!email) return;

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        newEmailInput.classList.add('border-red-400');
        newEmailInput.focus();
        setTimeout(() => newEmailInput.classList.remove('border-red-400'), 2000);
        return;
    }

    const emails = getEmails();
    if (emails.includes(email)) {
        newEmailInput.value = '';
        return;
    }

    emails.push(email);
    saveEmails(emails);
    newEmailInput.value = '';
    newEmailInput.focus();
}

function removeNotificationEmail(index) {
    const emails = getEmails();
    emails.splice(index, 1);
    saveEmails(emails);
}

renderEmailChips();

// Meta description counter
const metaInput = document.getElementById('setting_meta_description');
const metaCount = document.getElementById('metaCount');
if (metaInput && metaCount) {
    metaInput.addEventListener('input', () => {
        const len = metaInput.value.length;
        metaCount.textContent = len;
        metaCount.className = len > 160 ? 'text-red-500' : 'text-gray-400';
    });
}
</script>
@endsection

@extends('layouts.app')

@section('title', 'Libro de Reclamaciones - Arixna')
@section('meta_description', 'Libro de Reclamaciones virtual de Arixna conforme a las disposiciones de INDECOPI. Registra tu reclamo o queja.')

@section('styles')
    .complaint-form label {
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
    }
    .complaint-form input,
    .complaint-form select,
    .complaint-form textarea {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        transition: border-color 0.2s;
    }
    .complaint-form input:focus,
    .complaint-form select:focus,
    .complaint-form textarea:focus {
        outline: none;
        border-color: #f43f5e;
        box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.1);
    }
    .complaint-form .error-msg {
        color: #dc2626;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-10 text-center">
            <div class="flex items-center justify-center gap-3 mb-4">
                <img src="{{ !empty($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : asset('images/logo_arixna.png') }}" alt="Arixna" class="h-12">
            </div>
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-3">Libro de Reclamaciones</h1>
            <p class="text-gray-500 max-w-2xl mx-auto">Conforme a lo establecido en el Código de Protección y Defensa del Consumidor (Ley N° 29571) y el Decreto Supremo N° 011-2011-PCM.</p>
        </div>

        <!-- Info box -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-8">
            <div class="flex gap-3">
                <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                <div class="text-sm text-amber-800">
                    <p class="font-semibold mb-1">Antes de llenar el formulario, ten en cuenta:</p>
                    <ul class="space-y-1 list-disc list-inside">
                        <li><strong>Reclamo:</strong> Disconformidad relacionada a los productos o servicios adquiridos.</li>
                        <li><strong>Queja:</strong> Disconformidad respecto a la atención al público que no está relacionada con los productos o servicios.</li>
                    </ul>
                    <p class="mt-2">El proveedor dará respuesta a tu reclamo en un plazo máximo de <strong>30 días calendario</strong>.</p>
                </div>
            </div>
        </div>

        <div id="complaintErrors" class="bg-red-50 border border-red-200 rounded-xl p-5 mb-8 hidden">
            <div class="flex gap-3">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                <div class="text-sm text-red-700">
                    <p class="font-semibold mb-2">Por favor corrige los siguientes errores:</p>
                    <ul id="complaintErrorList" class="list-disc list-inside space-y-1"></ul>
                </div>
            </div>
        </div>

        <form id="complaintForm" action="{{ route('complaint.store') }}" method="POST" class="complaint-form space-y-8">
            @csrf

            <!-- Datos del proveedor (read-only) -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-store text-rose-500"></i>
                    1. Identificación del proveedor
                </h2>
                <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div><strong>Razón Social:</strong> {{ $settings['legal_name'] ?? 'Arixna E.I.R.L.' }}</div>
                    <div><strong>RUC:</strong> {{ $settings['ruc'] ?? 'Por definir' }}</div>
                    <div><strong>Dirección:</strong> {{ $settings['address'] ?? 'Por definir' }}</div>
                    <div><strong>Email:</strong> {{ $settings['contact_email'] ?? 'contacto@arixna.com' }}</div>
                </div>
            </div>

            <!-- Datos del consumidor -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-user text-rose-500"></i>
                    2. Identificación del consumidor reclamante
                </h2>
                <div class="grid md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="consumer_name">Nombre completo *</label>
                        <input type="text" id="consumer_name" name="consumer_name" value="{{ old('consumer_name', auth()->user()?->full_name) }}" required>
                        @error('consumer_name') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="consumer_document_type">Tipo de documento *</label>
                        <select id="consumer_document_type" name="consumer_document_type" required>
                            <option value="">Seleccionar</option>
                            <option value="DNI" {{ old('consumer_document_type') == 'DNI' ? 'selected' : '' }}>DNI</option>
                            <option value="CE" {{ old('consumer_document_type') == 'CE' ? 'selected' : '' }}>Carné de Extranjería</option>
                            <option value="Pasaporte" {{ old('consumer_document_type') == 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                        @error('consumer_document_type') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="consumer_document_number">Número de documento *</label>
                        <input type="text" id="consumer_document_number" name="consumer_document_number" value="{{ old('consumer_document_number', auth()->user()?->document_number) }}" required>
                        @error('consumer_document_number') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="consumer_email">Correo electrónico *</label>
                        <input type="email" id="consumer_email" name="consumer_email" value="{{ old('consumer_email', auth()->user()?->email) }}" required>
                        @error('consumer_email') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="consumer_phone">Teléfono *</label>
                        <input type="text" id="consumer_phone" name="consumer_phone" value="{{ old('consumer_phone', auth()->user()?->phone) }}" required>
                        @error('consumer_phone') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="consumer_address">Domicilio</label>
                        <input type="text" id="consumer_address" name="consumer_address" value="{{ old('consumer_address', auth()->user()?->address) }}">
                        @error('consumer_address') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Datos del apoderado -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-2 flex items-center gap-2">
                    <i class="fas fa-user-tie text-rose-500"></i>
                    3. Identificación del apoderado (opcional)
                </h2>
                <p class="text-sm text-gray-500 mb-5">Completa solo si otra persona presenta el reclamo en tu nombre.</p>
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="representative_name">Nombre del apoderado</label>
                        <input type="text" id="representative_name" name="representative_name" value="{{ old('representative_name') }}">
                    </div>
                    <div>
                        <label for="representative_email">Correo del apoderado</label>
                        <input type="email" id="representative_email" name="representative_email" value="{{ old('representative_email') }}">
                    </div>
                </div>
            </div>

            <!-- Bien contratado -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-box text-rose-500"></i>
                    4. Identificación del bien contratado
                </h2>
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="product_type">Tipo de bien *</label>
                        <select id="product_type" name="product_type" required>
                            <option value="">Seleccionar</option>
                            <option value="producto" {{ old('product_type') == 'producto' ? 'selected' : '' }}>Producto</option>
                            <option value="servicio" {{ old('product_type') == 'servicio' ? 'selected' : '' }}>Servicio</option>
                        </select>
                        @error('product_type') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="order_number">Número de pedido (si aplica)</label>
                        <input type="text" id="order_number" name="order_number" value="{{ old('order_number') }}" placeholder="Ej: ORD-20260301-0001">
                    </div>
                    <div class="md:col-span-2">
                        <label for="product_description">Descripción del producto o servicio *</label>
                        <input type="text" id="product_description" name="product_description" value="{{ old('product_description') }}" required placeholder="Describe el producto o servicio contratado">
                        @error('product_description') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Detalle del reclamo -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-file-alt text-rose-500"></i>
                    5. Detalle de la reclamación
                </h2>
                <div class="space-y-5">
                    <div>
                        <label class="block mb-3">Tipo *</label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="complaint_type" value="reclamo" {{ old('complaint_type', 'reclamo') == 'reclamo' ? 'checked' : '' }} class="w-4 h-4 text-rose-500" required>
                                <span><strong>Reclamo</strong></span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="complaint_type" value="queja" {{ old('complaint_type') == 'queja' ? 'checked' : '' }} class="w-4 h-4 text-rose-500">
                                <span><strong>Queja</strong></span>
                            </label>
                        </div>
                        @error('complaint_type') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="complaint_detail">Detalle *</label>
                        <textarea id="complaint_detail" name="complaint_detail" rows="5" required placeholder="Describe con detalle lo sucedido, incluyendo fechas y circunstancias relevantes.">{{ old('complaint_detail') }}</textarea>
                        @error('complaint_detail') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="consumer_request">Pedido del consumidor *</label>
                        <textarea id="consumer_request" name="consumer_request" rows="3" required placeholder="Indica qué solución esperas recibir (reembolso, cambio de producto, reparación, etc.)">{{ old('consumer_request') }}</textarea>
                        @error('consumer_request') <p class="error-msg">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex flex-col items-center gap-4">
                <p class="text-sm text-gray-500 text-center max-w-lg">
                    Al enviar este formulario, declaras que la información proporcionada es verídica. La formulación del reclamo no impide acudir directamente a otras vías de solución de controversias ni es requisito previo para interponer una denuncia ante INDECOPI.
                </p>
                <button type="submit" class="bg-gray-900 text-white px-10 py-3.5 rounded-full font-medium hover:bg-gray-800 transition-all">
                    Enviar reclamación
                </button>
            </div>

        </form>
    </div>
</div>

<!-- Toast container -->
<div id="toastContainer" class="fixed top-6 right-6 z-50 flex flex-col gap-3 pointer-events-none"></div>

@endsection

@section('scripts')
<script>
function showToast(title, message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    const isSuccess = type === 'success';

    toast.className = 'pointer-events-auto flex items-center gap-3 bg-white rounded-2xl shadow-lg border px-5 py-4 max-w-md transform translate-x-full transition-transform duration-300';
    toast.style.borderColor = isSuccess ? '#d1fae5' : '#fecaca';
    toast.innerHTML = `
        <div class="w-9 h-9 ${isSuccess ? 'bg-emerald-100' : 'bg-red-100'} rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas ${isSuccess ? 'fa-check text-emerald-500' : 'fa-times text-red-500'} text-sm"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900">${title}</p>
            <p class="text-xs text-gray-500 mt-0.5">${message}</p>
        </div>
        <button onclick="this.closest('.pointer-events-auto').remove()" class="text-gray-300 hover:text-gray-500 transition flex-shrink-0" aria-label="Cerrar notificación">
            <i class="fas fa-times text-xs"></i>
        </button>
    `;

    container.appendChild(toast);
    requestAnimationFrame(() => {
        requestAnimationFrame(() => toast.classList.remove('translate-x-full'));
    });
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 6000);
}

document.getElementById('complaintForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const btn = form.querySelector('button[type="submit"]');
    const btnText = btn.innerHTML;
    const errorsDiv = document.getElementById('complaintErrors');
    const errorsList = document.getElementById('complaintErrorList');

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Enviando...';
    errorsDiv.classList.add('hidden');

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(new FormData(form))),
        });

        const data = await res.json();

        if (res.ok) {
            showToast(
                'Reclamo registrado',
                data.message + (data.complaint_number ? ' N°: ' + data.complaint_number : ''),
                'success'
            );
            form.reset();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else if (res.status === 422 && data.errors) {
            errorsList.innerHTML = '';
            Object.values(data.errors).flat().forEach(err => {
                const li = document.createElement('li');
                li.textContent = err;
                errorsList.appendChild(li);
            });
            errorsDiv.classList.remove('hidden');
            errorsDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            showToast('Error', 'Ocurrió un error inesperado. Intenta de nuevo.', 'error');
        }
    } catch {
        showToast('Error', 'Error de conexión. Verifica tu internet.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = btnText;
    }
});
</script>
@endsection
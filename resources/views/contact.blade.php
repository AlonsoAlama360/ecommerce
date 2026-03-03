@extends('layouts.app')

@section('title', 'Contacto - Arixna')
@section('meta_description', 'Contáctanos para cualquier consulta sobre pedidos, productos o atención al cliente. Estamos para ayudarte.')

@section('styles')
    .contact-hero {
        background: linear-gradient(160deg, #faf5f0 0%, #f5ece3 40%, #efe4d8 100%);
    }
    .form-field {
        border: 1.5px solid #e5e7eb;
        transition: all 0.25s ease;
    }
    .form-field:focus {
        border-color: #1f2937;
        box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.06);
        outline: none;
    }
@endsection

@section('content')

<!-- Hero -->
<section class="contact-hero py-20 md:py-28">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-4">Hablemos</h1>
        <p class="text-gray-500 text-lg max-w-md mx-auto">¿Tienes alguna pregunta? Nos encantaría saber de ti.</p>
    </div>
</section>

<!-- Contenido -->
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 -mt-6">

    <!-- Canales rápidos -->
    <div class="grid sm:grid-cols-3 gap-4 mb-16">
        <a href="mailto:{{ $settings['contact_email'] ?? 'contacto@arixna.com' }}" class="group bg-white rounded-2xl border border-gray-100 p-6 flex items-center gap-4 hover:shadow-lg hover:shadow-gray-200/60 hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                <i class="fas fa-envelope text-white"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-900 text-sm">Email</p>
                <p class="text-gray-400 text-sm">{{ $settings['contact_email'] ?? 'contacto@arixna.com' }}</p>
            </div>
        </a>
        <div class="group bg-white rounded-2xl border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-clock text-white"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-900 text-sm">Horario</p>
                <p class="text-gray-400 text-sm">{{ $settings['business_hours'] ?? 'Lun - Vie, 9am - 6pm' }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <!-- Mobile: icono + iconos compactos -->
            <div class="sm:hidden flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-share-alt text-white"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm mb-1.5">Síguenos</p>
                    <div class="flex items-center gap-3">
                        <a href="{{ $settings['instagram_url'] ?? '#' }}" target="_blank" class="w-8 h-8 bg-gray-100 hover:bg-gradient-to-r hover:from-purple-500 hover:to-pink-500 rounded-lg flex items-center justify-center group/s transition-all duration-300">
                            <i class="fab fa-instagram text-gray-500 group-hover/s:text-white transition-colors text-sm"></i>
                        </a>
                        <a href="{{ $settings['facebook_url'] ?? '#' }}" target="_blank" class="w-8 h-8 bg-gray-100 hover:bg-blue-600 rounded-lg flex items-center justify-center group/s transition-all duration-300">
                            <i class="fab fa-facebook-f text-gray-500 group-hover/s:text-white transition-colors text-sm"></i>
                        </a>
                        <a href="{{ $settings['tiktok_url'] ?? '#' }}" target="_blank" class="w-8 h-8 bg-gray-100 hover:bg-gray-900 rounded-lg flex items-center justify-center group/s transition-all duration-300">
                            <i class="fab fa-tiktok text-gray-500 group-hover/s:text-white transition-colors text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Desktop: pills con nombre -->
            <div class="hidden sm:block">
                <p class="font-semibold text-gray-900 text-sm mb-3">Síguenos</p>
                <div class="flex items-center gap-2">
                    <a href="{{ $settings['instagram_url'] ?? '#' }}" target="_blank" class="group/s flex items-center gap-2 bg-gray-50 hover:bg-gradient-to-r hover:from-purple-500 hover:to-pink-500 rounded-lg px-3 py-2 transition-all duration-300">
                        <i class="fab fa-instagram text-gray-500 group-hover/s:text-white transition-colors text-sm"></i>
                        <span class="text-xs font-medium text-gray-500 group-hover/s:text-white transition-colors">Instagram</span>
                    </a>
                    <a href="{{ $settings['facebook_url'] ?? '#' }}" target="_blank" class="group/s flex items-center gap-2 bg-gray-50 hover:bg-blue-600 rounded-lg px-3 py-2 transition-all duration-300">
                        <i class="fab fa-facebook-f text-gray-500 group-hover/s:text-white transition-colors text-sm"></i>
                        <span class="text-xs font-medium text-gray-500 group-hover/s:text-white transition-colors">Facebook</span>
                    </a>
                    <a href="{{ $settings['tiktok_url'] ?? '#' }}" target="_blank" class="group/s flex items-center gap-2 bg-gray-50 hover:bg-gray-900 rounded-lg px-3 py-2 transition-all duration-300">
                        <i class="fab fa-tiktok text-gray-500 group-hover/s:text-white transition-colors text-sm"></i>
                        <span class="text-xs font-medium text-gray-500 group-hover/s:text-white transition-colors">TikTok</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-5 gap-12">

        <!-- Formulario -->
        <div class="lg:col-span-3 order-2 lg:order-1">

            <div id="contactErrors" class="mb-8 bg-red-50 border border-red-100 rounded-2xl p-5 hidden">
                <p class="font-semibold text-red-800 text-sm mb-2"><i class="fas fa-exclamation-triangle mr-1"></i> Corrige los siguientes errores:</p>
                <ul id="contactErrorList" class="list-disc list-inside text-sm text-red-600 space-y-0.5"></ul>
            </div>

            <form id="contactForm" action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-800 mb-2">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ old('name', auth()->user()?->full_name) }}" required placeholder="Tu nombre completo"
                            class="form-field w-full rounded-xl px-4 py-3.5 text-sm bg-white">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-800 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required placeholder="tu@email.com"
                            class="form-field w-full rounded-xl px-4 py-3.5 text-sm bg-white">
                    </div>
                </div>

                <div>
                    <label for="order_number" class="block text-sm font-medium text-gray-800 mb-2">N° de pedido <span class="text-gray-300 font-normal">(opcional)</span></label>
                    <input type="text" id="order_number" name="order_number" value="{{ old('order_number') }}" placeholder="ORD-20260301-0001"
                        class="form-field w-full rounded-xl px-4 py-3.5 text-sm bg-white">
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-800 mb-2">Asunto</label>
                    <select id="subject" name="subject" required
                        class="form-field w-full rounded-xl px-4 py-3.5 text-sm bg-white appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2212%22%20height%3D%2212%22%20viewBox%3D%220%200%2012%2012%22%3E%3Cpath%20d%3D%22M2%204l4%204%204-4%22%20stroke%3D%22%239CA3AF%22%20stroke-width%3D%221.5%22%20fill%3D%22none%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E')] bg-no-repeat bg-[right_1rem_center]">
                        <option value="">Selecciona un tema</option>
                        <option value="Consulta sobre un producto" {{ old('subject') == 'Consulta sobre un producto' ? 'selected' : '' }}>Consulta sobre un producto</option>
                        <option value="Estado de mi pedido" {{ old('subject') == 'Estado de mi pedido' ? 'selected' : '' }}>Estado de mi pedido</option>
                        <option value="Problema con mi pedido" {{ old('subject') == 'Problema con mi pedido' ? 'selected' : '' }}>Problema con mi pedido</option>
                        <option value="Cambio o devolución" {{ old('subject') == 'Cambio o devolución' ? 'selected' : '' }}>Cambio o devolución</option>
                        <option value="Problema con el pago" {{ old('subject') == 'Problema con el pago' ? 'selected' : '' }}>Problema con el pago</option>
                        <option value="Sugerencia" {{ old('subject') == 'Sugerencia' ? 'selected' : '' }}>Sugerencia</option>
                        <option value="Otro" {{ old('subject') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-800 mb-2">Mensaje</label>
                    <textarea id="message" name="message" rows="6" required placeholder="Cuéntanos cómo podemos ayudarte..."
                        class="form-field w-full rounded-xl px-4 py-3.5 text-sm bg-white resize-none">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="bg-gray-900 text-white px-10 py-4 rounded-full text-sm font-semibold hover:bg-gray-800 active:scale-[0.98] transition-all duration-200">
                    Enviar mensaje
                </button>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-2 order-1 lg:order-2 space-y-6">

            <!-- Card principal -->
            <div class="bg-gray-900 rounded-3xl p-8 text-white">
                <h3 class="font-serif text-xl font-semibold mb-3">¿Necesitas ayuda urgente?</h3>
                <p class="text-white/50 text-sm leading-relaxed mb-6">Para consultas sobre pedidos en curso, escríbenos directamente y te responderemos lo antes posible.</p>
                <a href="mailto:{{ $settings['contact_email'] ?? 'contacto@arixna.com' }}" class="inline-flex items-center gap-2 bg-white text-gray-900 px-6 py-3 rounded-full text-sm font-semibold hover:bg-gray-100 transition">
                    <i class="fas fa-envelope text-xs"></i> {{ $settings['contact_email'] ?? 'contacto@arixna.com' }}
                </a>
            </div>

            <!-- Links útiles -->
            <div class="bg-white rounded-3xl border border-gray-100 p-7">
                <h3 class="font-semibold text-gray-900 mb-1">Respuestas rápidas</h3>
                <p class="text-sm text-gray-400 mb-5">Quizás ya tenemos lo que buscas.</p>
                <div class="space-y-1.5">
                    <a href="{{ route('legal.faq') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-gray-50 transition group">
                        <div class="w-9 h-9 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-lightbulb text-amber-500 text-xs"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 flex-1">Preguntas Frecuentes</span>
                        <i class="fas fa-chevron-right text-gray-300 text-[10px] group-hover:text-gray-400 transition"></i>
                    </a>
                    <a href="{{ route('legal.returns') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-gray-50 transition group">
                        <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-exchange-alt text-blue-500 text-xs"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 flex-1">Cambios y Devoluciones</span>
                        <i class="fas fa-chevron-right text-gray-300 text-[10px] group-hover:text-gray-400 transition"></i>
                    </a>
                    <a href="{{ route('complaint.create') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-gray-50 transition group">
                        <div class="w-9 h-9 bg-rose-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-book-open text-rose-500 text-xs"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 flex-1">Libro de Reclamaciones</span>
                        <i class="fas fa-chevron-right text-gray-300 text-[10px] group-hover:text-gray-400 transition"></i>
                    </a>
                </div>
            </div>

            <!-- Dirección -->
            <div class="bg-white rounded-3xl border border-gray-100 p-7">
                <h3 class="font-semibold text-gray-900 mb-4">Encuéntranos</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-gray-300 mt-0.5"></i>
                        <p class="text-gray-600">{{ $settings['address'] ?? 'Por definir' }}</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-clock text-gray-300 mt-0.5"></i>
                        <p class="text-gray-600">{{ $settings['business_hours'] ?? '' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Toast container -->
<div id="toastContainer" class="fixed top-6 right-6 z-50 flex flex-col gap-3 pointer-events-none"></div>

@endsection

@section('scripts')
<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    const isSuccess = type === 'success';

    toast.className = 'pointer-events-auto flex items-center gap-3 bg-white rounded-2xl shadow-lg border px-5 py-4 max-w-sm transform translate-x-full transition-transform duration-300';
    toast.style.borderColor = isSuccess ? '#d1fae5' : '#fecaca';
    toast.innerHTML = `
        <div class="w-9 h-9 ${isSuccess ? 'bg-emerald-100' : 'bg-red-100'} rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas ${isSuccess ? 'fa-check text-emerald-500' : 'fa-times text-red-500'} text-sm"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900">${isSuccess ? 'Mensaje enviado' : 'Error'}</p>
            <p class="text-xs text-gray-500 mt-0.5">${message}</p>
        </div>
        <button onclick="this.closest('.pointer-events-auto').remove()" class="text-gray-300 hover:text-gray-500 transition flex-shrink-0">
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
    }, 5000);
}

document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const btn = form.querySelector('button[type="submit"]');
    const btnText = btn.innerHTML;
    const errorsDiv = document.getElementById('contactErrors');
    const errorsList = document.getElementById('contactErrorList');

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
            showToast(data.message, 'success');
            form.reset();
        } else if (res.status === 422 && data.errors) {
            errorsList.innerHTML = '';
            Object.values(data.errors).flat().forEach(err => {
                const li = document.createElement('li');
                li.textContent = err;
                errorsList.appendChild(li);
            });
            errorsDiv.classList.remove('hidden');
        } else {
            showToast('Ocurrió un error inesperado. Intenta de nuevo.', 'error');
        }
    } catch {
        showToast('Error de conexión. Verifica tu internet.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = btnText;
    }
});
</script>
@endsection
@extends('layouts.app')

@section('title', 'Preguntas Frecuentes - Arixna')
@section('meta_description', 'Resuelve tus dudas sobre compras, envíos, pagos, cambios y devoluciones en Arixna. Encuentra respuestas rápidas a las preguntas más comunes.')

@section('seo')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            {
                "@@type": "Question",
                "name": "¿Cómo realizo una compra en Arixna?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Navega por nuestro catálogo, elige los productos, agrégalos al carrito, completa tus datos de envío, elige tu método de pago y confirma tu pedido. Recibirás un correo de confirmación."
                }
            },
            {
                "@@type": "Question",
                "name": "¿Hacen envíos a todo el Perú?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Sí, realizamos envíos a todo el Perú. Lima Metropolitana: 3 a 7 días hábiles. Provincias: 5 a 12 días hábiles."
                }
            },
            {
                "@@type": "Question",
                "name": "¿Qué métodos de pago aceptan?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Aceptamos tarjetas de crédito y débito (Visa, Mastercard, American Express, Diners Club), billeteras digitales (Yape, Plin) y transferencia bancaria."
                }
            },
            {
                "@@type": "Question",
                "name": "¿Es seguro comprar en Arixna?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Sí. Nuestro sitio cuenta con certificado SSL, los pagos son procesados por pasarelas certificadas PCI DSS y no almacenamos datos de tarjetas en nuestros servidores."
                }
            },
            {
                "@@type": "Question",
                "name": "¿Puedo devolver un producto?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Sí, tienes 7 días calendario desde la recepción para solicitar una devolución. El producto debe estar sin uso, en su empaque original y con todos sus accesorios."
                }
            },
            {
                "@@type": "Question",
                "name": "¿Los productos son originales?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Sí, todos nuestros productos son 100% originales. Trabajamos directamente con marcas y distribuidores autorizados para garantizar la autenticidad."
                }
            },
            {
                "@@type": "Question",
                "name": "¿Los productos tienen garantía?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Sí, todos nuestros productos cuentan con garantía legal conforme al Código de Protección al Consumidor (Ley N° 29571)."
                }
            },
            {
                "@@type": "Question",
                "name": "¿Emiten boleta o factura?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Sí, emitimos comprobantes de pago con cada compra. Durante el checkout puedes elegir entre boleta o factura."
                }
            }
        ]
    }
    </script>
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@@type": "ListItem",
                "position": 1,
                "name": "Inicio",
                "item": "{{ url('/') }}"
            },
            {
                "@@type": "ListItem",
                "position": 2,
                "name": "Preguntas Frecuentes",
                "item": "{{ route('legal.faq') }}"
            }
        ]
    }
    </script>
@endsection

@section('styles')
    .faq-item summary {
        cursor: pointer;
        list-style: none;
    }
    .faq-item summary::-webkit-details-marker {
        display: none;
    }
    .faq-item summary::marker {
        display: none;
    }
    .faq-item[open] summary .faq-icon {
        transform: rotate(45deg);
    }
    .faq-item[open] .faq-answer {
        animation: fadeIn 0.3s ease;
    }
    @@keyframes fadeIn {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-10 text-center">
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-3">Preguntas Frecuentes</h1>
            <p class="text-gray-500 max-w-2xl mx-auto">Encuentra respuestas rápidas a las dudas más comunes sobre compras, envíos, pagos y más.</p>
        </div>

        <!-- Categorías de navegación -->
        <div class="flex flex-wrap justify-center gap-2 mb-10">
            <a href="#compras" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition">
                <i class="fas fa-shopping-bag mr-1.5"></i> Compras
            </a>
            <a href="#envios" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition">
                <i class="fas fa-truck mr-1.5"></i> Envíos
            </a>
            <a href="#pagos" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition">
                <i class="fas fa-credit-card mr-1.5"></i> Pagos
            </a>
            <a href="#cambios" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition">
                <i class="fas fa-exchange-alt mr-1.5"></i> Cambios y Devoluciones
            </a>
            <a href="#cuenta" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition">
                <i class="fas fa-user mr-1.5"></i> Mi Cuenta
            </a>
            <a href="#productos" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition">
                <i class="fas fa-box mr-1.5"></i> Productos
            </a>
        </div>

        <div class="space-y-10">

            {{-- ==================== COMPRAS ==================== --}}
            <section id="compras">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shopping-bag text-rose-500"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Compras</h2>
                </div>
                <div class="space-y-3">
                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Cómo realizo una compra?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Es muy fácil:</p>
                            <ol class="mt-2 space-y-1 list-decimal list-inside">
                                <li>Navega por nuestro catálogo y elige los productos que te gusten.</li>
                                <li>Haz clic en "Agregar al carrito".</li>
                                <li>Cuando estés listo, ve al carrito y haz clic en "Proceder al pago".</li>
                                <li>Completa tus datos de envío y elige tu método de pago.</li>
                                <li>Confirma tu pedido y recibirás un correo de confirmación.</li>
                            </ol>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Necesito crear una cuenta para comprar?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Puedes agregar productos al carrito sin cuenta, pero necesitarás registrarte o iniciar sesión para completar tu compra. Esto nos permite enviarte la confirmación del pedido y que puedas hacer seguimiento de tu orden. Puedes registrarte con tu email o usando tu cuenta de Google o Facebook.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Puedo modificar o cancelar mi pedido?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Si tu pedido aún no ha sido enviado, puedes solicitar la modificación o cancelación enviando un correo a <strong>{{ $settings['contact_email'] ?? 'contacto@arixna.com' }}</strong> con tu número de pedido. Una vez que el pedido ha sido despachado, ya no es posible cancelarlo, pero puedes solicitar una devolución al recibirlo.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Los precios incluyen IGV?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Sí, todos los precios mostrados en nuestra tienda incluyen el Impuesto General a las Ventas (IGV). El precio que ves es el precio final del producto, sin costos ocultos. Los costos de envío se calculan aparte y se muestran antes de confirmar tu compra.</p>
                        </div>
                    </details>
                </div>
            </section>

            {{-- ==================== ENVÍOS ==================== --}}
            <section id="envios">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-truck text-blue-500"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Envíos</h2>
                </div>
                <div class="space-y-3">
                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Hacen envíos a todo el Perú?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Sí, realizamos envíos a todo el Perú. Llegamos a Lima Metropolitana, todas las capitales de departamento y principales ciudades del país a través de nuestros operadores logísticos.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Cuánto demora el envío?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Los plazos de entrega estimados son:</p>
                            <ul class="mt-2 space-y-1 list-disc list-inside">
                                <li><strong>Lima Metropolitana:</strong> 3 a 7 días hábiles</li>
                                <li><strong>Provincias:</strong> 5 a 12 días hábiles</li>
                            </ul>
                            <p class="mt-2">Los plazos se cuentan desde la confirmación del pago. Recibirás un número de seguimiento por correo para rastrear tu pedido.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Cuánto cuesta el envío?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>El costo de envío depende del destino y se calcula automáticamente al ingresar tu dirección de entrega durante el proceso de compra. Podrás ver el monto exacto antes de confirmar tu pedido.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Cómo puedo rastrear mi pedido?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Una vez que tu pedido sea despachado, recibirás un correo electrónico con el número de seguimiento. También puedes ver el estado de tus pedidos ingresando a <a href="{{ route('orders.index') }}" class="text-rose-600 hover:text-rose-700 underline">Mis Pedidos</a> en tu cuenta.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Qué pasa si no estoy en casa cuando llega el pedido?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>El courier intentará la entrega hasta 2 veces. Si no encuentra a nadie, dejará un aviso con instrucciones para coordinar una nueva entrega. También puedes contactarnos a <strong>{{ $settings['contact_email'] ?? 'contacto@arixna.com' }}</strong> para coordinar la entrega.</p>
                        </div>
                    </details>
                </div>
            </section>

            {{-- ==================== PAGOS ==================== --}}
            <section id="pagos">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-credit-card text-green-500"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Pagos</h2>
                </div>
                <div class="space-y-3">
                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Qué métodos de pago aceptan?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Aceptamos los siguientes métodos de pago:</p>
                            <ul class="mt-2 space-y-1 list-disc list-inside">
                                <li><strong>Tarjetas de crédito y débito:</strong> Visa, Mastercard, American Express, Diners Club</li>
                                <li><strong>Billeteras digitales:</strong> Yape, Plin</li>
                                <li><strong>Transferencia bancaria</strong></li>
                            </ul>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Es seguro comprar en Arixna?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Totalmente. Tu seguridad es nuestra prioridad:</p>
                            <ul class="mt-2 space-y-1 list-disc list-inside">
                                <li>Nuestro sitio cuenta con certificado SSL que encripta toda la información.</li>
                                <li>Los pagos con tarjeta son procesados por pasarelas de pago certificadas PCI DSS.</li>
                                <li>No almacenamos datos de tarjetas en nuestros servidores.</li>
                            </ul>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Emiten boleta o factura?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Sí, emitimos comprobantes de pago con cada compra. Durante el proceso de checkout puedes elegir entre boleta o factura. Si necesitas factura, recuerda ingresar tu RUC y razón social al momento de la compra.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Puedo pagar en cuotas?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Sí, si pagas con tarjeta de crédito puedes optar por el pago en cuotas. Las opciones de cuotas disponibles dependerán de tu banco emisor y se mostrarán al momento del pago.</p>
                        </div>
                    </details>
                </div>
            </section>

            {{-- ==================== CAMBIOS Y DEVOLUCIONES ==================== --}}
            <section id="cambios">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exchange-alt text-amber-500"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Cambios y Devoluciones</h2>
                </div>
                <div class="space-y-3">
                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Puedo devolver un producto?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Sí, tienes <strong>7 días calendario</strong> desde la recepción del producto para solicitar una devolución. El producto debe estar sin uso, en su empaque original y con todos sus accesorios. Para más detalle, revisa nuestra <a href="{{ route('legal.returns') }}" class="text-rose-600 hover:text-rose-700 underline">Política de Cambios y Devoluciones</a>.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Cómo solicito un cambio o devolución?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Envía un correo a <strong>{{ $settings['contact_email'] ?? 'contacto@arixna.com' }}</strong> indicando:</p>
                            <ul class="mt-2 space-y-1 list-disc list-inside">
                                <li>Tu número de pedido</li>
                                <li>El producto que deseas cambiar o devolver</li>
                                <li>El motivo</li>
                                <li>Fotos del producto (si hay defecto o daño)</li>
                            </ul>
                            <p class="mt-2">Evaluaremos tu solicitud en un plazo máximo de 3 días hábiles.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Cuánto demora el reembolso?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Una vez aprobada la devolución, el reembolso se realiza en un plazo de <strong>15 días hábiles</strong> por el mismo medio de pago utilizado. Si pagaste con tarjeta, puede tomar hasta 2 ciclos de facturación adicionales dependiendo de tu banco.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">Recibí un producto defectuoso, ¿qué hago?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Lamentamos mucho esta situación. Contáctanos inmediatamente a <strong>{{ $settings['contact_email'] ?? 'contacto@arixna.com' }}</strong> con fotos del producto y del empaque. En caso de defectos de fábrica, tienes hasta <strong>30 días</strong> para reportarlo y los costos de envío de devolución corren por nuestra cuenta. También puedes usar nuestro <a href="{{ route('complaint.create') }}" class="text-rose-600 hover:text-rose-700 underline">Libro de Reclamaciones</a>.</p>
                        </div>
                    </details>
                </div>
            </section>

            {{-- ==================== MI CUENTA ==================== --}}
            <section id="cuenta">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-purple-500"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Mi Cuenta</h2>
                </div>
                <div class="space-y-3">
                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Cómo creo una cuenta?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Puedes crear tu cuenta de tres formas:</p>
                            <ul class="mt-2 space-y-1 list-disc list-inside">
                                <li><strong>Email:</strong> Haz clic en "Registrarse" e ingresa tus datos.</li>
                                <li><strong>Google:</strong> Haz clic en "Continuar con Google" para registrarte con tu cuenta de Gmail.</li>
                                <li><strong>Facebook:</strong> Haz clic en "Continuar con Facebook" para usar tu cuenta de Facebook.</li>
                            </ul>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">Olvidé mi contraseña, ¿cómo la recupero?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Haz clic en <a href="{{ route('password.request') }}" class="text-rose-600 hover:text-rose-700 underline">"¿Olvidaste tu contraseña?"</a> en la página de inicio de sesión. Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña. El enlace tiene una validez de 60 minutos.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Cómo actualizo mis datos personales?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Inicia sesión y ve a <a href="{{ route('profile.show') }}" class="text-rose-600 hover:text-rose-700 underline">Mi Perfil</a>. Desde ahí puedes actualizar tu nombre, email, teléfono, documento de identidad, dirección y contraseña.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Qué es la lista de deseos?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>La lista de deseos te permite guardar productos que te interesan para comprarlos más adelante. Solo haz clic en el ícono del corazón en cualquier producto y se agregará a tu <a href="{{ route('wishlist.index') }}" class="text-rose-600 hover:text-rose-700 underline">Lista de Deseos</a>. Necesitas tener una cuenta para usar esta función.</p>
                        </div>
                    </details>
                </div>
            </section>

            {{-- ==================== PRODUCTOS ==================== --}}
            <section id="productos">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-box text-teal-500"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Productos</h2>
                </div>
                <div class="space-y-3">
                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Los productos son originales?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Sí, todos nuestros productos son 100% originales. Trabajamos directamente con marcas y distribuidores autorizados para garantizar la autenticidad de cada artículo que vendemos.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Los productos tienen garantía?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Sí, todos nuestros productos cuentan con garantía legal conforme al Código de Protección al Consumidor (Ley N° 29571). Si tu producto presenta un defecto de fábrica, puedes solicitar la reparación, reposición o devolución del monto pagado.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Las fotos corresponden al producto real?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Nos esforzamos para que las fotos sean lo más fieles posible al producto real. Sin embargo, pueden existir variaciones menores de color debido a la configuración de tu pantalla o monitor. Cada producto incluye una descripción detallada con sus especificaciones.</p>
                        </div>
                    </details>

                    <details class="faq-item bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <summary class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-900 pr-4">¿Un producto aparece como agotado, volverá a estar disponible?</span>
                            <span class="faq-icon text-gray-400 text-xl transition-transform duration-300 flex-shrink-0">+</span>
                        </summary>
                        <div class="faq-answer px-6 pb-5 text-gray-600 text-sm leading-relaxed">
                            <p>Reponemos stock constantemente. Te recomendamos agregar el producto a tu lista de deseos y suscribirte a nuestro newsletter para recibir notificaciones cuando esté disponible nuevamente.</p>
                        </div>
                    </details>
                </div>
            </section>

        </div>

        <!-- CTA final -->
        <div class="mt-12 bg-white rounded-2xl shadow-sm p-8 text-center">
            <h2 class="font-serif text-xl font-semibold text-gray-900 mb-2">¿No encontraste lo que buscabas?</h2>
            <p class="text-gray-500 mb-5">Nuestro equipo está listo para ayudarte.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="mailto:{{ $settings['contact_email'] ?? 'contacto@arixna.com' }}" class="bg-gray-900 text-white px-6 py-3 rounded-full font-medium hover:bg-gray-800 transition inline-flex items-center justify-center gap-2">
                    <i class="fas fa-envelope"></i> {{ $settings['contact_email'] ?? 'contacto@arixna.com' }}
                </a>
                <a href="{{ route('complaint.create') }}" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-full font-medium hover:bg-gray-50 transition inline-flex items-center justify-center gap-2">
                    <i class="fas fa-book"></i> Libro de Reclamaciones
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

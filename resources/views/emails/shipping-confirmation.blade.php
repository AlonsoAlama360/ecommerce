<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pedido Enviado</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F5F1EC; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased;">

    <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all;">
        Tu pedido {{ $order->order_number }} est&aacute; en camino &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #F5F1EC;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width: 560px; width: 100%;">

                    <!-- Logo -->
                    <tr>
                        <td style="padding: 0 0 32px; text-align: center;">
                            <img src="https://arixna.com/images/logo_arixna.png" alt="Arixna" style="height: 36px;">
                        </td>
                    </tr>

                    <!-- Main card -->
                    <tr>
                        <td style="background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

                                <!-- Header -->
                                <tr>
                                    <td style="padding: 40px 40px 24px; text-align: center;">
                                        <div style="width: 64px; height: 64px; background-color: #F0ECE7; border-radius: 50%; margin: 0 auto 20px; line-height: 64px; font-size: 28px;">&#128666;</div>
                                        <p style="margin: 0 0 6px; color: #C39563; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">Pedido enviado</p>
                                        <h1 style="margin: 0 0 8px; color: #1A1A1A; font-size: 22px; font-weight: 700;">&#161;Tu pedido est&aacute; en camino!</h1>
                                        <p style="margin: 0; color: #888; font-size: 14px; line-height: 1.5;">Hola {{ explode(' ', $order->customer_name)[0] }}, tu pedido <strong style="color: #333;">{{ $order->order_number }}</strong> ha sido despachado.</p>
                                    </td>
                                </tr>

                                <!-- Tracking -->
                                @if($order->tracking_number)
                                <tr>
                                    <td style="padding: 0 40px 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #F9F7F4; border-radius: 12px;">
                                            <tr>
                                                <td style="padding: 20px 24px;">
                                                    <p style="margin: 0 0 4px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">N&uacute;mero de seguimiento</p>
                                                    <p style="margin: 0; color: #1A1A1A; font-size: 18px; font-weight: 700; letter-spacing: 1px;">{{ $order->tracking_number }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif

                                <!-- Separator -->
                                <tr>
                                    <td style="padding: 0 40px;"><div style="height: 1px; background-color: #F0ECE7;"></div></td>
                                </tr>

                                <!-- Products -->
                                <tr>
                                    <td style="padding: 24px 40px 8px;">
                                        <p style="margin: 0 0 16px; color: #AAA; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">Productos enviados</p>

                                        @foreach($order->items as $item)
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 14px;">
                                            <tr>
                                                <td style="vertical-align: top; padding-right: 12px;">
                                                    <div style="width: 8px; height: 8px; background-color: #D4A574; border-radius: 50%; margin-top: 6px;"></div>
                                                </td>
                                                <td style="vertical-align: top; width: 100%;">
                                                    <p style="margin: 0; color: #333; font-size: 14px; font-weight: 600;">{{ $item->product_name }}</p>
                                                    <p style="margin: 2px 0 0; color: #AAA; font-size: 12px;">Cant: {{ $item->quantity }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                        @endforeach
                                    </td>
                                </tr>

                                <!-- Separator -->
                                <tr>
                                    <td style="padding: 8px 40px;"><div style="height: 1px; background-color: #F0ECE7;"></div></td>
                                </tr>

                                <!-- Shipping address -->
                                @if($order->shipping_address)
                                <tr>
                                    <td style="padding: 24px 40px;">
                                        <p style="margin: 0 0 4px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Direcci&oacute;n de entrega</p>
                                        <p style="margin: 0; color: #333; font-size: 13px; font-weight: 600;">{{ $order->shipping_address }}</p>
                                    </td>
                                </tr>
                                @endif

                                <!-- CTA -->
                                <tr>
                                    <td style="padding: 8px 40px 36px; text-align: center;">
                                        <a href="{{ url('/mis-pedidos') }}" target="_blank"
                                            style="display: inline-block; background-color: #1A1A1A; color: #FFFFFF; text-decoration: none; padding: 14px 40px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                                            Ver mi pedido
                                        </a>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    <!-- Help link -->
                    @php $whatsapp = App\Models\SiteSetting::get('whatsapp_number'); @endphp
                    @if($whatsapp)
                    <tr>
                        <td style="padding: 24px 0 0; text-align: center;">
                            <p style="margin: 0; color: #999; font-size: 13px;">
                                &#191;Dudas sobre tu env&iacute;o?
                                <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hola, consulta sobre mi pedido ' . $order->order_number) }}" target="_blank" style="color: #C39563; font-weight: 600; text-decoration: none;">Escr&iacute;benos</a>
                            </p>
                        </td>
                    </tr>
                    @endif

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 0 0; text-align: center;">
                            <p style="margin: 0 0 4px; color: #BBB; font-size: 11px;">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                            <p style="margin: 0; color: #CCC; font-size: 10px;">Este es un correo autom&aacute;tico, por favor no respondas.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Deja tu opinión</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F5F1EC; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased;">

    <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all;">
        Cu&eacute;ntanos qu&eacute; te parecieron tus productos &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
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
                                        <div style="width: 64px; height: 64px; background-color: #F0ECE7; border-radius: 50%; margin: 0 auto 20px; line-height: 64px; font-size: 28px;">&#11088;</div>
                                        <p style="margin: 0 0 6px; color: #C39563; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">Tu opini&oacute;n importa</p>
                                        <h1 style="margin: 0 0 8px; color: #1A1A1A; font-size: 22px; font-weight: 700;">&#191;Qu&eacute; te parecieron tus productos?</h1>
                                        <p style="margin: 0; color: #888; font-size: 14px; line-height: 1.5;">Hola {{ explode(' ', $order->customer_name)[0] }}, esperamos que est&eacute;s disfrutando tu compra. Tu opini&oacute;n nos ayuda a mejorar y ayuda a otros clientes.</p>
                                    </td>
                                </tr>

                                <!-- Separator -->
                                <tr>
                                    <td style="padding: 0 40px;"><div style="height: 1px; background-color: #F0ECE7;"></div></td>
                                </tr>

                                <!-- Products to review -->
                                <tr>
                                    <td style="padding: 24px 40px 8px;">
                                        <p style="margin: 0 0 16px; color: #AAA; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">Productos para calificar</p>

                                        @foreach($order->items as $item)
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px; background-color: #FAFAF8; border-radius: 10px;">
                                            <tr>
                                                <td style="padding: 16px;">
                                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="vertical-align: middle; width: 100%;">
                                                                <p style="margin: 0 0 4px; color: #333; font-size: 14px; font-weight: 600;">{{ $item->product_name }}</p>
                                                                <p style="margin: 0; color: #C39563; font-size: 20px; letter-spacing: 2px;">&#9734;&#9734;&#9734;&#9734;&#9734;</p>
                                                            </td>
                                                            <td style="vertical-align: middle; text-align: right; white-space: nowrap; padding-left: 16px;">
                                                                @if($item->product && $item->product->slug)
                                                                <a href="{{ url('/producto/' . $item->product->slug) }}" target="_blank"
                                                                    style="display: inline-block; background-color: #D4A574; color: #FFFFFF; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                                                                    Calificar
                                                                </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </table>
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

                                <!-- Motivation text -->
                                <tr>
                                    <td style="padding: 24px 40px 36px; text-align: center;">
                                        <p style="margin: 0 0 20px; color: #888; font-size: 13px; line-height: 1.6;">Tus rese&ntilde;as ayudan a otros compradores a elegir mejor. Solo toma un minuto y hace una gran diferencia.</p>
                                        <a href="{{ url('/mis-pedidos') }}" target="_blank"
                                            style="display: inline-block; background-color: #1A1A1A; color: #FFFFFF; text-decoration: none; padding: 14px 40px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                                            Ver mis compras
                                        </a>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

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

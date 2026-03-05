<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tu carrito te espera</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F5F1EC; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased;">

    <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all;">
        Tienes productos esperando en tu carrito &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
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
                                        <div style="width: 64px; height: 64px; background-color: #F0ECE7; border-radius: 50%; margin: 0 auto 20px; line-height: 64px; font-size: 28px;">&#128722;</div>
                                        <p style="margin: 0 0 6px; color: #C39563; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">Tu carrito te espera</p>
                                        <h1 style="margin: 0 0 8px; color: #1A1A1A; font-size: 22px; font-weight: 700;">&#161;No dejes pasar tus favoritos!</h1>
                                        <p style="margin: 0; color: #888; font-size: 14px; line-height: 1.5;">Hola {{ $abandonedCart->user->first_name }}, notamos que dejaste algunos productos en tu carrito. Est&aacute;n esper&aacute;ndote.</p>
                                    </td>
                                </tr>

                                <!-- Separator -->
                                <tr>
                                    <td style="padding: 0 40px;"><div style="height: 1px; background-color: #F0ECE7;"></div></td>
                                </tr>

                                <!-- Products -->
                                <tr>
                                    <td style="padding: 24px 40px 8px;">
                                        <p style="margin: 0 0 16px; color: #AAA; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">En tu carrito</p>

                                        @php $total = 0; @endphp
                                        @foreach($products as $product)
                                        @php
                                            $price = $product['sale_price'] ?? $product['price'];
                                            $lineTotal = $price * $product['quantity'];
                                            $total += $lineTotal;
                                        @endphp
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 16px; background-color: #FAFAF8; border-radius: 10px;">
                                            <tr>
                                                <td style="padding: 16px;">
                                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            @if(!empty($product['image']))
                                                            <td style="vertical-align: middle; width: 56px; padding-right: 14px;">
                                                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" style="width: 56px; height: 56px; border-radius: 8px; object-fit: cover;">
                                                            </td>
                                                            @endif
                                                            <td style="vertical-align: middle; width: 100%;">
                                                                <p style="margin: 0 0 2px; color: #333; font-size: 14px; font-weight: 600;">{{ $product['name'] }}</p>
                                                                <p style="margin: 0; color: #AAA; font-size: 12px;">Cant: {{ $product['quantity'] }}</p>
                                                            </td>
                                                            <td style="vertical-align: middle; text-align: right; white-space: nowrap; padding-left: 16px;">
                                                                <p style="margin: 0; color: #333; font-size: 14px; font-weight: 700;">S/ {{ number_format($lineTotal, 2) }}</p>
                                                                @if(!empty($product['sale_price']) && $product['sale_price'] < $product['price'])
                                                                <p style="margin: 2px 0 0; color: #AAA; font-size: 11px; text-decoration: line-through;">S/ {{ number_format($product['price'] * $product['quantity'], 2) }}</p>
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

                                <!-- Total -->
                                <tr>
                                    <td style="padding: 0 40px 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top: 1px dashed #E5E0DA; padding-top: 14px;">
                                            <tr>
                                                <td style="color: #1A1A1A; font-size: 16px; font-weight: 800;">Total estimado</td>
                                                <td style="color: #1A1A1A; font-size: 18px; font-weight: 800; text-align: right;">S/ {{ number_format($total, 2) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- CTA -->
                                <tr>
                                    <td style="padding: 8px 40px 36px; text-align: center;">
                                        <a href="{{ url('/carrito') }}" target="_blank"
                                            style="display: inline-block; background-color: #D4A574; color: #FFFFFF; text-decoration: none; padding: 14px 44px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                                            Completar mi compra
                                        </a>
                                        <p style="margin: 14px 0 0; color: #AAA; font-size: 12px;">Los productos est&aacute;n sujetos a disponibilidad.</p>
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

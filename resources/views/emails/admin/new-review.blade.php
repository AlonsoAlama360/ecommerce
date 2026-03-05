<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reseña</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F5F1EC; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #F5F1EC;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width: 560px; width: 100%;">

                    <tr>
                        <td style="padding: 0 0 32px; text-align: center;">
                            <img src="https://arixna.com/images/logo_arixna.png" alt="Arixna" style="height: 36px;">
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

                                <tr>
                                    <td style="padding: 40px 40px 24px; text-align: center;">
                                        <div style="width: 64px; height: 64px; background-color: #FFFBEB; border-radius: 50%; margin: 0 auto 20px; line-height: 64px; font-size: 28px;">&#11088;</div>
                                        <p style="margin: 0 0 6px; color: #D97706; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">Nueva rese&ntilde;a</p>
                                        <p style="margin: 0 0 8px; color: #D97706; font-size: 24px; letter-spacing: 4px;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)&#9733;@else<span style="color: #E5E0DA;">&#9733;</span>@endif
                                            @endfor
                                        </p>
                                        <h1 style="margin: 0; color: #1A1A1A; font-size: 18px; font-weight: 700;">{{ $review->product?->name }}</h1>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 0 40px;"><div style="height: 1px; background-color: #F0ECE7;"></div></td>
                                </tr>

                                <tr>
                                    <td style="padding: 24px 40px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding-bottom: 12px;">
                                                    <p style="margin: 0 0 2px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Cliente</p>
                                                    <p style="margin: 0; color: #333; font-size: 14px; font-weight: 600;">{{ $review->user?->first_name }} {{ $review->user?->last_name }}</p>
                                                </td>
                                            </tr>
                                            @if($review->title)
                                            <tr>
                                                <td style="padding-bottom: 12px;">
                                                    <p style="margin: 0 0 2px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">T&iacute;tulo</p>
                                                    <p style="margin: 0; color: #333; font-size: 14px; font-weight: 600;">{{ $review->title }}</p>
                                                </td>
                                            </tr>
                                            @endif
                                            @if($review->comment)
                                            <tr>
                                                <td>
                                                    <p style="margin: 0 0 2px; color: #AAA; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Comentario</p>
                                                    <div style="background-color: #F9F7F4; border-radius: 10px; padding: 16px; margin-top: 6px;">
                                                        <p style="margin: 0; color: #444; font-size: 14px; line-height: 1.6;">{{ $review->comment }}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 8px 40px 36px; text-align: center;">
                                        <a href="{{ url('/admin/reviews') }}" target="_blank"
                                            style="display: inline-block; background-color: #1A1A1A; color: #FFFFFF; text-decoration: none; padding: 14px 40px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                                            Ver rese&ntilde;as
                                        </a>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 24px 0 0; text-align: center;">
                            <p style="margin: 0; color: #CCC; font-size: 10px;">Notificaci&oacute;n autom&aacute;tica del sistema &middot; {{ config('app.name') }}</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

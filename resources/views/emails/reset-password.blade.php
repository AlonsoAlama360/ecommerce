<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Restablecer Contraseña</title>
    <!--[if mso]>
    <style>
        table {border-collapse: collapse;}
        .fallback-font {font-family: Arial, sans-serif !important;}
    </style>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #F3EDE7; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased;">

    <!-- Preheader text (hidden) -->
    <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all;">
        Restablece tu contraseña de Arixna &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #F3EDE7;">
        <tr>
            <td align="center" style="padding: 30px 16px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%;">

                    <!-- Top accent bar -->
                    <tr>
                        <td style="height: 5px; background: linear-gradient(90deg, #D4A574, #C39563, #D4A574); border-radius: 20px 20px 0 0;"></td>
                    </tr>

                    <!-- Header with logo -->
                    <tr>
                        <td style="background-color: #FFFFFF; padding: 35px 40px 25px; text-align: center;">
                            <img src="https://arixna.com/images/logo_arixna.png"
                                alt="Arixna" style="height: 45px; display: inline-block;">
                        </td>
                    </tr>

                    <!-- Hero section -->
                    <tr>
                        <td style="background-color: #FFFFFF; padding: 0 40px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #D4A574 0%, #C39563 50%, #B8864F 100%); border-radius: 16px; padding: 40px 30px; text-align: center;">
                                        <div style="font-size: 48px; line-height: 1; margin-bottom: 16px;">&#128274;</div>
                                        <h1 style="margin: 0 0 8px; color: #FFFFFF; font-size: 26px; font-weight: 700; letter-spacing: 0.3px; line-height: 1.3;">
                                            Restablecer Contrase&ntilde;a
                                        </h1>
                                        <p style="margin: 0; color: rgba(255,255,255,0.85); font-size: 15px; line-height: 1.5;">
                                            Hola, {{ $user->first_name }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body content -->
                    <tr>
                        <td style="background-color: #FFFFFF; padding: 35px 40px 20px;">
                            <p style="margin: 0 0 20px; color: #4A4A4A; font-size: 15px; line-height: 1.7;">
                                Recibimos una solicitud para restablecer la contrase&ntilde;a de tu cuenta en
                                <strong style="color: #C39563;">Arixna</strong>. Haz clic en el bot&oacute;n
                                de abajo para crear una nueva contrase&ntilde;a.
                            </p>
                            <p style="margin: 0 0 10px; color: #4A4A4A; font-size: 15px; line-height: 1.7;">
                                Este enlace expirar&aacute; en <strong>60 minutos</strong> por seguridad.
                            </p>
                        </td>
                    </tr>

                    <!-- Info card -->
                    <tr>
                        <td style="background-color: #FFFFFF; padding: 0 40px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 10px;">
                                <tr>
                                    <td style="background-color: #FDF9F5; border-radius: 12px; padding: 20px; border-left: 4px solid #D4A574;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="44" valign="top">
                                                    <div style="width: 36px; height: 36px; background-color: #D4A574; border-radius: 8px; text-align: center; line-height: 36px; font-size: 16px;">
                                                        &#128161;
                                                    </div>
                                                </td>
                                                <td style="padding-left: 14px;">
                                                    <p style="margin: 0 0 3px; color: #333; font-weight: 700; font-size: 14px;">Consejo de seguridad</p>
                                                    <p style="margin: 0; color: #888; font-size: 13px; line-height: 1.5;">Usa una contrase&ntilde;a &uacute;nica con al menos 8 caracteres, incluyendo may&uacute;sculas, min&uacute;sculas y n&uacute;meros.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td style="background-color: #FFFFFF; padding: 25px 40px 35px; text-align: center;">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                <tr>
                                    <td style="border-radius: 12px; background-color: #D4A574;">
                                        <a href="{{ $resetUrl }}"
                                            target="_blank"
                                            style="display: inline-block; background-color: #D4A574; color: #FFFFFF; text-decoration: none; padding: 15px 45px; border-radius: 12px; font-size: 15px; font-weight: 700; letter-spacing: 0.5px; mso-padding-alt: 0; text-align: center;">
                                            <!--[if mso]><i style="mso-font-width: -100%; mso-text-raise: 22pt;">&nbsp;</i><![endif]-->
                                            <span style="mso-text-raise: 11pt;">Restablecer Contrase&ntilde;a</span>
                                            <!--[if mso]><i style="mso-font-width: -100%;">&nbsp;</i><![endif]-->
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin: 18px 0 0; color: #AAAAAA; font-size: 12px;">
                                O copia este enlace: <a href="{{ $resetUrl }}" style="color: #C39563; word-break: break-all;">{{ $resetUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="background-color: #FFFFFF; padding: 0 40px;">
                            <div style="height: 1px; background-color: #EDE8E3;"></div>
                        </td>
                    </tr>

                    <!-- Security notice -->
                    <tr>
                        <td style="background-color: #FFFFFF; padding: 25px 40px 30px;">
                            <p style="margin: 0 0 8px; color: #999; font-size: 13px; line-height: 1.6;">
                                Si no solicitaste restablecer tu contrase&ntilde;a, puedes ignorar este correo de forma segura. Tu contrase&ntilde;a no cambiar&aacute;.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F9F5F0; border-radius: 0 0 20px 20px; padding: 28px 40px; text-align: center;">
                            <p style="margin: 0 0 15px;">
                                <a href="https://wa.me/{{ config('app.whatsapp_phone') }}" style="display: inline-block; width: 32px; height: 32px; background-color: #D4A574; border-radius: 8px; text-align: center; line-height: 32px; text-decoration: none; margin: 0 4px; font-size: 14px;">&#128172;</a>
                            </p>
                            <p style="margin: 0 0 8px; color: #999999; font-size: 12px;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
                            </p>
                            <p style="margin: 0; color: #BBBBBB; font-size: 11px; line-height: 1.5;">
                                Recibiste este correo porque solicitaste restablecer tu contrase&ntilde;a.<br>
                                Si no fuiste t&uacute;, puedes ignorar este mensaje.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

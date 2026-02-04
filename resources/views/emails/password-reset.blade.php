<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .btn { display: inline-block; padding: 12px 24px; background: #333; color: white; text-decoration: none; border-radius: 4px; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Redefinir senha</h1>

        <p>Você solicitou a redefinição da sua senha no melembre.</p>

        <p>Clique no botão abaixo para criar uma nova senha:</p>

        <p style="margin: 30px 0;">
            <a href="{{ $url }}" class="btn">Redefinir senha</a>
        </p>

        <p>Se você não solicitou esta redefinição, ignore este email.</p>

        <div class="footer">
            <p>Obrigado,<br>
            Equipe melembre 🔥</p>
        </div>
    </div>
</body>
</html>

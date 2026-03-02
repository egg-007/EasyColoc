<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invitation à rejoindre {{ $colocationName }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #334155; line-height: 1.6; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; padding: 40px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .logo { font-size: 24px; font-weight: bold; color: #4f46e5; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; color: #0f172a; margin-bottom: 15px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; margin-right: 10px; }
        .button-danger { background-color: #ef4444; }
        .footer { margin-top: 30px; font-size: 14px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">EasyColoc</div>
        
        <div class="title">Bonjour !</div>
        
        <p><strong>{{ $senderName }}</strong> vous invite à rejoindre la colocation <strong>"{{ $colocationName }}"</strong> sur EasyColoc.</p>
        
        <p>Pour accepter l'invitation et commencer à gérer vos dépenses communes, cliquez sur le bouton ci-dessous :</p>
        
        <div style="margin-top: 30px; margin-bottom: 30px;">
            <a href="{{ $acceptUrl }}" class="button">Accepter l'invitation</a>
            <a href="{{ $refuseUrl }}" class="button button-danger">Refuser</a>
        </div>
        
        <p>Si vous n'êtes pas concerné, vous pouvez simplement ignorer cet email.</p>
        
        <div class="footer">
            <p>L'équipe EasyColoc</p>
        </div>
    </div>
</body>
</html>

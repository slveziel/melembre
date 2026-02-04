# Configuração de Email - melembre

## Desenvolvimento (atual)

Os emails são **salvos em log** para teste:
```
storage/logs/laravel.log
```

Para ver os links de reset de senha:
```bash
tail -f storage/logs/laravel.log | grep "password"
```

---

## Produção - Ativar emails reais

### Opção 1: Resend (recomendado, 3k emails/grátis/mês)

1. Crie conta em https://resend.com
2. Pegue sua API Key
3. Edite `.env`:
```env
MAIL_MAILER=resend
RESEND_API_KEY=re_sua_api_key_aqui
MAIL_FROM_ADDRESS=noreply@seudominio.com
MAIL_FROM_NAME=melembre
```

### Opção 2: Gmail SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=seuemail@gmail.com
MAIL_PASSWORD=sua_senha_de_app
MAIL_FROM_ADDRESS=seuemail@gmail.com
MAIL_FROM_NAME=melembre
```

> ⚠️ Necesário ativar "Senhas de app" no Google: https://myaccount.google.com/apppasswords

### Opção 3: SendGrid/Mailgun

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=sua_api_key
```

---

## SSL/HTTPS

Se usar HTTPS com domínio próprio, atualize:
```env
APP_URL=https://seudominio.com
```

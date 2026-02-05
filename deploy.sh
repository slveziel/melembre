#!/bin/bash

# melembre-deploy.sh - Deploy para Google Cloud Run

set -e

echo "=== Deploy do melembre para Cloud Run ==="
echo ""

# Verificar se gcloud está instalado
if ! command -v gcloud &> /dev/null; then
    echo "❌ gcloud não encontrado. Instale antes."
    exit 1
fi

# Verificar autenticação
if ! gcloud auth list --filter=status:ACTIVE &> /dev/null; then
    echo "❌ Não autenticado. Execute: gcloud auth login"
    exit 1
fi

# Configurar projeto
echo "📋 Projetos disponíveis:"
gcloud projects list --format="table(projectId,name)"
echo ""
read -p "Digite o PROJECT_ID: " PROJECT_ID

gcloud config set project $PROJECT_ID

# Configurar região
read -p "Região (default: us-central1): " REGION
REGION=${REGION:-us-central1}

# Criar Cloud SQL
echo ""
echo "🗄️ Criando Cloud SQL..."
gcloud sql instances create melembre-db \
    --database-version=MYSQL_8_0 \
    --tier=db-f1-micro \
    --region=$REGION \
    --no-assign-ip

# Criar banco de dados
echo "📦 Criando banco de dados..."
gcloud sql databases create melembre --instance=melembre-db

# Criar usuário
DB_PASSWORD=$(openssl rand -base64 16)
echo "👤 Criando usuário..."
gcloud sql users create melembre_user \
    --instance=melembre-db \
    --password=$DB_PASSWORD

echo ""
echo "✅ Cloud SQL criado!"
echo "   - Host: /cloudsql/$PROJECT_ID:$REGION:melembre-db"
echo "   - Database: melembre"
echo "   - Username: melembre_user"
echo "   - Password: $DB_PASSWORD"
echo ""

# Atualizar .env.production
echo "📝 Atualizando .env.production..."
sed -i "s|PROJECT_ID|$PROJECT_ID|g" .env.production
sed -i "s|REGION|$REGION|g" .env.production
sed -i "s|YOUR_DB_PASSWORD|$DB_PASSWORD|g" .env.production

# Gerar APP_KEY
APP_KEY=$(php artisan key:generate --show)
sed -i "s|base64:Wb3J4XNcQ8K2YrF8PzN5Q3M7V1H9D0E6T9A4R2W5U8I1O3P6|$APP_KEY|g" .env.production

echo "📦 Fazendo build da imagem..."
gcloud builds submit --tag gcr.io/$PROJECT_ID/melembre

echo ""
echo "🚀 Fazendo deploy para Cloud Run..."
gcloud run deploy melembre \
    --image gcr.io/$PROJECT_ID/melembre \
    --region $REGION \
    --platform managed \
    --allow-unauthenticated \
    --add-cloudsql-instances $PROJECT_ID:$REGION:melembre-db \
    --set-env-vars "APP_URL=https://melembre-xxxxxxxx-uc.a.run.app,DB_CONNECTION=mysql,DB_HOST=/cloudsql/$PROJECT_ID:$REGION:melembre-db,DB_DATABASE=melembre,DB_USERNAME=melembre_user,DB_PASSWORD=$DB_PASSWORD,SESSION_DRIVER=database,CACHE_DRIVER=database"

# Migrar banco
echo ""
echo "🗃️ Rodando migrações..."
gcloud run services describe melembre --region $REGION --format="value(status.url)" | xargs -I {} curl {} -X POST -F "key=$APP_KEY" "https://{}/migrate"

echo ""
echo "✅ Deploy completo!"
echo ""
echo "📝 Próximos passos:"
echo "   1. Acesse: https://melembre-xxxxxxxx-uc.a.run.app"
echo "   2. Faça login e crie sua primeira anotação"
echo "   3. Configure um domínio personalizado (opcional)"

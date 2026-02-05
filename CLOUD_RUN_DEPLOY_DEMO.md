# Demo-hub Cloud Run デプロイ手順（Demo 環境）

## 前提
- Project: `lmb-demohub`
- Region: `asia-northeast1`
- 変数は Cloud Run の環境変数で管理（`GEMINI_API_KEY`, `SUPABASE_URL`, `SUPABASE_ANON_KEY` など）

## 0) 共通設定
```bash
gcloud config set project lmb-demohub
gcloud config set run/region asia-northeast1
gcloud config set run/platform managed
```

## 1) demo-hub（静的）
```bash
cd demo-hub
gcloud run deploy demo-hub-demo \
  --source . \
  --allow-unauthenticated
```

## 2) AI_Vet_STT（Demo）
### Backend
```bash
cd AI_Vet_STT_demo/Backend
gcloud run deploy ai-vet-backend-demo \
  --source . \
  --allow-unauthenticated

# 必須変数を設定（値は実際のものに置換）
gcloud run services update ai-vet-backend-demo \
  --set-env-vars "GEMINI_API_KEY=YOUR_KEY,GOOGLE_GEMINI_API_KEY=YOUR_KEY,SUPABASE_URL=YOUR_URL,SUPABASE_SERVICE_ROLE_KEY=YOUR_KEY"
```

### Frontend
```bash
cd ../Frontend
gcloud run deploy ai-vet-frontend-demo \
  --source . \
  --allow-unauthenticated \
  --set-env-vars "NEXT_PUBLIC_API_URL=https://ai-vet-backend-demo-xxxxx-an.a.run.app"
```

## 3) MultilingualReportApp（Demo）
### Backend
```bash
cd MultilingualReportApp_demo
gcloud run deploy multilingual-report-backend-demo \
  --source . \
  --allow-unauthenticated \
  --set-env-vars "GEMINI_API_KEY=YOUR_KEY"
```

### Frontend
```bash
cd frontend
gcloud run deploy multilingual-report-frontend-demo \
  --source . \
  --allow-unauthenticated \
  --set-env-vars "NEXT_PUBLIC_API_URL=https://multilingual-report-backend-demo-xxxxx-an.a.run.app,NEXT_PUBLIC_SUPABASE_URL=https://YOUR_PROJECT.supabase.co,NEXT_PUBLIC_SUPABASE_ANON_KEY=YOUR_ANON_KEY"
```

## 4) Woodshop Inventory（Demo）
### Web（静的）
```bash
cd woodshop-inventory_demo/apps/web
gcloud run deploy woodshop-web-demo \
  --source . \
  --allow-unauthenticated
```

### API について
`woodshop-inventory_demo/apps/api` は Cloudflare Workers（D1/R2）前提です。  
Cloud Run へ移すには **D1/R2 を Supabase/Cloud Storage に置換**する移植が必要です。  
現状維持で良ければ、`apps/web/assets/js/api.js` の `API_BASE` を Workers の URL のまま使います。

## 5) demo-hub のリンク更新
`demo-hub/index.html` の `localhost` や旧URLを、上記で発行された Cloud Run URL に差し替えます。

## 補足
- Next.js の `NEXT_PUBLIC_*` 変数は **ビルド時に埋め込まれる**ため、変更時は再デプロイが必要です。
- Gemini キーは `GEMINI_API_KEY` を統一キーとし、AI_Vet_STT 用に `GOOGLE_GEMINI_API_KEY` も同値でセットします。

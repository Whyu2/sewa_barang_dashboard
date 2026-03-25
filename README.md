## ⚙️ Requirements

- PHP >= 8.x
- Node.js >= 18.x
- Composer
- Database (MySQL / PostgreSQL)

---
## 🧱 Tech Stack

### Backend

* Laravel

### Frontend

* Vue 3
* Inertia.js
* Vite

### UI & Styling

* PrimeVue
* PrimeIcons
* Tailwind CSS
* PrimeUIX Themes

### State & Data

* Pinia
* @tanstack/vue-query

### Forms & Validation

* vee-validate
* yup
* vue-yup-form
---

## ⚙️ Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Setup database di `.env`.

---

## ▶️ Development (Windows / Localhost)

```bash
php artisan serve --host=0.0.0.0 --port=8000
npm run dev
```

Akses:

* http://localhost:8000
* http://YOUR_IP:8000

---

## 🗄️ Database

### Migration

```bash
php artisan migrate
```

### Fresh + Seeder

```bash
php artisan migrate:fresh --seed
```


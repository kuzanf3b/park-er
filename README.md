# 🅿️ Park-er — Parking Management System

A web-based parking lot management system built with **Laravel 13**. Park-er handles vehicle entry/exit, automated fee calculation, parking area capacity enforcement, role-based user access, and financial reporting — all through a clean, role-aware web interface.

> The application UI and data are primarily in **Indonesian (Bahasa Indonesia)**.

---

## ✨ Features

- **Role-Based Access Control** — Three distinct roles (`admin`, `petugas`, `owner`) with route-level enforcement via custom middleware.
- **Parking Transaction Management** — Full entry/exit workflow with automatic area assignment, tariff lookup, and fee calculation.
- **"Kilat" (Quick) Mode** — A single-page AJAX interface for `petugas` to process vehicles by license plate scan: if the vehicle is currently parked → exit; otherwise → entry.
- **Tiered Fee System** — First 2 hours billed at the base rate; additional hours billed at a 1.5× penalty rate. A 5-minute grace period applies before billing starts.
- **Capacity Enforcement** — Each parking zone has a hard capacity limit, enforced with pessimistic database locking to prevent overbooking under concurrent requests.
- **Multi-vehicle-type Support** — Motor, Mobil (car), Truk (truck), Bus — each with dedicated zones and tariffs.
- **Revenue Reports** — Filterable by date range and broken down by vehicle type. Owners only see reports for their own vehicles.
- **Activity Audit Log** — Every login, logout, transaction, and data mutation is logged with a timestamp and user ID.
- **Owner Portal** — Vehicle owners can view their registered vehicles, currently parked vehicles, and parking history.

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.3+, Laravel 13 |
| Database | MySQL 8.0 (production), SQLite (development default) |
| Frontend | Blade templates, Tailwind CSS v4, Axios |
| Build tool | Vite 8, Laravel Vite Plugin |
| Testing | PHPUnit 12 |

---

## 📋 Requirements

- PHP >= 8.3
- Composer
- Node.js & npm
- MySQL 8.0 (for production) **or** SQLite (for local development)

---

## 🚀 Installation & Setup

### Quick setup (all-in-one)

```bash
composer run setup
```

This runs: `composer install` → copy `.env` → generate app key → migrate → `npm install` → `npm run build`.

### Manual setup

```bash
# 1. Install PHP dependencies
composer install

# 2. Configure environment
cp .env.example .env
php artisan key:generate
```

**For MySQL:** edit `.env` and set:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=parkir
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Then import the bundled SQL dump:
```bash
mysql -u root -p parkir < parkir.sql
```

**For SQLite (development):**
```bash
# The default .env.example already uses sqlite
php artisan migrate --force
```

```bash
# 3. Install JS dependencies and build assets
npm install
npm run build
```

---

## ▶️ Running the Application

### Development (all services at once)

```bash
composer run dev
```

This concurrently starts:
- `php artisan serve` — Laravel dev server
- `php artisan queue:listen` — Queue worker
- `php artisan pail` — Log viewer
- `npm run dev` — Vite HMR dev server

### Production

```bash
npm run build
php artisan serve
```

---

## 🧪 Running Tests

```bash
composer run test
# Equivalent to: php artisan config:clear && php artisan test
```

---

## 👤 Default Users

The SQL dump (`parkir.sql`) seeds three default accounts. All use the password **`password`**.

| Username | Role | Full Name |
|---|---|---|
| `admin` | admin | Administrator |
| `petugas1` | petugas | Petugas Parkir 1 |
| `owner` | owner | Owner Parkir |

---

## 🏗️ Project Structure

```
app/
├── Http/
│   ├── Controllers/     # AuthController, DashboardController, TransaksiController, ...
│   └── Middleware/      # RoleMiddleware (role:admin,petugas,owner)
├── Models/              # AreaParkir, Kendaraan, Tarif, Transaksi, LogAktivitas, User
├── Services/
│   ├── ParkingService.php   # Core business logic: entry, exit, fee calculation
│   └── LogService.php       # Activity audit logging
database/
├── migrations/          # Laravel migrations
└── seeders/
parkir.sql               # Full MySQL schema + seed data dump
resources/views/         # Blade templates (auth, dashboard, transaksi, laporan, ...)
routes/
└── web.php              # All application routes with role middleware
```

---

## 💰 Fee Calculation

| Hours parked | Rate |
|---|---|
| First 2 hours | Base rate (per hour) |
| Beyond 2 hours | 1.5× base rate (per hour) |
| Grace period | First 5 minutes are free |

Default base rates:

| Vehicle Type | Rate per Hour |
|---|---|
| Motor (motorcycle) | Rp 2,000 |
| Mobil (car) | Rp 5,000 |
| Truk (truck) | Rp 10,000 |
| Bus | Rp 15,000 |

Rates can be updated by an `admin` via the **Tarif** management page.

---

## 🔑 Role Permissions

| Feature | Admin | Petugas | Owner |
|---|---|---|---|
| Dashboard | ✅ (stats + charts) | ✅ (active transactions) | ✅ (own vehicles) |
| Transactions (page-based) | ✅ | ❌ | ❌ |
| Transactions (kilat/AJAX) | ❌ | ✅ | ❌ |
| Transaction receipt | ✅ | ✅ | ❌ |
| Vehicle management (CRUD) | ✅ | ✅ | Read-only |
| Parking area management | ✅ | ❌ | ❌ |
| Tariff management | ✅ | ❌ | ❌ |
| User management | ✅ | ❌ | ❌ |
| Revenue reports | ✅ (all) | ❌ | ✅ (own vehicles) |
| Activity log | ✅ | ❌ | ❌ |

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

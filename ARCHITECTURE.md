# Laravel Architecture — salon_mvp_api

## Goals

- Scalable, maintainable, role-based structure (Admin / Owner / Customer)
- Clear separation: Controller → Service → Repository → Model
- 100% API contract compatibility with Vue frontend

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/V1/
│   │   ├── Admin/
│   │   ├── Owner/
│   │   └── Customer/
│   ├── Requests/Api/V1/
│   │   ├── Admin/
│   │   ├── Owner/
│   │   └── Customer/
│   └── Resources/Api/V1/
│       ├── Admin/
│       ├── Owner/
│       └── Customer/
├── Services/
│   ├── Admin/
│   ├── Owner/
│   ├── Customer/
│   └── Shared/          # cross-cutting concerns (traits)
├── Repositories/
│   ├── Interfaces/
│   │   ├── Admin/       # (future)
│   │   ├── Owner/
│   │   └── Customer/
│   └── Eloquent/
│       ├── Owner/
│       └── Customer/
├── Contracts/Services/  # service interfaces for DI
│   ├── Admin/
│   ├── Owner/
│   └── Customer/
├── Support/             # mappers, visibility helpers
├── Enums/
└── Models/

routes/
├── api.php
└── api/v1/
    ├── admin.php
    ├── owner.php
    └── customer.php
```

---

## Architecture Flow

```
Controller (thin)
   ↓
Service (business logic)
   ↓
Repository Interface
   ↓
Eloquent Repository
   ↓
Model
```

---

## Role Classification

| Role | Controllers | Services | Repositories |
|------|-------------|----------|--------------|
| **Admin** | User/Salon/Booking management, packages, subscriptions, audit | `Admin*Service` | Direct model queries (MVP) |
| **Owner** | Salon CRUD, staff, services, style-options, uploads, subscription | Salon, Staff, ServiceCatalog, StyleOption, Owner* | Salon, Service, Staff |
| **Customer** | Auth, bookings, reviews, payments, favorites, search | Auth, Booking, Payment, Review, Notification | User, Booking, Payment, Review |

---

## DI Bindings

All repository and core service bindings are registered in `app/Providers/RepositoryServiceProvider.php`.

---

## Routes

- **V1 canonical:** `/api/v1/*` — split across `customer.php`, `owner.php`, `admin.php`
- **Legacy Vue paths:** `/api/customer/*`, `/api/owner/*`, `/api/admin/*` — alias files unchanged
- **Auth:** `/api/auth/*`, `/api/profile/*` — in `customer.php`

See `REFACTOR_REPORT.md` for full migration details.

---

## Naming Convention

| Layer | Example |
|-------|---------|
| Controller | `App\Http\Controllers\Api\V1\Customer\BookingController` |
| Service | `App\Services\Customer\BookingService` |
| Repository | `App\Repositories\Eloquent\Customer\BookingRepository` |
| Interface | `App\Repositories\Interfaces\Customer\BookingRepositoryInterface` |
| Request | `App\Http\Requests\Api\V1\Customer\StoreBookingRequest` |
| Resource | `App\Http\Resources\Api\V1\Customer\BookingResource` |

---

## Anti-Patterns (Avoid)

- Fat controllers
- Business logic in models
- Raw queries in controllers
- Hardcoded role checks outside middleware/policies

---

> Chi tiết refactor: xem `REFACTOR_REPORT.md`

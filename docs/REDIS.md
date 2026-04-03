# Redis — Cache & Queue

## Phase 1 (đã xong)

Chuyển Laravel cache từ `file` sang `redis` cho các cache đã có sẵn trong code:

- `system:settings` — `App\Support\SystemSettings`
- `salons:list:*` + `salons:list:version` — `App\Services\Owner\SalonService`
- `salons:available_today:*` — `App\Services\Owner\SalonTodayAvailabilityService`

## Phase 2 (đã xong)

Cache **available slots** khi khách chọn giờ đặt lịch:

- Helper: `App\Support\AvailableSlotsCache`
- Key: `available-slots:{salonId}:{date}:v{version}:sv{salonVersion}:{hash}` (TTL **45 giây**)
- Version key (theo ngày): `available-slots:version:{salonId}:{date}`
- Salon-wide version: `available-slots:salon-version:{salonId}` — tăng khi giờ mở cửa, interval, roster dịch vụ/nhân viên đổi
- Hash inputs: `service_ids` + `style_options` (sau `BookingMapper::normalizeAvailableSlotFilters`)

**Invalidation** (tăng version → cache miss ngay):

| Hành động | Ghi chú |
|-----------|---------|
| `createBooking` | Luôn invalidate ngày booking |
| `cancelBooking` | Giải phóng slot |
| `rescheduleBooking` | Invalidate ngày cũ + ngày mới |
| `completeBooking` | Slot không còn active |
| `updateBookingStatus` | Chỉ khi chuyển qua/lại trạng thái chiếm slot (`pending`/`confirmed`) |
| `deleteBooking` | Chỉ khi booking đang `pending`/`confirmed` |
| `confirmBooking` | **Không** invalidate (`pending` → `confirmed` vẫn chiếm slot) |

**Invalidation bổ sung (schedule & cấu hình salon):**

| Nguồn | Hành vi |
|-------|---------|
| Duyệt / tạo / sửa / xóa ca **approved** (`OwnerWorkScheduleService`) | `forgetSalonDate` theo `work_date` |
| Staff gửi ca + auto-approve | `forgetSalonDates` các ngày gửi |
| Owner thay lịch nhân viên (`StaffService::updateSchedule`) | Ngày cũ + ngày mới |
| Gán dịch vụ cho NV, bật/tắt NV, xóa NV | `forgetSalonWide` |
| Sửa/xóa dịch vụ (`ServiceCatalogService`) | `forgetSalonWide` |
| Đổi `open_time` / `close_time` salon | `forgetSalonWide` |
| Đổi `booking_interval_minutes` | `forgetSalonWide` |
| Đổi trạng thái salon (đóng cửa / khóa / duyệt) | `forgetSalonWide` |

Khi invalidate available-slots, đồng thời bump `salons:available_today:version:{date}` để filter “Còn chỗ hôm nay” cập nhật sớm hơn TTL 5 phút.

## Phase 3 (đã xong)

Chuyển **queue** sang Redis cho email và gửi thông báo shop:

```env
QUEUE_CONNECTION=redis
REDIS_QUEUE=default
```

| Thành phần | Cách hoạt động |
|------------|----------------|
| Email booking / reset mật khẩu / subscription | `App\Support\QueuedMailer` → `Mail::queue()` |
| Nhắc hết hạn gói | `SendSubscriptionExpiryReminderJob` (gửi + ghi log sau khi worker xử lý) |
| Broadcast thông báo owner → khách | `DispatchNotificationBroadcastJob` |

Queue Redis dùng `REDIS_DB=0`; cache vẫn dùng `REDIS_CACHE_DB=1`. Connection redis queue bật `after_commit=true`.

## Phase 4 (đã xong)

Distributed lock khi tạo/đổi lịch để tránh double-booking:

- Helper: `App\Support\BookingSlotLock`
- Keys (per ngày):
  - `booking-slot-lock:staff:{salonId}:{staffId}:{date}`
  - `booking-slot-lock:seat:{salonId}:{seatId}:{date}`
- Wait **5 giây**, TTL lock **15 giây**
- Bọc `createBooking` và `rescheduleBooking`: re-check conflict + ghi DB trong lock
- `createBooking` giờ kiểm tra cả **staff lẫn ghế** (trước đây chỉ staff)
- Timeout lock → `409 SLOT_LOCK_BUSY`

**Worker (bắt buộc khi `QUEUE_CONNECTION=redis`):**

Xem chi tiết: [QUEUE_WORKER.md](./QUEUE_WORKER.md)

```bash
# Dev Windows — mở queue + scheduler
scripts/start-dev-workers.bat

# Hoặc thủ công
php artisan queue:work redis --tries=3
```

## Yêu cầu

1. **Redis server** — Laragon: bật Redis (`Menu → Redis → Start`) hoặc chạy:
   ```
   C:\laragon\bin\redis\redis-x64-5.0.14.1\redis-server.exe
   ```
2. **PHP client** — `predis/predis` (đã cài qua Composer). Dùng `REDIS_CLIENT=predis` vì Laragon PHP thường chưa bật extension `phpredis`.

## Cấu hình `.env`

```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1
REDIS_QUEUE=default
```

Sau khi đổi `.env`:

```bash
php artisan config:clear
php artisan cache:clear
```

## Kiểm tra

```bash
php artisan cache:verify-redis
php artisan queue:verify-redis
```

## Ghi chú

- **Session** vẫn dùng `file`.
- PHPUnit dùng `CACHE_DRIVER=array` và `QUEUE_CONNECTION=sync` trong `phpunit.xml`.
- Cache keys nằm trên Redis DB `REDIS_CACHE_DB` (mặc định `1`); queue jobs trên `REDIS_DB` (mặc định `0`); slot locks dùng cache store (DB `1`).

## Rate limit booking

Giới hạn riêng cho API đặt lịch (`config/booking.php`):

| Limiter | Route | Mặc định |
|---------|-------|----------|
| `booking-slots` | `GET …/available-slots` | 60/phút (IP hoặc user) |
| `booking-create` | `POST …/bookings` | 10/phút (user) |
| `booking-mutate` | `PATCH …/cancel`, `…/reschedule` | 20/phút (user) |

`.env`:

```env
BOOKING_SLOTS_RATE_LIMIT=60
BOOKING_CREATE_RATE_LIMIT=10
BOOKING_MUTATE_RATE_LIMIT=20
```

Vượt limit → `429` + `RATE_LIMIT_EXCEEDED`.

## Hoàn tất Redis roadmap (Phase 1–4)

Cache, queue, available-slots invalidation và booking slot lock đã được triển khai. Các hướng mở rộng sau (nếu cần): session Redis multi-server, soft-hold slot tạm thời trước khi thanh toán.

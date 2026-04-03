# Queue worker & scheduler — vận hành

Email booking, thông báo shop và nhắc hết hạn gói dùng **Redis queue** (`QUEUE_CONNECTION=redis`). Job chỉ được xử lý khi **queue worker** đang chạy.

## Yêu cầu

- Redis server đang chạy (Laragon: Menu → Redis → Start)
- `.env`: `QUEUE_CONNECTION=redis`
- `php artisan queue:verify-redis` → OK

---

## Windows / Laragon (dev)

### Cách nhanh — một lần double-click

Chạy file:

```
salon_mvp_api/scripts/start-dev-workers.bat
```

Mở **2 cửa sổ CMD**:

1. **Queue worker** — xử lý email & notification jobs  
2. **Scheduler** — mỗi 60s chạy `schedule:run` (thông báo hẹn giờ, nhắc subscription)

Giữ cả hai cửa sổ mở khi dev/test.

### Chạy riêng lẻ

| Script | Mục đích |
|--------|----------|
| `scripts/queue-worker.bat` | Chỉ queue worker (tự restart sau crash / mỗi giờ) |
| `scripts/schedule-run.bat` | Chỉ Laravel scheduler loop |

### Laragon — chạy cùng Windows

1. Tạo shortcut tới `scripts/start-dev-workers.bat`
2. Win+R → `shell:startup` → copy shortcut (tuỳ chọn, tự chạy khi đăng nhập)
3. Hoặc **Task Scheduler**: trigger At log on → action `start-dev-workers.bat`

---

## Linux production (Supervisor)

1. Sửa path trong `deploy/supervisor/salon-mvp-queue.conf` (`/var/www/salon_mvp_api`, user `www-data`)
2. Cài config:

```bash
sudo cp deploy/supervisor/salon-mvp-queue.conf /etc/supervisor/conf.d/
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start salon-mvp-queue:*
sudo supervisorctl status
```

3. **Cron** cho scheduler (bắt buộc — mỗi phút):

```cron
* * * * * cd /var/www/salon_mvp_api && php artisan schedule:run >> /dev/null 2>&1
```

Log worker: `storage/logs/queue-worker.log`

---

## Lệnh hữu ích

```bash
# Kiểm tra queue Redis
php artisan queue:verify-redis

# Xem failed jobs
php artisan queue:failed

# Retry tất cả failed
php artisan queue:retry all

# Chạy worker thủ công (foreground)
php artisan queue:work redis --tries=3 --timeout=90
```

---

## Scheduled commands (Kernel)

| Command | Lịch |
|---------|------|
| `notifications:dispatch-scheduled` | Mỗi phút |
| `subscriptions:send-expiry-reminders` | 08:00 hàng ngày (Asia/Ho_Chi_Minh) |

Cả hai phụ thuộc **scheduler** (cron hoặc `schedule-run.bat`).

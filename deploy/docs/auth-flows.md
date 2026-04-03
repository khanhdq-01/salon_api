# Auth Flows — Registration, Email Verification & Password Reset

Tài liệu mô tả luồng xác thực khách hàng (Customer) cho Salon SaaS MVP.

## Tổng quan kiến trúc

```
Controller (mỏng)
  → FormRequest (validate + trim)
  → Service (nghiệp vụ)
    → Repository (DB)
    → Job (Redis Queue) → Mail
  → API Resource (response)
```

- **Login hiện tại không thay đổi** cho owner/admin/staff và customer đã xác thực email.
- Customer **chưa xác thực email** bị chặn đăng nhập (`EMAIL_NOT_VERIFIED`, HTTP 403).

---

## 1. Luồng Đăng ký

### Frontend
- Trang: `/register` (`RegisterPage.vue`)
- Sau đăng ký thành công → `/verify-email/pending?email=...`

### API
`POST /api/auth/register`  
Rate limit: **5 lần/phút** theo IP (`auth-register`)

**Request body:**
```json
{
  "name": "Nguyễn Văn A",
  "email": "user@example.com",
  "password": "Secret@123",
  "password_confirmation": "Secret@123"
}
```

**Validation (`RegisterRequest`):**
- Trim toàn bộ input
- Email đúng định dạng, unique
- Password ≥ 8 ký tự, có chữ hoa/thường/số/ký tự đặc biệt
- `password_confirmation` khớp

**Xử lý (`AuthService::register`):**
1. Tạo `User` role **Customer**, `status = pending`
2. `email_verified_at = null`
3. **Không** cấp JWT
4. Gọi `EmailVerificationService::registerAndSendVerification`
5. Audit: `register` (success)

**Response (201):**
```json
{
  "data": {
    "user": { "id": "...", "email": "...", "status": "pending" },
    "verification_required": true,
    "message": "Đăng ký thành công. Vui lòng kiểm tra email để xác thực tài khoản."
  }
}
```

---

## 2. Luồng Xác thực Email

### Sinh token
- Plain token 64 ký tự (`Str::random(64)`) gửi trong URL email
- Hash bcrypt lưu bảng `email_verification_tokens`
- Hết hạn: **60 phút**
- Token cũ bị vô hiệu khi sinh token mới

### Gửi email
- Job: `SendVerifyEmailJob` → Redis Queue
- Retry: **3 lần**, `failed()` ghi log
- Template: `resources/views/emails/auth-transactional.blade.php`

### API xác thực
`GET /api/auth/email/verify?email=&token=`

**Thành công:**
- `email_verified_at` được set
- Token consumed (single-use)
- Audit: `verify_email` (success)
- Kích hoạt tài khoản → cho phép login

**Thất bại:**
| Trường hợp | Code | Audit |
|------------|------|-------|
| Token không hợp lệ | `VERIFICATION_INVALID` (422) | `verify_email` failed |
| Token hết hạn | `VERIFICATION_EXPIRED` (410) | `verify_email` failed (reason: expired) |

### Frontend
- Link email → `/verify-email?email=&token=`
- Trạng thái: loading / success / expired / failed
- Hết hạn: nút **Gửi lại email xác thực**

### Gửi lại email
`POST /api/auth/email/resend`  
Rate limit: **3 lần/10 phút** theo IP + email (`auth-resend-verification`)

**Điều kiện:** email tồn tại, là Customer, chưa xác thực  
**Anti-enumeration:** luôn trả success message chung (không lộ email có/không)

**Xử lý:**
1. Vô hiệu token cũ
2. Sinh token mới
3. Queue `SendVerifyEmailJob`
4. Audit: `resend_verification_email`

---

## 3. Luồng Quên mật khẩu

### Frontend
- Trang: `/forgot-password` (`ForgotPasswordPage.vue`)

### API
`POST /api/auth/forgot-password`  
Rate limit: **3 lần/10 phút** theo IP + email (`auth-forgot-password`)

**Request:**
```json
{ "email": "user@example.com" }
```

**Xử lý (`AuthService::forgotPassword`):**
- Email **không tồn tại** hoặc không phải Customer → **không làm gì**, không lộ thông tin
- Email hợp lệ → `Password::createToken()` (Laravel, hash trong `password_reset_tokens`)
- Queue `SendResetPasswordJob`
- Audit: `forgot_password`

**Response (luôn giống nhau):**
```json
{
  "message": "Nếu Email tồn tại trong hệ thống, chúng tôi đã gửi Email hướng dẫn."
}
```

---

## 4. Luồng Đặt lại mật khẩu

### Frontend
- Link email → `/reset-password?email=&token=`
- Trang: `ResetPasswordPage.vue`

### API
`POST /api/auth/reset-password`

**Request:**
```json
{
  "email": "user@example.com",
  "token": "...",
  "password": "NewPass@123",
  "password_confirmation": "NewPass@123"
}
```

**Xử lý:**
- Dùng `Password::reset()` của Laravel (token hash, TTL 60 phút, single-use)
- Cập nhật password qua `UserRepository::updatePassword`
- Audit: `reset_password` (success/failed)

---

## 5. Luồng Queue (Redis)

| Job | Trigger | Queue |
|-----|---------|-------|
| `SendVerifyEmailJob` | Register / Resend verify | Redis (default) |
| `SendResetPasswordJob` | Forgot password | Redis (default) |

**Cấu hình:**
- `$tries = 3`
- `failed(Throwable)` → `Log::error`
- Worker: `salon_mvp_api/scripts/queue-worker.bat`

**Yêu cầu `.env`:**
```
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
MAIL_MAILER=smtp
```

---

## 6. Database

### Migration: `email_verified_at` trên `users`
- Cột nullable timestamp
- User cũ được backfill `now()` để không bị khóa login

### Migration: `email_verification_tokens`
| Cột | Mô tả |
|-----|-------|
| `user_id` | FK users |
| `token_hash` | bcrypt hash |
| `expires_at` | TTL 60 phút |
| `consumed_at` | Single-use marker |
| Index | `user_id`, `expires_at` |

### Bảng `password_reset_tokens` (Laravel mặc định)
- Token lưu dạng hash
- TTL: 60 phút (`config/auth.php`)

---

## 7. API Reference

| Method | Endpoint | Auth | Rate limit |
|--------|----------|------|------------|
| POST | `/api/auth/register` | Public | 5/min IP |
| GET | `/api/auth/email/verify` | Public | — |
| POST | `/api/auth/email/resend` | Public | 3/10min IP+email |
| POST | `/api/auth/forgot-password` | Public | 3/10min IP+email |
| POST | `/api/auth/reset-password` | Public | — |
| POST | `/api/auth/login` | Public | (không đổi) |

---

## 8. Security

| Biện pháp | Chi tiết |
|-----------|----------|
| Password hash | `bcrypt` via Laravel |
| Token hash | Verification: bcrypt; Reset: Laravel Password broker |
| Single-use token | `consumed_at` / Laravel reset consume |
| Token expiry | 60 phút |
| Anti-enumeration | Forgot + Resend luôn trả message chung |
| Rate limiting | Register 5/min; Forgot & Resend 3/10min |
| Email verification gate | Customer login blocked until verified |
| Queue-only email | Không gửi đồng bộ trong request |

---

## 9. Audit Log

| Action | Khi nào |
|--------|---------|
| `register` | Đăng ký thành công |
| `send_verification_email` | Gửi email xác thực lần đầu |
| `resend_verification_email` | Gửi lại email xác thực |
| `verify_email` | Xác thực success/failed/expired |
| `forgot_password` | Yêu cầu quên mật khẩu |
| `reset_password` | Đặt lại mật khẩu success/failed |

Presenter labels: `AuditLogPresenter`  
HTTP classifier: `AuditLogClassifier` (route `auth/*`)

---

## 10. Frontend Pages

| Route | Component |
|-------|-----------|
| `/register` | `RegisterPage.vue` |
| `/verify-email/pending` | `VerifyEmailPendingPage.vue` |
| `/verify-email` | `VerifyEmailPage.vue` |
| `/forgot-password` | `ForgotPasswordPage.vue` |
| `/reset-password` | `ResetPasswordPage.vue` |

UI: premium split layout (glassmorphism), loading states, toast/error messages, responsive.

---

## 11. Chạy Production

1. `php artisan migrate`
2. Cấu hình Redis + SMTP trong `.env`
3. Chạy queue worker (supervisor hoặc `queue-worker.bat`)
4. Cấu hình `FRONTEND_URL` cho link trong email (`AuthEmailContentBuilder`)

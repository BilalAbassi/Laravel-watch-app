# TIMEYRAH Watches — Pakistani Payment System Blueprint & AI Memory

> **Purpose:** This document defines the complete requirements, database schema, checkout flow, admin verification workflow, and gateway architecture for building the **Pakistani Payment System** in TIMEYRAH Watches. Any AI model working on payments, checkout, or order management MUST follow this specification.

---

## 1. Core Hosting & Environment Constraints (CRITICAL)
- **Live Hosting:** InfinityFree shared hosting (`sql312.byetcluster.com`, database `if0_43273400_watches`).
- **No SSH / No `storage:link`:** All uploaded files (such as customer payment screenshots/receipts) **MUST** be stored directly inside `public/uploads/payments/` using `move(public_path('uploads/payments'), $filename)` — **never** rely on `storage/app/public` symlinks.
- **No Heavy Background Daemons:** All payment callbacks, webhook verifications, and status updates must work synchronously over standard HTTP requests (`SESSION_DRIVER=file`, `CACHE_STORE=file`).
- **Target Audience:** Pakistani watch buyers (Karachi, Lahore, Islamabad, Rawalpindi, Faisalabad, Multan, Peshawar, Quetta, etc.). Keep all UI labels simple, clear, and trustworthy.

---

## 2. Pakistani Market Payment Methods (`app/Enums/PaymentMethod.php`)

Expand `App\Enums\PaymentMethod` to support the 5 core payment methods used in Pakistan:

| Enum Case | Value | Customer Label | Checkout Form Value | Description |
| :--- | :--- | :--- | :--- | :--- |
| `Cod` | `'COD'` | `Cash on Delivery (COD)` | `'cod'` | Pay in cash to the courier upon doorstep delivery across Pakistan. |
| `Bank` | `'Bank'` | `Bank Transfer / Raast (IBFT)` | `'bank'` / `'wire'` | Direct transfer to official Meezan / HBL bank account or Raast ID. |
| `JazzCash` | `'JazzCash'` | `JazzCash Mobile Wallet` | `'jazzcash'` | Transfer to official JazzCash wallet/till number (`03XX-XXXXXXX`). |
| `EasyPaisa` | `'EasyPaisa'` | `Easypaisa Mobile Wallet` | `'easypaisa'` | Transfer to official Easypaisa wallet account (`03XX-XXXXXXX`). |
| `Card` | `'Card'` | `Debit / Credit Card (Visa / Mastercard / PayPak)` | `'card'` | Online card payment (Safepay / PayFast hosted checkout or card flow). |

---

## 3. Payment Status Enum (`app/Enums/PaymentStatus.php`)

Create a new backed enum `App\Enums\PaymentStatus: string`:
- `Unpaid = 'Unpaid'` — Default for `COD` orders (until delivered) or unpaid gateway orders.
- `PendingVerification = 'Pending Verification'` — Default when a customer submits a `Bank`, `JazzCash`, or `EasyPaisa` order with a Transaction ID (`TID`) or payment screenshot.
- `Paid = 'Paid'` — Verified by Admin in CMS or confirmed via automated payment gateway callback.
- `Failed = 'Failed'` — Rejected screenshot/TID or failed card transaction.
- `Refunded = 'Refunded'` — Order refunded to customer.

---

## 4. Database Schema Additions (`orders` Table)

When implementing the payment system, create a migration (and update `database/timeyrah_schema.sql` with phpMyAdmin `ALTER TABLE` statements) adding these columns to `orders`:

```sql
ALTER TABLE `orders`
  ADD COLUMN `payment_status` VARCHAR(30) NOT NULL DEFAULT 'Unpaid' AFTER `payment_method`,
  ADD COLUMN `transaction_id` VARCHAR(100) NULL DEFAULT NULL AFTER `payment_status`,
  ADD COLUMN `payment_receipt` VARCHAR(255) NULL DEFAULT NULL AFTER `transaction_id`,
  ADD COLUMN `advance_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `total_amount`,
  ADD COLUMN `paid_at` TIMESTAMP NULL DEFAULT NULL AFTER `order_status`;
```

### Column Responsibilities:
1. `payment_status`: Tracks payment lifecycle independently of `order_status` (`Pending`, `Processing`, `Delivered`, `Cancelled`).
2. `transaction_id`: Stores the customer's 11–12 digit JazzCash/Easypaisa TID, Bank Reference Number, or Gateway Reference ID.
3. `payment_receipt`: Relative path (e.g., `uploads/payments/receipt_TY-ORD-2026-XXXX.jpg`) of the customer's uploaded transfer screenshot.
4. `advance_amount`: Optional advance delivery deposit (if configured for high-value COD watches) or full prepaid amount.
5. `paid_at`: Timestamp set when admin marks the payment as `Paid` or gateway webhook succeeds.

---

## 5. Two-Stage Implementation Roadmap

### Stage 1: Manual Wallet, Raast/IBFT & Receipt Verification Flow (100% Shared-Hosting Ready)

1. **Checkout Page (`resources/views/checkout.blade.php`):**
   - Display interactive payment cards for:
     1. **Cash on Delivery (COD)** — Free insured delivery across Pakistan.
     2. **Bank Transfer / Raast** — Shows official Bank Name, Account Title, Account Number, IBAN, and Raast ID with one-click **Copy** buttons.
     3. **JazzCash** — Shows official JazzCash Account Title & Mobile Number (`03XX-XXXXXXX`) with a **Copy** button.
     4. **Easypaisa** — Shows official Easypaisa Account Title & Mobile Number (`03XX-XXXXXXX`) with a **Copy** button.
     5. **Debit / Credit Card** — Visa, Mastercard, PayPak.
   - **Dynamic Proof-of-Payment Fields (Shown when `Bank`, `JazzCash`, or `EasyPaisa` is selected):**
     - **Transaction ID / Reference Number (`transaction_id`):** Required text input (e.g., `"Enter your 11-digit JazzCash/Easypaisa TID or Bank Reference"`).
     - **Upload Payment Screenshot (`payment_receipt`):** Optional/recommended image upload (`jpg, jpeg, png, webp`, max `4MB`), previewed with Alpine.js before submission.
   - Ensure `<form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">` includes `enctype="multipart/form-data"`.

2. **Backend Validation & Order Creation (`CheckoutRequest.php` & `PlaceOrder.php`):**
   - In `CheckoutRequest`:
     - Validate `payment_method` in `['cod', 'bank', 'wire', 'jazzcash', 'easypaisa', 'card']`.
     - Require `transaction_id` (or `payment_receipt`) when `payment_method` is `bank`, `wire`, `jazzcash`, or `easypaisa`.
     - Validate `payment_receipt` as `nullable|image|mimes:jpg,jpeg,png,webp|max:4096`.
   - In `PlaceOrder`:
     - Save uploaded `payment_receipt` directly to `public/uploads/payments/`.
     - Set `payment_status` to:
       - `PaymentStatus::PendingVerification` for `Bank`, `JazzCash`, `EasyPaisa`
       - `PaymentStatus::Unpaid` for `COD`
       - `PaymentStatus::Paid` (or `PendingVerification` until gateway callback) for `Card`

3. **Order Confirmation & WhatsApp Instant Verification (`resources/views/order-confirmation.blade.php`):**
   - Display `payment_method`, `payment_status` badge, `transaction_id`, and uploaded receipt thumbnail.
   - Include a **"Verify & Confirm Order on WhatsApp"** button that opens `https://wa.me/923001234567?text=...` pre-filled with:
     - Order Number (`TY-ORD-2026-...`)
     - Customer Name & City
     - Watch Title(s) & Total Amount
     - Payment Method & Transaction ID (`TID`)

4. **Admin Orders & Payment Verification CMS (`/admin/orders`):**
   - Create `App\Http\Controllers\Admin\OrderController` with routes under `/admin/orders`:
     - List all orders with filters by `order_status`, `payment_status`, and `payment_method`.
     - View customer delivery details, ordered watches, `transaction_id`, and a lightbox preview of `payment_receipt`.
     - One-click actions to update `payment_status` (`Mark as Paid`, `Reject Payment`) and `order_status` (`Processing`, `Delivered`, `Cancelled` — restoring stock if cancelled).
   - Store configurable bank/wallet account details (Bank Name, IBAN, JazzCash number, Easypaisa number, WhatsApp number) in `page_sections` or config so the admin can update account numbers from the CMS without editing code.

---

### Stage 2: Automated Pakistani Payment Gateway Integration (Safepay / PayFast)

When connecting live automated card/wallet processing:
1. **Recommended Gateway:** **Safepay** (`getsafepay.com`) or **PayFast Pakistan** (`gopayfast.com`) — both support **Visa, Mastercard, PayPak, Raast, JazzCash, and Easypaisa** via a secure hosted checkout redirect.
2. **Redirect & Callback Flow:**
   - Customer selects `Card` (or online gateway) at checkout -> `PlaceOrder` creates the `Order` with `payment_status = 'Unpaid'`.
   - Controller generates a Safepay/PayFast checkout session and redirects the user to the hosted payment page.
   - Upon completion, the gateway redirects back to `GET/POST /checkout/payment/callback/{order:order_number}`.
   - Controller verifies the HMAC signature using `config('services.safepay.secret')`, updates `payment_status = 'Paid'`, stores the gateway reference in `transaction_id`, sets `paid_at = now()`, and redirects to `route('order.confirmation', $order->order_number)`.

---

## 6. Testing Checklist for Any AI Implementing This Plan
- Feature tests in `tests/Feature/CheckoutOrderTest.php` covering:
  - Placing a `COD` order sets `payment_status = Unpaid`.
  - Placing a `JazzCash`, `EasyPaisa`, or `Bank` order with `transaction_id` and `UploadedFile::fake()->image('receipt.jpg')` saves the file to `public/uploads/payments/` and sets `payment_status = Pending Verification`.
  - Validation error when `JazzCash` / `EasyPaisa` / `Bank` is selected without a `transaction_id` or receipt.
- Feature tests in `tests/Feature/Admin/OrderManagementTest.php` covering:
  - Admin viewing orders, verifying payment receipts, marking `payment_status = Paid`, and updating `order_status`.

# M-Pesa Module for Ultimate POS

Adds Safaricom Daraja M-Pesa integration to Ultimate POS:

- **A "Pay with M-Pesa" button on the POS (sell) screen** — appears
  automatically next to Cash and Card the moment M-Pesa is enabled. Works the
  same way: one click, customer enters their PIN on their phone, sale
  finalizes automatically once payment is confirmed. No template editing
  required (see section 3).
- **Admin settings page** where the business owner enters their own Daraja API
  credentials (Consumer Key/Secret, Shortcode, Passkey) — no hardcoded keys.
- **STK Push** ("Lipa Na M-Pesa Online") — sends a payment prompt to the
  customer's phone and confirms automatically when they enter their PIN.
- **C2B (Paybill) confirmations** — logs payments customers make directly to
  your Paybill, even if they didn't go through the POS.
- **In-app (bell icon) notifications** the moment a payment is confirmed —
  to the cashier and the business owner (see section 5).
- A **transactions log** (Settings > M-Pesa > Transactions) showing all M-Pesa
  payments with status, receipt numbers, **and the linked invoice number**
  (clickable straight to the sale), plus a search box to look up a specific
  invoice, receipt, or phone number.

---

### Updating an existing install to get the Invoice # column

If you already have this module installed, you need to run the new
migration once after replacing the module files:

```bash
php artisan module:migrate Mpesa --force
php artisan view:clear
```

(or use the **Update** button under **Superadmin > Modules** — it checks
`module_version` in `Config/config.php` and runs pending migrations
automatically.) This adds an `invoice_no` column to `mpesa_transactions` and
starts populating it automatically for new payments going forward — it
won't backfill invoice numbers for M-Pesa payments made *before* this
update, since that link wasn't being recorded yet at the time.

---

## 1. Installation

This module now mirrors WooCommerce's structure: a migration inserts an
`mpesa_version` row into the `system` table (matching how
`woocommerce_version` works), permissions are created via a dedicated
migration (`Spatie\Permission\Models\Permission::create()`), and the sidebar
menu is added with `$menu->dropdown()` exactly like WooCommerce's
`modifyAdminMenu()`.

### Recommended: Upload & Migrate via Manage Modules

1. Upload `Mpesa-module.zip` via **Superadmin > Manage Modules**.
2. If your Manage Modules screen auto-runs `module:migrate` on upload/enable
   (as it does for other modules), that's it — the M-Pesa menu will appear
   in the sidebar immediately, because `modifyAdminMenu()` only checks that
   the `mpesa_settings` table exists (created by the migration).
3. If the **Install** button on Manage Modules still does nothing for this
   module (the same JS issue as before), it doesn't matter for this module
   any more — once the zip is uploaded and the module is **enabled**, the
   migration creates the tables and the menu appears on its own. You do not
   need to click "Install" at all.
4. If after upload+enable the M-Pesa menu still isn't visible, the migration
   hasn't run. Ask your host to run, from the project root:

   ```bash
   php artisan module:migrate Mpesa --force
   php artisan optimize:clear
   ```

   This is the same single command WooCommerce's Install button ultimately
   triggers — running it manually has the identical effect.

5. Go to **M-Pesa > Settings** in the sidebar to enter your Daraja
   credentials, and assign the `mpesa.settings`, `mpesa.view_transactions`,
   and `mpesa.collect_payment` permissions to the relevant roles under
   **Roles & Permissions**.

### Manual (terminal/SSH access)

1. Copy this `Mpesa` folder into your Ultimate POS installation's `Modules/`
   directory, so you end up with:

   ```
   Modules/Mpesa/...
   ```

2. From your project root, enable and migrate the module:

   ```bash
   php artisan module:enable Mpesa
   php artisan module:migrate Mpesa --force
   ```

3. Clear caches:

   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

4. Log in as a Superadmin / Admin. You should now see an **M-Pesa** menu item
   in the sidebar with **Settings** and **Transactions**.

---

## 2. Configure M-Pesa Settings

Go to **M-Pesa > Settings**:

1. Get your **Consumer Key**, **Consumer Secret**, and **Passkey** from the
   [Safaricom Developer Portal](https://developer.safaricom.co.ke) — create
   an app under "My Apps" and select the "Lipa Na M-Pesa Online" (M-Pesa
   Express) product to get the Passkey.
2. Enter your **Shortcode** (PayBill or Till number) and select the matching
   **Shortcode Type**.
3. Choose **Sandbox** while testing, switch to **Production** when you go live.
4. Tick **Enable M-Pesa for this business** and save.
5. Copy the **Callback URLs** shown on the right and paste them into your
   Daraja app configuration:
   - **STK Push Callback URL** → used automatically with every STK push (no
     manual registration needed on Safaricom's side).
   - **C2B Confirmation/Validation URLs** → click "Register C2B URLs with
     Safaricom" once you're on a **Production** shortcode that has completed
     Go-Live, so direct Paybill payments are also logged.

> ⚠️ Safaricom callback URLs must be publicly reachable over HTTPS — `localhost`
> won't work. For local testing, use a tunnel like `ngrok` and update the app
> URL (`APP_URL` in `.env`) accordingly so the generated callback URLs are
> reachable.

---

## 3. The "Pay with M-Pesa" POS button (automatic — no template editing required)

Once you tick **Enable M-Pesa for this business** and save, an **M-Pesa**
button appears automatically on the POS (sell) screen, right next to the
**Cash** and **Card** express-checkout buttons. No core blade files are
edited and nothing needs to be manually wired up — this happens through the
same module-hook mechanism Ultimate POS core itself uses to let modules add
things to the POS screen (`SellPosController` → `ModuleUtil::getModuleData
('get_pos_screen_view')` → this module's `DataController::get_pos_screen_view()`
→ `Resources/views/partials/pos_button.blade.php`).

### How it behaves — identical mechanics to Cash/Card, with one extra step

1. **Cashier clicks "M-Pesa"** (same pre-flight checks as Cash/Card: must have
   products added, reward points validated if enabled, total must be > 0).
2. A modal opens asking for the **customer's phone number**, pre-filled with
   the sale total (read-only) and the customer's saved mobile number if one
   is on file.
3. Cashier clicks **Send Payment Request** → this hits `MpesaController::stkPush()`,
   which calls Safaricom's Daraja API to push an STK prompt to the customer's
   phone, and creates a `pending` row in `mpesa_transactions`.
4. The modal switches to a "waiting" state and **polls** `MpesaController::checkStatus()`
   every 4 seconds (giving up after 2 minutes) — this both checks our local
   DB (updated by Safaricom's async callback, see below) and, if still
   pending, proactively queries Safaricom directly for a faster result.
5. **On success**: the modal shows a green checkmark + the M-Pesa receipt
   number, then — exactly like clicking Cash or Card —
   it fills in the first payment row with method **"M-Pesa"** (this module's
   relabeled `custom_pay_1` slot) and the receipt number, and submits the POS
   form. Ultimate POS's own `SellPosController::store()` then creates the
   `transaction_payments` row, completes the sale, and shows the receipt —
   identical to a Cash/Card sale.
6. **On failure/cancellation/timeout**: the modal shows the reason and lets
   the cashier retry. **Nothing is submitted** — the sale is never finalized
   without confirmed payment.

This is implemented in `Resources/views/partials/pos_button.blade.php`
(injects the button + modal HTML) and `Resources/assets/js/pos_express.js` /
its Blade-wrapped copy `Resources/views/partials/pos_express_script.blade.php`
(all click/AJAX/polling/finalize logic).

### Why "Custom Payment 1"?

Ultimate POS core doesn't have a native "M-Pesa" payment method/enum, only
`cash`, `card`, `cheque`, `bank_transfer`, and seven generic `custom_pay_1..7`
slots. Rather than patch core migrations/enums (which would break on every
core update), this module uses `custom_pay_1` and auto-relabels it to
"M-Pesa" the first time you enable M-Pesa in settings (see
`SettingController::ensureCustomPaymentLabel()`) — it won't touch that label
again if you've since changed it to something else. This means M-Pesa sales
show up correctly everywhere core already understands payment methods:
receipts, the sales list, payment reports, the cash register report, etc. —
with the receipt number stored in the same `transaction_no_1` field core
already displays for custom payment types.

### Using an M-Pesa payment the customer already sent ("Recent M-Pesa" panel)

If the customer already paid (e.g. an STK push timed out client-side but
actually went through, or they paid your Paybill directly via C2B before
reaching the till), the cashier doesn't have to start a brand new STK push.
A **Recent M-Pesa** button on the sell screen opens a panel listing
unlinked, successful M-Pesa payments (`recentPayments()` in
`MpesaController`) that haven't been attached to a sale yet.

- The panel sends the current cart total to the server, which sorts the
  list by closeness to that amount and flags any exact match
  (`is_exact_match`). The card list always shows the closest matches first,
  with an **EXACT MATCH** badge and a banner at the top when one exists.
- **A payment can only be used if it matches the cart total exactly** (to
  the cent) — no split payments, no over/under-payments. Clicking a card
  that doesn't match shows a toastr notification explaining why and refuses
  to link it; this is enforced both client-side (instant feedback, no round
  trip) and authoritatively server-side in `linkPayment()`, so it can't be
  bypassed.
- A **search box** at the top of the panel lets the cashier filter by phone
  number (any format — `07..`, `2547..`, or just the last few digits) or by
  M-Pesa receipt/transaction code, debounced as they type.
- Tapping **Use this payment** on an exact match calls `linkPayment()`,
  which claims that M-Pesa transaction (so it can't be picked twice) and
  finalizes the sale the same way the STK-push success path does.
- Each card's button is scoped independently — clicking one only disables/
  relabels that card's own button (the others are dimmed, not relabeled),
  so multiple cards never show "Linking…" at once (this was a bug in an
  earlier version, since fixed).

### The original generic modal still exists too

`Resources/views/partials/stk_modal.blade.php` + `Resources/assets/js/mpesa.js`
are still here, unused by the POS button but available if you want a "Pay
with M-Pesa" option somewhere else (e.g. on an existing/unpaid invoice
screen, outside the POS flow) — see `markSalePaid()` in `CallbackController`
for how that flow links payment back to an existing sale via
`window.mpesa_transaction_id`. The two flows are independent and won't
double-pay a sale: the POS button never sends a `transaction_id` to
`stkPush()` (the sale doesn't exist yet), so `markSalePaid()` correctly skips
it; core's own form submission handles those payments instead.

---

## 4. Linking payments to sales (`markSalePaid`)

`Http/Controllers/CallbackController.php::markSalePaid()` automatically
creates a `transaction_payments` row (method = `mpesa`) when an STK push
linked to a `transaction_id` succeeds — but only for the **legacy generic
modal** flow (`stk_modal.blade.php` / `mpesa.js`), used for collecting
payment on a sale/invoice that already exists. This assumes the common
Ultimate POS schema (`App\Transaction`, `App\TransactionPayment` with
`transaction_id`, `amount`, `method`, `payment_ref_no`, `paid_on`).

The **POS express-checkout button** (section 3 above) does **not** use this
method — it never sends a `transaction_id` to `stkPush()`, since the sale
doesn't exist yet at that point. Instead, once payment is confirmed, it
fills in the payment row itself (method = `custom_pay_1`, i.e. "M-Pesa") and
submits the POS form, so core's own `SellPosController::store()` creates the
payment row. This avoids any chance of the same payment being recorded
twice.

If your Ultimate POS version uses different model names/namespaces or a
different payment-status calculation, open `markSalePaid()` and adjust the
class names / field names to match — everything else in the module
(settings, POS button, STK push, callbacks, notifications, transaction log)
works independently of this part.

### How the Invoice # column gets populated

Since the POS express-checkout button sends its STK push *before* the sale
exists, there's no `transaction_id` (or invoice number) to store at that
point. `Listeners/LinkMpesaTransactionToInvoice.php` solves this by hooking
core's own `App\Events\TransactionPaymentAdded` event (registered in
`MpesaServiceProvider::boot()` via `Event::listen()` — no core
`EventServiceProvider.php` edit needed):

1. The cashier finishes the M-Pesa payment and the POS form submits with
   `payment[0][custom_pay_1]` set and the receipt number in
   `payment[0][transaction_no_1]`.
2. Core's `TransactionUtil::createOrUpdatePaymentLines()` creates the sale
   (with its `invoice_no` already assigned) and the `transaction_payments`
   row, then fires `TransactionPaymentAdded`.
3. The listener checks if the payment method is `custom_pay_1` and has a
   `transaction_no`, looks up the matching `mpesa_transactions` row by
   `mpesa_receipt_number`, and writes back `transaction_id` + `invoice_no`.

For the **legacy generic modal** flow, `markSalePaid()` already has both the
`MpesaTransaction` and the `Transaction` in scope, so it writes `invoice_no`
back directly — no event needed for that path.

### Clicking the Invoice # shows only the slip, not the full sale details

Clicking an invoice number on the M-Pesa Transactions page opens a small
modal (markup + JS at the bottom of `Resources/views/transactions/index.blade.php`)
with **Invoice** / **Packing Slip** toggle buttons. It fetches the printable
HTML directly from core's own `SellPosController::printInvoice` endpoint
(the same one core's "Print Invoice" / "Packing Slip" menu items use) and
renders just that — no line-items table, payments tab, or activity log,
unlike core's full "View Sale" modal (`SellController::show`), which this
intentionally bypasses. A **Print** button opens the slip in a new tab and
triggers the browser's print dialog.

---

## 5. Notifications

A bell-icon (in-app) notification fires automatically whenever an M-Pesa
payment is confirmed — both for POS sales and for direct Paybill (C2B)
payments that didn't go through the POS at all:

- **Who gets notified**: the cashier who created the linked sale (if any),
  and the business owner (always — it's their money, and C2B payments have
  no associated cashier).
- **How it's delivered**: `Notifications/PaymentReceivedNotification.php`
  uses Laravel's built-in `database` notification channel — the exact same
  mechanism core Ultimate POS uses for its own notifications
  (`RecurringInvoiceNotification`, etc). Since `App\User` already has the
  `Notifiable` trait, `$user->notify(new PaymentReceivedNotification(...))`
  in `CallbackController::notifyPaymentReceived()` is all it takes — no core
  changes needed.
- **How it appears in the UI**: core's bell-icon dropdown already calls
  `auth()->user()->notifications()` / `unreadNotifications`
  (`HomeController::loadMoreNotifications()` /
  `getTotalUnreadNotifications()`), and `Util::parseNotifications()` already
  falls through to `ModuleUtil::getModuleData('parse_notification', ...)`
  for any notification type it doesn't natively recognize. This module's
  `DataController::parse_notification()` (already present) handles that
  fall-through and renders the message text, icon, and a link to
  **M-Pesa > Transactions**.
- **Where it's triggered from**: `CallbackController::stkCallback()`, right
  after a successful Safaricom STK callback is processed — so it fires the
  moment the customer's payment is confirmed, even if the cashier has
  already moved on to the next sale.
- **Want email/SMS too instead of just the bell icon?** Add `'mail'` (or
  your SMS channel) to the `via()` method in `PaymentReceivedNotification`
  and implement the corresponding `toMail()` / `toSms()` method — the
  `database` channel can stay alongside it.

---

## 6. Testing in Sandbox

Safaricom's sandbox shortcode `174379` and test phone number
`254708374149` are commonly used for testing STK Push. Use the sandbox
Consumer Key/Secret/Passkey from your Daraja app's "Lipa Na M-Pesa Online
Sandbox" credentials. Amounts in sandbox are typically capped (e.g. max
KES 1–10 depending on the test credentials provided).

---

## File structure

```
Mpesa/
├── Config/config.php
├── Database/Migrations/
│   ├── 2024_01_01_000000_add_module_version_to_system_table.php
│   ├── 2024_01_01_000001_create_mpesa_settings_table.php
│   ├── 2024_01_01_000002_create_mpesa_transactions_table.php
│   ├── 2024_01_01_000003_add_mpesa_permissions.php
│   └── 2024_01_01_000004_add_invoice_no_to_mpesa_transactions_table.php
├── Entities/
│   ├── MpesaSetting.php
│   └── MpesaTransaction.php
├── Http/Controllers/
│   ├── DataController.php        (permissions, menu, POS button hook, notifications)
│   ├── InstallController.php     (Manage Modules install/update/uninstall)
│   ├── SettingController.php     (admin settings page, auto-labels "Custom Payment 1")
│   ├── MpesaController.php       (STK push, status check, transactions list + search)
│   └── CallbackController.php    (Safaricom callbacks, marks sales paid, notifies)
├── Listeners/
│   └── LinkMpesaTransactionToInvoice.php  (auto-fills Invoice # after a sale completes)
├── Notifications/
│   └── PaymentReceivedNotification.php   (bell-icon notification on payment success)
├── Providers/
│   ├── MpesaServiceProvider.php
│   └── RouteServiceProvider.php
├── Resources/
│   ├── assets/js/
│   │   ├── mpesa.js          (legacy generic "Pay with M-Pesa" modal behaviour)
│   │   └── pos_express.js    (POS express-checkout button behaviour)
│   ├── lang/en/lang.php
│   └── views/
│       ├── settings/index.blade.php
│       ├── transactions/index.blade.php  (now includes the Invoice # column + search)
│       └── partials/
│           ├── stk_modal.blade.php          (legacy generic modal HTML)
│           ├── pos_button.blade.php         (POS button + modal — loaded automatically)
│           └── pos_express_script.blade.php (pos_express.js, inlined as a Blade view)
├── Routes/
│   ├── web.php   (authenticated: settings, transactions, AJAX)
│   └── api.php   (public: Safaricom callbacks)
├── composer.json
└── module.json
```

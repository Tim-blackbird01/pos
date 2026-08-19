# KRA eTIMS (OSCU) setup

This POS sends each newly completed (`final`) sale to KRA eTIMS after the local sale commits. A KRA outage cannot lose or reverse a POS sale: the eTIMS record is stored as `failed` for retry after the connection is restored.

## Before enabling production

KRA requires approval, OSCU device activation, sandbox testing, and valid item master data before a system can issue production invoices. The integration follows the OSCU `saveTrnsSalesOsdc` API; it does not replace that onboarding/certification process. See [KRA's system-to-system integration guidance](https://www.kra.go.ke/business/etims-electronic-tax-invoice-management-system/learn-about-etims/etims-system-to-system-integration) and the [OSCU specification](https://www.kra.go.ke/images/publications/OSCU_Specification_Document_v2.0.pdf).

## Per-business setup

Each subscriber configures eTIMS in **Business Settings → KRA eTIMS**. The credentials are encrypted at rest and are loaded only for sales belonging to that business; credentials from another subscriber, or from `.env`, are never used.

Keep eTIMS disabled until all sandbox values are ready. Then fill in the values supplied by KRA:

- `ETIMS_TIN`, `ETIMS_BRANCH_ID`, `ETIMS_DEVICE_SERIAL_NUMBER`, and `ETIMS_COMMUNICATION_KEY`
- `ETIMS_ITEM_CLASSIFICATION_CODE`, unit codes, and tax-code/rate mapping that applies to your registered goods
- `ETIMS_ENVIRONMENT=production` and `ETIMS_ENABLED=true` only after KRA approves production use.

Product SKUs are used as eTIMS item codes (optionally prefixed by `ETIMS_ITEM_CODE_PREFIX`) and must match the item codes registered with KRA. Configure valid SKUs and eTIMS item master data before enabling invoices.

After deploying this feature, run `php artisan migrate --force`.

The accepted KRA receipt number, signature, and internal data are retained in `etims_invoices` and appear on the classic receipt template.

Retry a failed invoice with `php artisan etims:sync TRANSACTION_ID`, or retry all failed submissions with `php artisan etims:sync --failed`.

@extends ('marketing.layout')

@push ('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
        :root {
            --bg-white: #f4f7f5;
            --green-dark: #0f3d26;
            --green-mid: #165334;
            --green-accent: #48a96b;
            --green-glow: #82e28a;
            --green-lime: #52c26d;
            --text-dark: #1f2937;
            --text-muted: #4b5563;
            --card-bg: rgba(255, 255, 255, 0.95);
            --border-color: #e2e8f0;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        html {
            scroll-padding-top: 96px;
        }
        :target {
            scroll-margin-top: 110px;
        }
        body {
            font-family:
                'Inter',
                ui-sans-serif,
                system-ui,
                -apple-system,
                sans-serif;
            background-color: var(--bg-white);
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }
        .bg-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
            background-color: var(--bg-white);
        }
        .green-section {
            position: absolute;
            top: -20%;
            right: -10%;
            width: 85%;
            height: 140%;
            background: radial-gradient(circle at 80% 20%, #175a38 0%, #0c331f 70%, #072214 100%);
            transform: rotate(-38deg);
            transform-origin: top left;
            border-top-left-radius: 40px;
            box-shadow: -15px 15px 40px rgba(0, 0, 0, 0.2);
        }
        .border-glow-main {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-left: 3px solid var(--green-lime);
            border-top-left-radius: 40px;
            box-shadow:
                inset 6px 0 12px var(--green-glow),
                -2px 0 10px var(--green-glow);
            pointer-events: none;
        }
        .dark-sq {
            position: absolute;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.04);
            pointer-events: none;
        }
        .dark-sq-1 {
            width: 420px;
            height: 220px;
            bottom: 8%;
            left: -5%;
        }
        .dark-sq-2 {
            width: 380px;
            height: 200px;
            bottom: 12%;
            right: 18%;
            border-color: rgba(130, 226, 138, 0.25);
        }
        .dot-grid {
            position: absolute;
            top: 18%;
            right: 12%;
            width: 320px;
            height: 320px;
            background-image: radial-gradient(rgba(82, 194, 109, 0.4) 2px, transparent 2px);
            background-size: 16px 16px;
            mask-image: radial-gradient(circle at center, black 40%, transparent 80%);
            -webkit-mask-image: radial-gradient(circle at center, black 40%, transparent 80%);
            opacity: 0.7;
            pointer-events: none;
        }
        .light-sq {
            position: absolute;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(4px);
            transform: rotate(-38deg);
            pointer-events: none;
        }
        .light-sq-1 {
            width: 480px;
            height: 280px;
            top: -100px;
            left: -80px;
        }
        .light-sq-2 {
            width: 360px;
            height: 200px;
            top: 120px;
            left: 20px;
            background: rgba(255, 255, 255, 0.45);
        }
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 24px 80px;
            position: relative;
            z-index: 10;
        }
        .doc-header {
            margin-bottom: 40px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border-color);
        }
        .doc-header h1 {
            font-size: 42px;
            font-weight: 800;
            color: var(--green-dark);
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }
        .doc-meta {
            display: flex;
            gap: 24px;
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 500;
        }
        .layout-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 40px;
            align-items: start;
        }
        .sidebar-toc {
            position: sticky;
            top: 100px;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
            max-height: calc(100vh - 140px);
            overflow-y: auto;
        }
        .toc-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--green-dark);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .toc-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .toc-list a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            display: block;
            padding: 6px 10px;
            border-radius: 8px;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .toc-list a:hover {
            background: rgba(72, 169, 107, 0.1);
            color: var(--green-dark);
            padding-left: 14px;
        }
        .doc-content {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        .section-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.03);
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
            scroll-margin-top: 110px;
        }
        .section-card h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 12px;
        }
        .section-card p {
            color: var(--text-dark);
            font-size: 15px;
            margin-bottom: 12px;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 16px;
        }
        .feature-item {
            background: #ffffff;
            border: 1px solid #d1fae5;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            color: #065f46;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }
        .feature-item i {
            color: var(--green-accent);
        }
        .custom-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 12px;
        }
        .custom-list li {
            position: relative;
            padding-left: 28px;
            font-size: 15px;
            color: var(--text-dark);
        }
        .custom-list li::before {
            content: none;
            position: absolute;
            left: 0;
            top: 2px;
            font-size: 14px;
        }
        .custom-list.forbidden li::before {
            content: none;
        }
        .contact-box {
            background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-mid) 100%);
            color: #ffffff;
            padding: 32px;
            border-radius: 16px;
            margin-top: 16px;
        }
        .contact-box h3 {
            font-size: 18px;
            margin-bottom: 12px;
            color: var(--green-glow);
        }
        .contact-box p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            margin-bottom: 8px;
        }
        .contact-box a {
            color: var(--green-glow);
            text-decoration: none;
            font-weight: 600;
        }
        @media (max-width: 992px) {
            .layout-grid {
                grid-template-columns: 1fr;
            }
            .sidebar-toc {
                display: none;
            }
            .nav {
                padding: 16px 24px;
            }
            .doc-header h1 {
                font-size: 32px;
            }
        }
    </style>
@endpush

@section ('content')
    <div class="bg-wrapper" aria-hidden="true">
        <div class="light-sq light-sq-1"></div>
        <div class="light-sq light-sq-2"></div>
        <div class="green-section">
            <div class="border-glow-main"></div>
            <div class="dark-sq dark-sq-1"></div>
            <div class="dark-sq dark-sq-2"></div>
            <div class="dot-grid"></div>
        </div>
    </div>

    <div class="container">
        <header class="doc-header">
            <h1>Terms of Service</h1>
            <div class="doc-meta">
                <span><i class="far fa-calendar-alt"></i> Effective Date: January 1, 2026</span>
                <span><i class="far fa-clock"></i> Last Updated: August 7, 2026</span>
            </div>
        </header>

        <div class="layout-grid">
            <aside class="sidebar-toc">
                <div class="toc-title"><i class="fas fa-list-ol"></i> Navigation</div>
                <ul class="toc-list">
                    <li><a href="#sec-1">1. Introduction</a></li>
                    <li><a href="#sec-2">2. Eligibility</a></li>
                    <li><a href="#sec-3">3. Our Services</a></li>
                    <li><a href="#sec-4">4. User Accounts</a></li>
                    <li><a href="#sec-5">5. Business Data</a></li>
                    <li><a href="#sec-6">6. Acceptable Use</a></li>
                    <li><a href="#sec-7">7. Payments</a></li>
                    <li><a href="#sec-8">8. Refund Policy</a></li>
                    <li><a href="#sec-9">9. Taxes</a></li>
                    <li><a href="#sec-10">10. KRA eTIMS</a></li>
                    <li><a href="#sec-11">11. Integrations</a></li>
                    <li><a href="#sec-12">12. Service Availability</a></li>
                    <li><a href="#sec-13">13. Data Backups</a></li>
                    <li><a href="#sec-14">14. Intellectual Property</a></li>
                    <li><a href="#sec-15">15. Confidentiality</a></li>
                    <li><a href="#sec-16">16. Suspension</a></li>
                    <li><a href="#sec-17">17. Termination</a></li>
                    <li><a href="#sec-18">18. Limitation of Liability</a></li>
                    <li><a href="#sec-19">19. Force Majeure</a></li>
                    <li><a href="#sec-20">20. Changes to Terms</a></li>
                    <li><a href="#sec-21">21. Governing Law</a></li>
                    <li><a href="#sec-22">22. Contact Us</a></li>
                </ul>
            </aside>

            <main class="doc-content">
                <section id="sec-1" class="section-card">
                    <h2><i class="fas fa-info-circle"></i> 1. Introduction</h2>
                    <p>Welcome to <strong>{{ config('app.name', 'CraftSalesPOS') }}</strong> ("Platform", "System", "Service", "we", "our", or "us").</p>
                    <p>These Terms of Service govern your access to and use of our cloud-based Point of Sale, Inventory Management, Accounting, Customer Management, Reporting, and Business Management Platform.</p>
                    <p>By creating an account or using our services, you agree to these Terms.</p>
                </section>

                <section id="sec-2" class="section-card">
                    <h2><i class="fas fa-user-check"></i> 2. Eligibility</h2>
                    <p>You may only use the Platform if:</p>
                    <ul class="custom-list">
                        <li>You are at least 18 years old.</li>
                        <li>You have authority to register your business.</li>
                        <li>The information you provide is accurate.</li>
                        <li>You comply with Kenyan laws.</li>
                    </ul>
                </section>

                <section id="sec-3" class="section-card">
                    <h2><i class="fas fa-cubes"></i> 3. Our Services</h2>
                    <p>The Platform provides an integrated set of features designed to help operate your business. Features may vary depending on your active subscription plan:</p>
                    <div class="feature-grid">
                        <div class="feature-item">
                            <i class="fas fa-cash-register"></i> Point of Sale
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-boxes"></i> Inventory Management
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-exchange-alt"></i> Stock Transfers
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-store-alt"></i> Multi-Branch Management
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-users-cog"></i> Employee Management
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-address-book"></i> Customer Management
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-truck-loading"></i> Supplier Management
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-file-invoice"></i> Purchase Orders
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-shopping-cart"></i> Sales Orders
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-receipt"></i> Expense Tracking
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-chart-line"></i> Financial Reports
                        </div>
                        <div class="feature-item"><i class="fas fa-calculator"></i> Accounting</div>
                        <div class="feature-item">
                            <i class="fas fa-barcode"></i> Barcode Generation
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-print"></i> Receipt Printing
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-percent"></i> Tax Management
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-landmark"></i> KRA eTIMS Integration
                        </div>
                        <div class="feature-item"><i class="fas fa-sms"></i> SMS Notifications</div>
                        <div class="feature-item">
                            <i class="fas fa-envelope"></i> Email Notifications
                        </div>
                        <div class="feature-item"><i class="fas fa-gift"></i> Loyalty Programs</div>
                        <div class="feature-item">
                            <i class="fas fa-credit-card"></i> Gift Cards
                        </div>
                        <div class="feature-item"><i class="fas fa-globe"></i> Online Ordering</div>
                        <div class="feature-item">
                            <i class="fas fa-mobile-alt"></i> Mobile Applications
                        </div>
                        <div class="feature-item"><i class="fas fa-wifi"></i> Offline Mode</div>
                        <div class="feature-item">
                            <i class="fas fa-code"></i> APIs & Integrations
                        </div>
                    </div>
                </section>

                <section id="sec-4" class="section-card">
                    <h2><i class="fas fa-user-shield"></i> 4. User Accounts</h2>
                    <p>You are responsible for:</p>
                    <ul class="custom-list">
                        <li>Keeping passwords secure.</li>
                        <li>Restricting access to your account.</li>
                        <li>Activities under your account.</li>
                        <li>Creating user accounts responsibly.</li>
                        <li>Removing former employees' access.</li>
                    </ul>
                    <p style="
                            margin-top: 12px;
                        ">You must immediately notify us if unauthorized access occurs.</p>
                </section>

                <section id="sec-5" class="section-card">
                    <h2><i class="fas fa-database"></i> 5. Business Data</h2>
                    <p>You retain full ownership of all business records submitted to the platform, including: <strong>Products, Customers, Suppliers, Employees, Sales, Purchases, Reports, Documents, Receipts, and Financial Records</strong>.</p>
                    <p>You grant us permission to store, process, backup, and display your data solely for providing the Service.</p>
                </section>

                <section id="sec-6" class="section-card">
                    <h2><i class="fas fa-ban"></i> 6. Acceptable Use</h2>
                    <p>You agree <strong>NOT</strong> to:</p>
                    <ul class="custom-list forbidden">
                        <li>Break Kenyan laws.</li>
                        <li>Sell illegal products.</li>
                        <li>Store malicious software.</li>
                        <li>Attempt unauthorized access.</li>
                        <li>Reverse engineer the Platform.</li>
                        <li>Abuse APIs.</li>
                        <li>Upload viruses.</li>
                        <li>Interfere with other customers.</li>
                        <li>Perform fraudulent transactions.</li>
                        <li>Use stolen payment methods.</li>
                        <li>Circumvent subscription limits.</li>
                    </ul>
                </section>

                <section id="sec-7" class="section-card">
                    <h2><i class="fas fa-credit-card"></i> 7. Payments</h2>
                    <p>Subscriptions are billed <strong>Monthly, Quarterly, or Annually</strong> depending on your selected plan.</p>
                    <p>Fees are payable in advance. Failure to pay may result in suspension, restricted access, or account termination.</p>
                </section>

                <section id="sec-8" class="section-card">
                    <h2><i class="fas fa-undo"></i> 8. Refund Policy</h2>
                    <p>Unless required by law:</p>
                    <ul class="custom-list">
                        <li>Subscription fees are non-refundable.</li>
                        <li>Partial months are not refunded.</li>
                        <li>Trial periods are provided "as is."</li>
                    </ul>
                </section>

                <section id="sec-9" class="section-card">
                    <h2><i class="fas fa-file-invoice-dollar"></i> 9. Taxes</h2>
                    <p>You remain solely responsible for statutory obligations including: <strong>VAT, Income Tax, Excise Duty, Withholding Tax, PAYE, NHIF/SHIF, NSSF, Housing Levy</strong>, and any other relevant requirements.</p>
                    <p>The Platform assists in calculations but does not constitute formal tax advice.</p>
                </section>

                <section id="sec-10" class="section-card">
                    <h2><i class="fas fa-university"></i> 10. KRA eTIMS</h2>
                    <p>Where enabled:</p>
                    <ul class="custom-list">
                        <li>
                            We facilitate integration with Kenya Revenue Authority (KRA) systems.
                        </li>
                        <li>You remain responsible for compliance.</li>
                        <li>You are responsible for maintaining valid KRA credentials.</li>
                        <li>We are not liable for KRA downtime.</li>
                    </ul>
                </section>

                <section id="sec-11" class="section-card">
                    <h2><i class="fas fa-plug"></i> 11. Integrations</h2>
                    <p>Our Platform may integrate with third-party providers including <strong>M-Pesa, Banks, Payment Gateways, Email Providers, SMS Providers, Accounting Software, Ecommerce Platforms, and Delivery Providers</strong>.</p>
                    <p>We are not responsible for third-party service outages or failures.</p>
                </section>

                <section id="sec-12" class="section-card">
                    <h2><i class="fas fa-server"></i> 12. Service Availability</h2>
                    <p>We strive for high uptime but cannot guarantee uninterrupted service. Maintenance may occasionally occur, and emergency maintenance may happen without prior notice.</p>
                </section>

                <section id="sec-13" class="section-card">
                    <h2><i class="fas fa-hdd"></i> 13. Data Backups</h2>
                    <p>We perform regular automated data backups. However, you should also maintain your own local copies of important business records.</p>
                </section>

                <section id="sec-14" class="section-card">
                    <h2><i class="fas fa-copyright"></i> 14. Intellectual Property</h2>
                    <p>The Platform including software, design, logos, documentation, source code, and databases remain our intellectual property. You receive a limited, non-transferable license to use the system.</p>
                </section>

                <section id="sec-15" class="section-card">
                    <h2><i class="fas fa-lock"></i> 15. Confidentiality</h2>
                    <p>Both parties agree to protect confidential information including business information, customer data, financial information, and trade secrets.</p>
                </section>

                <section id="sec-16" class="section-card">
                    <h2><i class="fas fa-user-slash"></i> 16. Suspension</h2>
                    <p>We may suspend accounts if payments are overdue, fraud is detected, security risks arise, these Terms are violated, or if required by law.</p>
                </section>

                <section id="sec-17" class="section-card">
                    <h2><i class="fas fa-door-open"></i> 17. Termination</h2>
                    <p>Either party may terminate the agreement at any time. Upon termination, access ends. Data may be retained temporarily for recovery before being deleted according to our retention policy.</p>
                </section>

                <section id="sec-18" class="section-card">
                    <h2><i class="fas fa-shield-alt"></i> 18. Limitation of Liability</h2>
                    <p>To the maximum extent permitted by Kenyan law, we are not liable for lost profits, lost business, lost customers, lost goodwill, business interruption, data corruption, or indirect/consequential damages.</p>
                    <p>Our maximum total liability shall not exceed the subscription fees paid during the previous twelve (12) months.</p>
                </section>

                <section id="sec-19" class="section-card">
                    <h2><i class="fas fa-cloud-rain"></i> 19. Force Majeure</h2>
                    <p>We are not liable for delays caused by natural disasters, internet outages, government actions, power failures, war, terrorism, epidemics, or civil unrest.</p>
                </section>

                <section id="sec-20" class="section-card">
                    <h2><i class="fas fa-edit"></i> 20. Changes to Terms</h2>
                    <p>We may update these Terms from time to time. Continued use of the Platform after changes are published constitutes your acceptance of the updated Terms.</p>
                </section>

                <section id="sec-21" class="section-card">
                    <h2><i class="fas fa-gavel"></i> 21. Governing Law</h2>
                    <p>These Terms are governed by the laws of the Republic of Kenya. Disputes shall be resolved in Kenyan courts unless arbitration is agreed upon by both parties.</p>
                </section>

                <section id="sec-22" class="section-card">
                    <h2><i class="fas fa-envelope"></i> 22. Contact</h2>
                    <p>If you have any questions or concerns regarding these Terms, please contact our legal team:</p>
                    <div class="contact-box">
                        <h3>CraftSalesPOS Legal Team</h3>
                        <p><i class="fas fa-envelope"></i> Email: <a href="mailto:legal@craftsalespos.co.ke">legal@craftsalespos.co.ke</a></p>
                        <p><i class="fas fa-phone"></i> Phone: <a href="tel:+254700000000">+254 700 000 000</a></p>
                    </div>
                </section>
            </main>
        </div>
    </div>

@endsection

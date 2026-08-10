@extends('marketing.layout')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root { --bg-white:#f4f7f5; --green-dark:#0f3d26; --green-mid:#165334; --green-accent:#48a96b; --green-glow:#82e28a; --green-lime:#52c26d; --text-dark:#1f2937; --text-muted:#4b5563; --card-bg:rgba(255,255,255,0.95); --border-color:#e2e8f0; }
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-padding-top:96px}
:target{scroll-margin-top:110px}
body{font-family:'Inter',ui-sans-serif,system-ui,-apple-system,sans-serif;background-color:var(--bg-white);color:var(--text-dark);line-height:1.6;overflow-x:hidden}
.bg-wrapper{position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:-1;overflow:hidden;background-color:var(--bg-white)}
.green-section{position:absolute;top:-20%;right:-10%;width:85%;height:140%;background:radial-gradient(circle at 80% 20%,#175a38 0%,#0c331f 70%,#072214 100%);transform:rotate(-38deg);transform-origin:top left;border-top-left-radius:40px;box-shadow:-15px 15px 40px rgba(0,0,0,.2)}
.border-glow-main{position:absolute;top:0;left:0;width:100%;height:100%;border-left:3px solid var(--green-lime);border-top-left-radius:40px;box-shadow:inset 6px 0 12px var(--green-glow),-2px 0 10px var(--green-glow);pointer-events:none}
.dark-sq{position:absolute;border-radius:24px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.04);pointer-events:none}
.dark-sq-1{width:420px;height:220px;bottom:8%;left:-5%}
.dark-sq-2{width:380px;height:200px;bottom:12%;right:18%;border-color:rgba(130,226,138,.25)}
.dot-grid{position:absolute;top:18%;right:12%;width:320px;height:320px;background-image:radial-gradient(rgba(82,194,109,.4) 2px,transparent 2px);background-size:16px 16px;mask-image:radial-gradient(circle at center,black 40%,transparent 80%);-webkit-mask-image:radial-gradient(circle at center,black 40%,transparent 80%);opacity:.7;pointer-events:none}
.light-sq{position:absolute;border-radius:28px;background:rgba(255,255,255,.65);backdrop-filter:blur(4px);transform:rotate(-38deg);pointer-events:none}
.light-sq-1{width:480px;height:280px;top:-100px;left:-80px}
.light-sq-2{width:360px;height:200px;top:120px;left:20px;background:rgba(255,255,255,.45)}
.container{max-width:1280px;margin:0 auto;padding:40px 24px 80px;position:relative;z-index:10}
.doc-header{margin-bottom:40px;padding-bottom:24px;border-bottom:1px solid var(--border-color)}
.doc-header h1{font-size:42px;font-weight:800;color:var(--green-dark);margin-bottom:12px;letter-spacing:-.02em}
.doc-meta{display:flex;gap:24px;font-size:14px;color:var(--text-muted);font-weight:500}
.layout-grid{display:grid;grid-template-columns:280px 1fr;gap:40px;align-items:start}
.sidebar-toc{position:sticky;top:100px;background:var(--card-bg);border:1px solid var(--border-color);border-radius:16px;padding:24px;box-shadow:0 10px 25px rgba(0,0,0,.03);max-height:calc(100vh - 140px);overflow-y:auto}
.toc-title{font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--green-dark);margin-bottom:16px;display:flex;align-items:center;gap:8px}
.toc-list{list-style:none;display:flex;flex-direction:column;gap:8px}
.toc-list a{color:var(--text-muted);text-decoration:none;font-size:13px;font-weight:500;display:block;padding:6px 10px;border-radius:8px;transition:all .2s ease;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.toc-list a:hover{background:rgba(72,169,107,.1);color:var(--green-dark);padding-left:14px}
.doc-content{display:flex;flex-direction:column;gap:32px}
.section-card{background:var(--card-bg);border:1px solid var(--border-color);border-radius:20px;padding:36px;box-shadow:0 8px 30px rgba(0,0,0,.03);transition:transform .2s ease,box-shadow .2s ease;scroll-margin-top:110px}
.section-card h2{font-size:22px;font-weight:700;color:var(--green-dark);margin-bottom:16px;display:flex;align-items:center;gap:12px;border-bottom:2px solid #f1f5f9;padding-bottom:12px}
.section-card p{color:var(--text-dark);font-size:15px;margin-bottom:12px}
.data-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;margin-top:16px}
.data-card{background:#fff;border:1px solid var(--border-color);border-radius:12px;padding:16px}
.custom-list{list-style:none;display:flex;flex-direction:column;gap:10px;margin-top:8px}
.custom-list li{position:relative;padding-left:24px;font-size:14px;color:var(--text-dark)}
.custom-list li::before{content:none;position:absolute;left:0;top:2px;font-size:12px}
.badge-compliance{display:inline-flex;align-items:center;gap:6px;background:#d1fae5;color:#065f46;font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px;margin-bottom:12px}
.contact-box{background:linear-gradient(135deg,var(--green-dark) 0%,var(--green-mid) 100%);color:#fff;padding:32px;border-radius:16px;margin-top:16px}
.contact-box h3{font-size:18px;margin-bottom:12px;color:var(--green-glow)}
.contact-box p{color:rgba(255,255,255,.85);font-size:14px;margin-bottom:8px}
.contact-box a{color:var(--green-glow);text-decoration:none;font-weight:600}
@media(max-width:992px){.layout-grid{grid-template-columns:1fr}.sidebar-toc{display:none}.doc-header h1{font-size:32px}}
@media(max-width:760px){.container{padding:28px 16px 60px}.doc-header{margin-bottom:32px;padding-bottom:20px}.doc-meta{flex-wrap:wrap;gap:10px}.doc-meta span{display:inline-flex;align-items:center;gap:8px}.data-grid{grid-template-columns:1fr}.section-card{padding:28px}.data-card{padding:18px}.contact-box{padding:24px}.toc-title{font-size:13px}.toc-list a{font-size:13px;padding:8px 10px}.custom-list{gap:8px}.custom-list li{padding-left:22px}.feature-grid{grid-template-columns:1fr}.layout-grid{gap:24px}}
@media(max-width:540px){.container{padding:22px 14px 50px}.doc-header{padding-bottom:18px}.doc-header h1{font-size:28px}.doc-meta{gap:8px}.doc-meta span{font-size:13px}.section-card{padding:22px}.contact-box{padding:20px}.toc-title{display:none}.toc-list{display:none}.layout-grid{gap:18px}}
</style>
@endpush

@section('content')
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
            <div class="badge-compliance"><i class="fas fa-check-circle"></i> Kenya Data Protection Act, 2019 Compliant</div>
            <h1>Privacy Policy</h1>
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
                    <li><a href="#sec-2">2. Information We Collect</a></li>
                    <li><a href="#sec-3">3. How We Use Information</a></li>
                    <li><a href="#sec-4">4. Cookies</a></li>
                    <li><a href="#sec-5">5. Sharing Information</a></li>
                    <li><a href="#sec-6">6. Data Security</a></li>
                    <li><a href="#sec-7">7. Data Retention</a></li>
                    <li><a href="#sec-8">8. Your Rights</a></li>
                    <li><a href="#sec-9">9. International Transfers</a></li>
                    <li><a href="#sec-10">10. Children's Privacy</a></li>
                    <li><a href="#sec-11">11. Account Security</a></li>
                    <li><a href="#sec-12">12. Data Breaches</a></li>
                    <li><a href="#sec-13">13. Third-Party Services</a></li>
                    <li><a href="#sec-14">14. Updates to this Policy</a></li>
                    <li><a href="#sec-15">15. Contact Information</a></li>
                </ul>
            </aside>

            <main class="doc-content">

                <section id="sec-1" class="section-card">
                    <h2><i class="fas fa-info-circle"></i> 1. Introduction</h2>
                    <p>This Privacy Policy explains how <strong>{{ config('app.name', 'CraftSalesPOS') }}</strong> collects, uses, stores, and protects your information in accordance with the <strong>Kenya Data Protection Act, 2019</strong>.</p>
                </section>

                <section id="sec-2" class="section-card">
                    <h2><i class="fas fa-database"></i> 2. Information We Collect</h2>
                    <p>To provide our services efficiently, we gather various categories of data when you utilize our platform:</p>
                    
                    <div class="data-grid">
                        <div class="data-card">
                            <h3><i class="fas fa-building"></i> Business Data</h3>
                            <ul class="custom-list">
                                <li>Business name & Registration</li>
                                <li>KRA PIN & VAT number</li>
                                <li>Physical address & Branches</li>
                            </ul>
                        </div>

                        <div class="data-card">
                            <h3><i class="fas fa-user-circle"></i> Account Details</h3>
                            <ul class="custom-list">
                                <li>Full Name, Email & Phone</li>
                                <li>Username & Encrypted Password</li>
                            </ul>
                        </div>

                        <div class="data-card">
                            <h3><i class="fas fa-address-book"></i> Customer Info</h3>
                            <ul class="custom-list">
                                <li>Names, Contacts & Emails</li>
                                <li>Loyalty points & History</li>
                                <li>Credit balances</li>
                            </ul>
                        </div>

                        <div class="data-card">
                            <h3><i class="fas fa-users-cog"></i> Employee Info</h3>
                            <ul class="custom-list">
                                <li>Names & Positions</li>
                                <li>User permissions</li>
                                <li>Attendance & Logs</li>
                            </ul>
                        </div>

                        <div class="data-card">
                            <h3><i class="fas fa-receipt"></i> Transaction Info</h3>
                            <ul class="custom-list">
                                <li>Sales, Purchases & Refunds</li>
                                <li>Discounts, Taxes & Methods</li>
                            </ul>
                        </div>

                        <div class="data-card">
                            <h3><i class="fas fa-laptop"></i> Device Info</h3>
                            <ul class="custom-list">
                                <li>Browser, OS & Device IDs</li>
                                <li>IP Address & Timestamps</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section id="sec-3" class="section-card">
                    <h2><i class="fas fa-tasks"></i> 3. How We Use Information</h2>
                    <p>We process collected information to:</p>
                    <ul class="custom-list">
                        <li>Provide, operate, and maintain the Platform.</li>
                        <li>Process financial transactions and generate business reports.</li>
                        <li>Improve security and detect fraudulent activity.</li>
                        <li>Enhance platform services and user experience.</li>
                        <li>Send system notifications and transactional updates.</li>
                        <li>Provide customer support.</li>
                        <li>Comply with legal and statutory obligations.</li>
                    </ul>
                </section>

                <section id="sec-4" class="section-card">
                    <h2><i class="fas fa-cookie-bite"></i> 4. Cookies</h2>
                    <p>We use essential cookies and tracking technologies to:</p>
                    <ul class="custom-list">
                        <li>Keep users securely signed in during active sessions.</li>
                        <li>Improve performance and remember platform preferences.</li>
                        <li>Secure user sessions against unauthorized access.</li>
                        <li>Analyze usage patterns to improve functionality.</li>
                    </ul>
                </section>

                <section id="sec-5" class="section-card">
                    <h2><i class="fas fa-share-alt"></i> 5. Sharing Information</h2>
                    <p>We do <strong>NOT</strong> sell personal information. We share information only with authorized parties where strictly necessary:</p>
                    <ul class="custom-list">
                        <li>Payment processors, SMS providers, and Email service providers.</li>
                        <li>Cloud hosting providers and infrastructure partners.</li>
                        <li>Government agencies where legally mandated.</li>
                        <li>Kenya Revenue Authority (KRA) for eTIMS integration.</li>
                        <li>Auditors and professional advisers bound by confidentiality obligations.</li>
                    </ul>
                </section>

                <section id="sec-6" class="section-card">
                    <h2><i class="fas fa-user-lock"></i> 6. Data Security</h2>
                    <p>We implement strict administrative, physical, and technical safeguards, including:</p>
                    <ul class="custom-list">
                        <li>TLS encryption for data in transit and encryption at rest where appropriate.</li>
                        <li>Role-based access controls (RBAC) and detailed audit logs.</li>
                        <li>Regular automated backups and Firewall protection.</li>
                        <li>Multi-Factor Authentication (MFA) and continuous system monitoring.</li>
                    </ul>
                    <p style="margin-top:12px;font-size:13px;color:var(--text-muted);">* Note: No system can be 100% secure; users are responsible for keeping their credentials confidential.</p>
                </section>

                <section id="sec-7" class="section-card">
                    <h2><i class="fas fa-history"></i> 7. Data Retention</h2>
                    <p>We retain your information while your account is active, as necessary for tax obligations under Kenyan law, to resolve disputes, and to satisfy legal requirements.</p>
                    <p>When statutory retention periods expire, information is securely deleted or anonymized.</p>
                </section>

                <section id="sec-8" class="section-card">
                    <h2><i class="fas fa-user-shield"></i> 8. Your Rights</h2>
                    <p>Subject to the <strong>Kenya Data Protection Act, 2019</strong>, you have the right to:</p>
                    <ul class="custom-list">
                        <li>Access your personal information held by us.</li>
                        <li>Request correction of inaccurate or incomplete information.</li>
                        <li>Request deletion of data where legally permissible.</li>
                        <li>Restrict or object to specific processing activities.</li>
                        <li>Receive a copy of your data in a portable, structured format.</li>
                        <li>Withdraw consent where processing relies upon consent.</li>
                    </ul>
                </section>

                <section id="sec-9" class="section-card">
                    <h2><i class="fas fa-globe-africa"></i> 9. International Transfers</h2>
                    <p>Where data is processed outside Kenya, we implement appropriate safeguards consistent with applicable data protection laws to ensure your data remains secure.</p>
                </section>

                <section id="sec-10" class="section-card">
                    <h2><i class="fas fa-child"></i> 10. Children's Privacy</h2>
                    <p>Our Platform is strictly intended for business usage. We do not knowingly collect personal information from children. Businesses using the Platform remain responsible for ensuring legal compliance for any data entered.</p>
                </section>

                <section id="sec-11" class="section-card">
                    <h2><i class="fas fa-key"></i> 11. Account Security</h2>
                    <p>Users must take proactive measures to maintain security:</p>
                    <ul class="custom-list">
                        <li>Keep passwords confidential and unique.</li>
                        <li>Enable Multi-Factor Authentication (MFA) where available.</li>
                        <li>Log out after sessions, especially on shared devices.</li>
                        <li>Notify us immediately if unauthorized access is suspected.</li>
                    </ul>
                </section>

                <section id="sec-12" class="section-card">
                    <h2><i class="fas fa-exclamation-triangle"></i> 12. Data Breaches</h2>
                    <p>In the event of a verified personal data breach, we will investigate promptly, take steps to contain and remediate the incident, and notify affected users and regulatory authorities as required by law.</p>
                </section>

                <section id="sec-13" class="section-card">
                    <h2><i class="fas fa-plug"></i> 13. Third-Party Services</h2>
                    <p>The Platform may integrate with third parties like <strong>M-Pesa, Banks, Payment Gateways, Google, Microsoft, Email/SMS Gateways, and Delivery Providers</strong>. These entities process data according to their respective privacy policies.</p>
                </section>

                <section id="sec-14" class="section-card">
                    <h2><i class="fas fa-sync-alt"></i> 14. Updates to this Policy</h2>
                    <p>We may update this Privacy Policy periodically. Material updates will be communicated through the Platform or via direct notification.</p>
                </section>

                <section id="sec-15" class="section-card">
                    <h2><i class="fas fa-envelope-open-text"></i> 15. Contact Information</h2>
                    <p>For any privacy inquiries, data requests, or compliance questions, please contact our Data Protection Officer:</p>
                    <div class="contact-box">
                        <h3>Data Protection Officer</h3>
                        <p><strong>{{ config('app.name', 'CraftSalesPOS') }}</strong></p>
                        <p><i class="fas fa-envelope"></i> Email: <a href="mailto:privacy@craftsalespos.co.ke">privacy@craftsalespos.co.ke</a></p>
                        <p><i class="fas fa-phone"></i> Phone: <a href="tel:+254700000000">+254 700 000 000</a></p>
                        <p><i class="fas fa-map-marker-alt"></i> Address: Nairobi, Kenya</p>
                    </div>
                </section>

            </main>
        </div>
    </div>

@endsection

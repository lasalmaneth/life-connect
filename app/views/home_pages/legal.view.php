<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legal Overview | LifeConnect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
</head>
<body>
    <?php include __DIR__ . '/../templates/home_header.view.php'; ?>

    <!-- Hero -->
    <section class="hero page-hero">
        <div class="hero-glass-box container">
            <div class="hero-grid">
            <div class="hero-text">
                <h1>Legal Overview</h1>
                <p>Know your rights, responsibilities, and the law in Sri Lanka.</p>
                <a href="#sections" class="btn-hero"><i class="fas fa-book-open"></i> <span>Read the Guidelines</span></a>
            </div>
            <div class="hero-image-wrap">
                <div class="hero-shape"></div>
                <img src="<?= ROOT ?>/public/assets/images/home-law.jpg" class="hero-img" alt="Legal Overview" />
            </div>
            </div>
        </div>
    </section>

    <main id="sections">
        <section class="faq">
            <div class="container">
                <div class="sec-header">
                    <h2>National Laws & Regulations</h2>
                    <div class="underline"></div>
                    <p>LifeConnect operates in strict adherence to national medical and legal policies.</p>
                </div>
                <div class="faq-list" id="legalAccordion">
                    <!-- 1. Legal Overview -->
                    <div class="faq-item">
                        <button class="faq-q"><span><i class="fas fa-scale-balanced"></i> Legal Overview (Sri Lanka)</span><i class="fa-solid fa-plus"></i></button>
                        <div class="faq-a accord">
                            <ul class="bl">
                                <li>Organ and body donation in Sri Lanka is regulated under the <strong>Human Tissue Transplantation Act (2008, amended 2018)</strong>.</li>
                                <li>Only <strong>authorized hospitals, medical institutions, and registered coordinators</strong> may handle organ transplants.</li>
                                <li>Donors must be <strong>21 years or older</strong> to register legally (for live donation).</li>
                                <li>Legal consent from the donor is <strong>mandatory</strong>. For minors or incapacitated individuals, family or legal guardians must consent.</li>
                                <li>Donation is <strong>completely voluntary</strong>. It is <strong>illegal to sell or buy organs</strong>. Violations carry criminal penalties.</li>
                                <li>Donations require proper <strong>medical, legal, and ethical verification</strong>, including death certificates for deceased donors.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- 2. Donor Rights & Responsibilities -->
                    <div class="faq-item">
                        <button class="faq-q"><span><i class="fas fa-user-shield"></i> Donor Rights & Responsibilities</span><i class="fa-solid fa-plus"></i></button>
                        <div class="faq-a accord">
                            <ul class="bl">
                                <li>Donors retain the <strong>right to withdraw consent</strong> at any time before the procedure.</li>
                                <li>You can specify <strong>faith, cultural preferences, and family wishes</strong> in LifeConnect.</li>
                                <li>Families can be <strong>added as trusted contacts</strong> to support consent verification.</li>
                                <li>Donor data is <strong>secure and confidential</strong>. Only authorized staff can access it.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- 3. Illegal Organ Requests & Public Awareness -->
                    <div class="faq-item">
                        <button class="faq-q"><span><i class="fas fa-ban"></i> Illegal Organ Requests & Public Awareness</span><i class="fa-solid fa-plus"></i></button>
                        <div class="faq-a accord">
                            <div class="note-danger"><i class="fas fa-triangle-exclamation"></i> <span><strong>Public posts or social media requests for organs are illegal</strong> in Sri Lanka.</span></div>
                            <ul class="bl">
                                <li>Only <strong>registered hospitals, transplant coordinators, and LifeConnect Sri Lanka</strong> can manage organ allocation.</li>
                                <li>Unofficial requests risk <strong>legal action, fraud, and medical harm</strong>.</li>
                                <li>Many Sri Lankans are <strong>unaware</strong> of this rule; education is essential.</li>
                                <li>If you see <strong>unauthorized requests</strong>, report them to hospital authorities or LifeConnect Sri Lanka.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- 4. Post-Donation & Family Communication -->
                    <div class="faq-item">
                        <button class="faq-q"><span><i class="fas fa-heart"></i> Post-Donation & Family Communication</span><i class="fa-solid fa-plus"></i></button>
                        <div class="faq-a accord">
                            <ul class="bl">
                                <li>After donation, families receive <strong>appreciation certificates</strong> and <strong>updates</strong> about how organs or tissues were used.</li>
                                <li>For body donations, families are informed of the <strong>educational or research contributions</strong>.</li>
                                <li>Proper family communication ensures <strong>legal compliance and cultural respect</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- 5. Age & Eligibility Guidelines -->
                    <div class="faq-item">
                        <button class="faq-q"><span><i class="fas fa-calendar-check"></i> Age & Eligibility Guidelines</span><i class="fa-solid fa-plus"></i></button>
                        <div class="faq-a accord">
                            <ul class="bl">
                                <li><strong>Minimum age:</strong> 21 years for live donation; younger donors may not legally consent.</li>
                                <li><strong>No maximum age</strong>, but medical evaluation determines eligibility.</li>
                                <li><strong>Health screening</strong> is mandatory for all donors to ensure safety.</li>
                                <li>Deceased donor eligibility depends on <strong>cause of death and organ condition</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- 6. Consent Process -->
                    <div class="faq-item">
                        <button class="faq-q"><span><i class="fas fa-file-signature"></i> Consent Process</span><i class="fa-solid fa-plus"></i></button>
                        <div class="faq-a accord">
                            <ul class="bl">
                                <li>Donor consent must be <strong>explicit, recorded, and legally verifiable</strong>.</li>
                                <li>Consent can be <strong>updated</strong> anytime in LifeConnect.</li>
                                <li>Digital consent forms are linked to the <strong>donor profile</strong>, showing selected organs, witness information, and family approvals.</li>
                                <li>Family members can <strong>co-sign or guide consent</strong> for minors or incapacitated donors.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- 7. Hospital & Legal Verification -->
                    <div class="faq-item">
                        <button class="faq-q"><span><i class="fas fa-hospital"></i> Hospital & Legal Verification</span><i class="fa-solid fa-plus"></i></button>
                        <div class="faq-a accord">
                            <p class="content-p">Hospitals verify <strong>medical suitability, consent, and legal documents</strong> before proceeding.</p>
                            <div class="doc-card">
                                <h4>For deceased donors, required documents include:</h4>
                                <ul>
                                    <li>Death certificate</li>
                                    <li>GN (Grama Niladhari) certificate</li>
                                    <li>Funeral parlor information</li>
                                </ul>
                            </div>
                            <p class="content-p">Hospitals and LifeConnect ensure <strong>compliance with the law and safety protocols</strong>.</p>
                        </div>
                    </div>

                    <!-- 8. Awareness & Education -->
                    <div class="faq-item">
                        <button class="faq-q"><span><i class="fas fa-book-open"></i> Awareness & Education</span><i class="fa-solid fa-plus"></i></button>
                        <div class="faq-a accord">
                            <p class="content-p">LifeConnect educates the public about:</p>
                            <ul class="bl">
                                <li><strong>Legal rights</strong> as donors</li>
                                <li><strong>Safe donation practices</strong></li>
                                <li><strong>Ethical and religious considerations</strong></li>
                            </ul>
                            <div class="res-grid">
                                <div class="res-item"><i class="fas fa-video"></i> Videos and explainers</div>
                                <div class="res-item"><i class="fas fa-file-pdf"></i> PDF guides and forms</div>
                                <div class="res-item"><i class="fas fa-heart"></i> Donor and recipient stories</div>
                                <div class="res-item"><i class="fas fa-chart-line"></i> National transplant statistics</div>
                            </div>
                            <div class="note-info"><i class="fas fa-circle-info"></i> <span>This section addresses the <strong>unawareness of illegal organ requests</strong> and promotes <strong>safe, legal donation practices</strong>.</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../templates/home_footer.view.php'; ?>
    <script>
    // Accordion
    document.querySelectorAll('#legalAccordion .faq-item').forEach(item => {
        item.querySelector('.faq-q').addEventListener('click', () => {
            const open = item.classList.contains('open');
            document.querySelectorAll('#legalAccordion .faq-item').forEach(i => i.classList.remove('open'));
            if (!open) item.classList.add('open');
        });
    });
    </script>
</body>
</html>
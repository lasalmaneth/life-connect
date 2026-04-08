<?php
$pageKey = 'history';
require __DIR__ . '/layout.view.php';
?>

<div class="sec active">
    <!-- Header -->
    <div class="c-hdr" style="margin-bottom: 2rem;">
        <div class="c-ey">History</div>
        <h2>Your Contribution History</h2>
        <p>A complete record of your generosity.</p>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="ch" style="padding: 1.5rem; margin-bottom: 0; border-bottom: 1px solid var(--g200);">
            <div class="ct"><i class="fas fa-history"></i> Past Donations</div>
        </div>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: var(--blue-50); border-bottom: 2px solid var(--blue-100);">
                <tr>
                    <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 700; color: var(--blue-800); text-transform: uppercase;">Date</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 700; color: var(--blue-800); text-transform: uppercase;">Reference</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 700; color: var(--blue-800); text-transform: uppercase;">Amount</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 700; color: var(--blue-800); text-transform: uppercase;">Status</th>
                    <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 700; color: var(--blue-800); text-transform: uppercase; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody id="donationTableBody">
                <?php if (!empty($history)): ?>
                    <?php foreach ($history as $index => $row): ?>
                        <tr style="border-bottom: 1px solid var(--g200); transition: background 0.2s;" onmouseover="this.style.background='var(--g50)'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1rem 1.5rem; font-size: 0.875rem; font-weight: 600; color: var(--slate);"><?= date('M d, Y', strtotime($row->created_at)) ?></td>
                            <td style="padding: 1rem 1.5rem; font-size: 0.85rem; color: var(--g500); font-family: monospace;">#<?= str_pad($row->id, 8, '0', STR_PAD_LEFT) ?></td>
                            <td style="padding: 1rem 1.5rem; font-size: 0.875rem; font-weight: 700; color: var(--blue-600);">LKR <?= number_format($row->amount, 2) ?></td>
                            <td style="padding: 1rem 1.5rem;">
                                <?php if ($row->status === 'SUCCESS'): ?>
                                    <span class="badge ba"><i class="fas fa-check-circle" style="font-size: 0.7rem;"></i> Success</span>
                                <?php else: ?>
                                    <span class="badge bd"><?= htmlspecialchars($row->status) ?></span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: right;">
                                <button onclick='viewCertificate(<?= json_encode($row) ?>)' class="btn btn-g btn-sm">
                                    <i class="fas fa-certificate"></i> View Certificate
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding: 3rem; text-align: center; color: var(--g400);">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.5;"></i><br>
                            No donation history found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Appreciation Certificate Modal -->
<div id="certificateModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; padding: 2rem; backdrop-filter: blur(2px);">
    <div style="background: white; width: 100%; max-width: 800px; border-radius: var(--r); position: relative; box-shadow: 0 25px 50px rgba(0,0,0,0.25); overflow: hidden; animation: slideD 0.3s ease;">
        <button onclick="closeCertificate()" style="position: absolute; top: 1.5rem; right: 1.5rem; background: var(--g100); border: none; width: 32px; height: 32px; border-radius: 50%; font-size: 1.2rem; cursor: pointer; color: var(--g500); z-index: 10; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">&times;</button>
        
        <div id="certificateContent" style="padding: 4rem; text-align: center; background: #fffaf0; border: 15px solid #fff; outline: 1px solid var(--g200); margin: 0;">
            <div style="border: 2px solid var(--gold); padding: 3rem; position: relative; background: #fff;">
                <!-- Corner decorations -->
                <div style="position: absolute; top: -10px; left: -10px; width: 20px; height: 20px; border: 2px solid var(--gold); background: #fff;"></div>
                <div style="position: absolute; top: -10px; right: -10px; width: 20px; height: 20px; border: 2px solid var(--gold); background: #fff;"></div>
                <div style="position: absolute; bottom: -10px; left: -10px; width: 20px; height: 20px; border: 2px solid var(--gold); background: #fff;"></div>
                <div style="position: absolute; bottom: -10px; right: -10px; width: 20px; height: 20px; border: 2px solid var(--gold); background: #fff;"></div>
                
                <h1 style="font-family: 'Lora', serif; font-size: 3rem; color: var(--navy); margin-bottom: 0.5rem; letter-spacing: 0.02em;">Certificate of Appreciation</h1>
                <p style="font-size: 1.1rem; color: var(--g500); margin-bottom: 3rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600;">This certificate is proudly presented to</p>
                
                <h2 id="certName" style="font-family: 'Lora', serif; font-size: 2.25rem; color: var(--blue-800); margin-bottom: 2rem; display: inline-block; padding: 0 2rem 0.5rem; border-bottom: 2px solid var(--gold); font-style: italic;">
                    <?= htmlspecialchars($donor_data['full_name']) ?>
                </h2>
                
                <p style="max-width: 600px; margin: 0 auto; line-height: 1.8; color: var(--g700); font-size: 1.1rem;">
                    In recognition of your generous contribution of <strong style="color: var(--blue-600);" id="certAmount">LKR 0.00</strong> 
                    which was received on <strong id="certDate">---</strong>. <br>
                    Your monumental support directly empowers our mission <br>of saving lives and providing hope.
                </p>
                
                <div style="margin-top: 4rem; display: flex; justify-content: space-around; align-items: flex-end;">
                    <div style="text-align: center; width: 200px;">
                        <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height: 50px; opacity: 0.9;">
                    </div>
                    <div style="text-align: center; width: 200px;">
                        <div style="font-family: 'Brush Script MT', cursive; font-size: 1.8rem; margin-bottom: 0.5rem; border-bottom: 1px solid var(--slate); color: var(--navy);">Dr. Sarah Perera</div>
                        <div style="font-size: 0.75rem; color: var(--g500); text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700;">Director, LifeConnect</div>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding: 1.5rem; background: var(--g50); text-align: right; border-top: 1px solid var(--g200);">
            <button onclick="printCertificate()" class="btn btn-p">
                <i class="fas fa-print"></i> Download / Print
            </button>
        </div>
    </div>
</div>

<script>
function viewCertificate(donation) {
    document.getElementById('certAmount').textContent = 'LKR ' + parseFloat(donation.amount).toLocaleString(undefined, {minimumFractionDigits: 2});
    document.getElementById('certDate').textContent = new Date(donation.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    document.getElementById('certificateModal').style.display = 'flex';
}

function closeCertificate() {
    document.getElementById('certificateModal').style.display = 'none';
}

function printCertificate() {
    const printContent = document.getElementById('certificateContent').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <style>
            @media print {
                @page { size: landscape; margin: 0; }
                body { padding: 40px; background: white; -webkit-print-color-adjust: exact; }
            }
        </style>
        ${printContent}
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload(); 
}

window.onclick = function(event) {
    const modal = document.getElementById('certificateModal');
    if (event.target == modal) closeCertificate();
}
</script>

    </main>
</div> <!-- .wrap -->
</body>
</html>

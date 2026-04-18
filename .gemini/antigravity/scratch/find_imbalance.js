const fs = require('fs');
const content = fs.readFileSync('app/controllers/admin/DonationAdminController.php', 'utf8');
const lines = content.split('\n');
let bal = 0;
for (let i = 0; i < lines.length; i++) {
    const line = lines[i];
    for (let char of line) {
        if (char === '{') bal++;
        if (char === '}') bal--;
    }
    if (bal <= 0 && i > 12) {
        console.log(`Zero/Negative balance at line ${i + 1}: ${line.trim()}`);
        process.exit(0);
    }
}
console.log('Balanced or never hit zero');

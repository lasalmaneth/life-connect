$content = [IO.File]::ReadAllText('app/controllers/admin/DonationAdminController.php')
$openCount = 0
$closeCount = 0
foreach ($char in $content.ToCharArray()) {
    if ($char -eq '{') { $openCount++ }
    if ($char -eq '}') { $closeCount++ }
}
Write-Host "Open: $openCount"
Write-Host "Close: $closeCount"

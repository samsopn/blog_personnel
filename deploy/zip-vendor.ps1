# Crée deploy/vendor.zip pour upload InfinityFree (extraction dans blog_personnel_2/)
$racine = Split-Path $PSScriptRoot -Parent
$zip = Join-Path $racine "deploy\vendor.zip"

if (Test-Path $zip) { Remove-Item $zip -Force }

Write-Host "Compression de vendor/ (quelques minutes)..." -ForegroundColor Cyan
Compress-Archive -Path "$racine\vendor" -DestinationPath $zip -CompressionLevel Optimal

$mo = [math]::Round((Get-Item $zip).Length / 1MB, 1)
Write-Host "OK : $zip ($mo Mo)" -ForegroundColor Green
Write-Host ""
Write-Host "Sur InfinityFree :" -ForegroundColor Yellow
Write-Host "1. Uploadez vendor.zip dans htdocs/blog_personnel_2/"
Write-Host "2. Extrayez dans htdocs/blog_personnel_2/ (vous obtenez le dossier vendor/)"
Write-Host "3. Verifiez : blog_personnel_2/vendor/composer/autoload_real.php"
Write-Host "4. Supprimez vendor.zip"

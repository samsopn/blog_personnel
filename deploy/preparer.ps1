# Prépare deploy/infinity/htdocs/ prêt à uploader sur InfinityFree.
# Usage : powershell -ExecutionPolicy Bypass -File deploy\preparer.ps1

$ErrorActionPreference = "Stop"
$racine = Split-Path $PSScriptRoot -Parent

Set-Location $racine

Write-Host "Build des assets..." -ForegroundColor Cyan
npm.cmd run build

$destination = Join-Path $racine "deploy\infinity\htdocs"
if (Test-Path $destination) {
    Remove-Item $destination -Recurse -Force
}

New-Item -ItemType Directory -Path $destination -Force | Out-Null
New-Item -ItemType Directory -Path "$destination\blog_personnel" -Force | Out-Null
New-Item -ItemType Directory -Path "$destination\storage" -Force | Out-Null

Write-Host "Copie des fichiers web (htdocs)..." -ForegroundColor Cyan
Copy-Item "$racine\index.php" $destination
Copy-Item "$racine\.htaccess" $destination
Copy-Item "$racine\public\build" "$destination\build" -Recurse

Write-Host "Copie de Laravel (blog_personnel)..." -ForegroundColor Cyan
robocopy $racine "$destination\blog_personnel" /E /XD node_modules .git tests deploy "deploy\infinity" /XF .env .env.example .env.infinity.example /NFL /NDL /NJH /NJS /nc /ns /np | Out-Null

Copy-Item "$racine\deploy\migrer.php" "$destination\blog_personnel\migrer.php"

Write-Host ""
Write-Host "OK : $destination" -ForegroundColor Green
Write-Host "Uploadez le contenu de deploy\infinity\htdocs\ vers htdocs/ sur InfinityFree." -ForegroundColor Yellow

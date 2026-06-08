$phpPath = 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64'
$composerPath = 'C:\laragon\bin\composer'
$userPath = [Environment]::GetEnvironmentVariable('Path','User')
if (-not $userPath) { $userPath = '' }
if ($userPath.IndexOf($phpPath, [System.StringComparison]::OrdinalIgnoreCase) -lt 0) {
    $userPath = $userPath + ';' + $phpPath
    Write-Host "Added php to user PATH"
} else {
    Write-Host "php already in user PATH"
}
if ($userPath.IndexOf($composerPath, [System.StringComparison]::OrdinalIgnoreCase) -lt 0) {
    $userPath = $userPath + ';' + $composerPath
    Write-Host "Added composer to user PATH"
} else {
    Write-Host "composer already in user PATH"
}
[Environment]::SetEnvironmentVariable('Path',$userPath,'User')
# Update the current session PATH by combining user and machine path
$machinePath = [Environment]::GetEnvironmentVariable('Path','Machine')
$env:Path = ($userPath + ';' + $machinePath).Trim(';')
# Output PHP version and selected extensions
Write-Host '--- php -v ---'
& php -v
Write-Host '--- php -m (filtered) ---'
try {
    & php -m | Select-String 'zip|mbstring|pdo_mysql|openssl|pdo|sqlite3' -AllMatches | ForEach-Object { $_.Line }
} catch {
    Write-Host '(could not list modules)'
}
# Show composer version if available
Write-Host '--- composer version ---'
try {
    & composer --version
} catch {
    Write-Host '(composer not callable via composer command)'
    if (Test-Path "$composerPath\\composer.phar") {
        & "$phpPath\\php.exe" "$composerPath\\composer.phar" --version
    }
}

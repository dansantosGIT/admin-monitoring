$phpRoot = 'C:\laragon\bin\php'
$composerPath = 'C:\laragon\bin\composer'
$phpPath = Get-ChildItem $phpRoot -Directory |
    Sort-Object Name -Descending |
    Select-Object -First 1

if (-not $phpPath) {
    throw "No Laragon PHP installation found under $phpRoot"
}

$phpPath = $phpPath.FullName
$phpExe = Join-Path $phpPath 'php.exe'
$composerPhar = Join-Path $composerPath 'composer.phar'

function Add-PathEntry {
    param(
        [string]$CurrentPath,
        [string]$Entry
    )

    $parts = @($CurrentPath -split ';' | Where-Object { $_ })
    if ($parts -notcontains $Entry) {
        $parts += $Entry
    }

    return ($parts -join ';')
}

$userPath = [Environment]::GetEnvironmentVariable('Path','User')
if (-not $userPath) { $userPath = '' }
if ($userPath.IndexOf($phpPath, [System.StringComparison]::OrdinalIgnoreCase) -lt 0) {
    $userPath = Add-PathEntry -CurrentPath $userPath -Entry $phpPath
    Write-Host "Added php to user PATH"
} else {
    Write-Host "php already in user PATH"
}
if ($userPath.IndexOf($composerPath, [System.StringComparison]::OrdinalIgnoreCase) -lt 0) {
    $userPath = Add-PathEntry -CurrentPath $userPath -Entry $composerPath
    Write-Host "Added composer to user PATH"
} else {
    Write-Host "composer already in user PATH"
}
[Environment]::SetEnvironmentVariable('Path',$userPath,'User')
# Update the current session PATH by combining user and machine path
$machinePath = [Environment]::GetEnvironmentVariable('Path','Machine')
$env:Path = (@($userPath, $machinePath) | Where-Object { $_ }) -join ';'
# Output PHP version and selected extensions
Write-Host '--- php -v ---'
& $phpExe -v
Write-Host '--- php -m (filtered) ---'
try {
    & $phpExe -m | Select-String 'zip|mbstring|pdo_mysql|openssl|pdo|sqlite3' -AllMatches | ForEach-Object { $_.Line }
} catch {
    Write-Host '(could not list modules)'
}
# Show composer version if available
Write-Host '--- composer version ---'
try {
    & composer --version
} catch {
    Write-Host '(composer not callable via composer command)'
    if (Test-Path $composerPhar) {
        & $phpExe $composerPhar --version
    }
}

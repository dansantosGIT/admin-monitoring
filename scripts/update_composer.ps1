$target = 'C:\laragon\bin\composer\composer.phar'
$backup = 'C:\laragon\bin\composer\composer.phar.bak'
if (Test-Path $target) {
    Copy-Item $target $backup -Force
    Write-Host 'Backed up existing composer.phar to composer.phar.bak'
}
try {
    Invoke-WebRequest -Uri 'https://getcomposer.org/composer.phar' -OutFile $target -UseBasicParsing -ErrorAction Stop
    Write-Host 'Downloaded composer.phar'
    & 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe' $target --version
} catch {
    Write-Error "Composer download failed: $($_.Exception.Message)"
}

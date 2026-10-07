[CmdletBinding()]
param(
    [switch]$SharedHosting,
    [switch]$ExportDatabase,
    [string]$MysqlDump = 'C:\xampp\mysql\bin\mysqldump.exe',
    [string]$DatabaseHost = '127.0.0.1',
    [int]$DatabasePort = 3306,
    [string]$DatabaseUser = 'root',
    [string]$DatabaseName = 'electric_company',
    [switch]$AskDatabasePassword
)

$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$outputRoot = Join-Path $projectRoot 'writable\deployment'
$buildId = (Get-Date -Format 'yyyyMMdd-HHmmss') + '-' + [guid]::NewGuid().ToString('N').Substring(0, 8)
$outputDirectory = Join-Path $outputRoot $buildId
$null = New-Item -ItemType Directory -Path $outputDirectory
$archiveName = if ($SharedHosting) { 'demoelectric-shared-hosting.zip' } else { 'demoelectric-site.zip' }
$archivePath = Join-Path $outputDirectory $archiveName

Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem
$archive = [System.IO.Compression.ZipFile]::Open($archivePath, [System.IO.Compression.ZipArchiveMode]::Create)
try {
    # Explicitly include application files, never local credentials or runtime data.
    $folders = @('app', 'public', 'system')
    if (Test-Path -LiteralPath (Join-Path $projectRoot 'vendor')) {
        $folders += 'vendor'
    }
    foreach ($folder in $folders) {
        foreach ($file in Get-ChildItem -LiteralPath (Join-Path $projectRoot $folder) -Recurse -File -Force) {
            $relative = $file.FullName.Substring($projectRoot.Length + 1).Replace('\', '/')
            $null = [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($archive, $file.FullName, $relative)
        }
    }
    foreach ($relative in @('spark', 'LICENSE', 'composer.json', 'README.md', 'env.production.example', 'writable/.htaccess', 'writable/index.html')) {
        $null = [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($archive, (Join-Path $projectRoot $relative), $relative)
    }
    if ($SharedHosting) {
        foreach ($relative in @('index.php', '.htaccess')) {
            $null = [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($archive, (Join-Path $projectRoot $relative), $relative)
        }
    }
    foreach ($folder in @('cache', 'logs', 'session', 'uploads')) {
        # Some hosting ZIP extractors skip empty directories. A placeholder
        # ensures these runtime directories exist after browser-based uploads.
        $null = [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile(
            $archive, (Join-Path $projectRoot 'writable/index.html'), "writable/$folder/index.html"
        )
    }
} finally {
    $archive.Dispose()
}

Write-Output "Website package: $archivePath"
if ($SharedHosting) {
    Write-Output 'Upload the extracted contents to htdocs/. Include the hidden .htaccess files.'
} else {
    Write-Output 'The hosting document root must point to the extracted public/ directory.'
}

if ($ExportDatabase) {
    if (-not (Test-Path -LiteralPath $MysqlDump -PathType Leaf)) {
        throw "mysqldump was not found at $MysqlDump. The website package is ready, but the database was not exported."
    }
    $databasePath = Join-Path $outputDirectory 'electric-company.sql'
    $dumpArguments = @(
        "--host=$DatabaseHost", "--port=$DatabasePort", "--user=$DatabaseUser",
        '--single-transaction', '--quick', '--skip-lock-tables', '--skip-add-locks',
        '--skip-add-drop-table', '--no-tablespaces', '--default-character-set=utf8mb4',
        "--result-file=$databasePath"
    )
    if ($AskDatabasePassword) {
        $dumpArguments += '--password'
    }
    $dumpArguments += $DatabaseName
    & $MysqlDump @dumpArguments
    if ($LASTEXITCODE -ne 0) {
        throw 'Database export failed. Do not import the incomplete SQL file; check MySQL and rerun the script.'
    }
    Write-Output "Private database export: $databasePath"
    Write-Output 'Import the SQL into a NEW, EMPTY hosted database. It includes account password hashes; keep it outside public/ and Git.'
}

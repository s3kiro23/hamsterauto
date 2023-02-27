# Activer les fonctionnalités nécessaires pour WSL2
Enable-WindowsOptionalFeature -Online -FeatureName VirtualMachinePlatform,Microsoft-Windows-Subsystem-Linux -NoRestart

# Définir WSL2 comme version par défaut pour les nouvelles distributions Linux installées.
wsl --set-default-version 2

# Télécharger et installer Docker Desktop
Invoke-WebRequest -Uri https://desktop.docker.com/win/stable/Docker%20Desktop%20Installer.exe -OutFile "$env:USERPROFILE\Downloads\Docker Desktop Installer.exe"
Start-Process -FilePath "$env:USERPROFILE\Downloads\Docker Desktop Installer.exe" -ArgumentList "/S"

# Ajouter l'utilisateur courant au groupe "docker"
$computerName = $env:COMPUTERNAME
$userName = $env:USERNAME
Add-LocalGroupMember -Group "docker" -Member "$computerName\$userName"

# Ajouter le chemin d'accès à Docker à la variable d'environnement PATH
$dockerPath = "C:\Program Files\Docker\Docker\resources\bin"
if (-not (Test-Path -Path $dockerPath)) {
    $dockerPath = "C:\Program Files (x86)\Docker\Docker\resources\bin"
}
$env:Path += ";$dockerPath"
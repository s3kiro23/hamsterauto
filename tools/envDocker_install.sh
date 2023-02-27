# Use Windows as base image
FROM mcr.microsoft.com/windows:20H2

# Install WSL 2
RUN dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart
RUN dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart
COPY wsl_update_x64.msi C:\\wsl_update_x64.msi
RUN powershell.exe Start-Process msiexec.exe -ArgumentList '/i', 'C:\wsl_update_x64.msi', '/quiet', '/norestart' -NoNewWindow -Wait
RUN wsl --set-default-version 2

# DL Docker Desktop
ADD https://desktop.docker.com/win/stable/amd64/Docker%20Desktop%20Installer.exe C:\\Temp\\DockerDesktopInstaller.exe

# Install Docker Desktop
RUN powershell.exe Start-Process C:\\Temp\\DockerDesktopInstaller.exe -ArgumentList '--quiet' -NoNewWindow -Wait
RUN del C:\\Temp\\DockerDesktopInstaller.exe

# Ajouter Docker à la variable PATH
RUN setx /M PATH "%PATH%;C:\Program Files\Docker"

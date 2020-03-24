@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../spiral/roadrunner/bin/rr
php "%BIN_TARGET%" %*

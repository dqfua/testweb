# PHP 5.6.40 NTS Installation and Configuration Guide for Windows Server 2012 R2

## Prerequisites
1. Ensure that Windows Server 2012 R2 is installed and configured.
2. Have administrative access to the server.
3. Download the PHP 5.6.40 NTS (Non-Thread Safe) zip file from the official PHP website.

## Installation Steps

### Step 1: Download PHP
- Visit the [official PHP downloads page](https://windows.php.net/download/) and choose PHP 5.6.40 NTS.
- Choose the appropriate package (x64 or x86) based on your system architecture.

### Step 2: Extract PHP
- Extract the downloaded zip file to `C:\php` (you may need to create this directory).

### Step 3: Configure Environment Variables
- Right-click on 'This PC' or 'Computer', select 'Properties'.
- Click on 'Advanced system settings' and then 'Environment Variables'.
- Under 'System variables', find the `Path` variable and select it, then click 'Edit'.
- Add `C:\php` to the list of paths.

### Step 4: Configure PHP INI
- Rename `php.ini-development` to `php.ini` in the `C:\php` directory.
- Open the `php.ini` file in a text editor and configure the following settings according to your needs:
  - `extension_dir = "ext"`
  - Uncomment and set necessary extensions (e.g., `extension=mysqli`).

### Step 5: Configure IIS (optional)
- Install IIS if not already installed (Server Manager -> Add roles and features).
- Use the Web Platform Installer to install the PHP Manager for IIS.
- In IIS, add a new Handler Mapping for the PHP executable:
  - Request path: `*.php`
  - Executable: `C:\php\php-cgi.exe`
  - Name: `PHP FastCGI`

### Step 6: Verify Installation
- Create a new PHP file in your web root directory (e.g., `C:\inetpub\wwwroot\info.php`):
```php
<?php
phpinfo();
?>
```
- Open a web browser and navigate to `http://localhost/info.php` to confirm that PHP is working.

## Conclusion
You have successfully installed and configured PHP 5.6.40 NTS on Windows Server 2012 R2.
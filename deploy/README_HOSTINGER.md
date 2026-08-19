# Hostinger VPS Deployment

The deployment script is:

```bash
deploy/hostinger_publish_qms.sh
```

Use it on the VPS after confirming:

- `qms.ysaidea.com` points to the VPS
- the web server is configured for the domain
- PHP, Composer, Node/npm, Git, and MySQL/MariaDB are installed
- database credentials are ready
- Miniworld path is correct

Example:

```bash
export HOSTINGER_USER="your-hostinger-user"
export DB_DATABASE="qms_database"
export DB_USERNAME="qms_db_user"
export DB_PASSWORD="qms_db_password"
export MINIWORLD_DB_DATABASE="miniworld_database"
export MINIWORLD_DB_USERNAME="miniworld_db_user"
export MINIWORLD_DB_PASSWORD="miniworld_db_password"
bash deploy/hostinger_publish_qms.sh
```

The script backs up Miniworld files before switching the QMS domain public folder to Laravel `public`.

Production requirements:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://qms.ysaidea.com`
- `QUEUE_CONNECTION=database`
- database credentials must be created before deployment

Queue worker:

```bash
cd /home/your-hostinger-user/domains/qms.ysaidea.com/current
php artisan queue:work --queue=default --sleep=3 --tries=3 --timeout=90
```

Scheduler cron:

```bash
* * * * * cd /home/your-hostinger-user/domains/qms.ysaidea.com/current && php artisan schedule:run >> /dev/null 2>&1
```

Rollback:

```bash
ln -sfn /home/your-hostinger-user/domains/qms.ysaidea.com/releases/PREVIOUS_RELEASE /home/your-hostinger-user/domains/qms.ysaidea.com/current
ln -sfn /home/your-hostinger-user/domains/qms.ysaidea.com/current/public /home/your-hostinger-user/domains/qms.ysaidea.com/public_html
php artisan queue:restart
sudo systemctl reload php8.4-fpm
sudo systemctl reload nginx
```

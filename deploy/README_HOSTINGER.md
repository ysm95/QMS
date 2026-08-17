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

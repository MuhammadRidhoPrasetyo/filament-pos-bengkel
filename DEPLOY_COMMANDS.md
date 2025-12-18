# Deployment Commands — Filament POS Bengkel

Dokumen ini berisi perintah berurutan (copy-paste) untuk men-deploy aplikasi Dockerized Laravel pada hosting/VM. Sesuaikan placeholder (`your-domain.com`, `/home/youruser/...`, credentials) sebelum menjalankan.

> Prasyarat singkat
> - Server Linux (Debian/Ubuntu atau Alpine/CentOS), akses root atau user dengan hak Docker.
> - `docker` dan `docker compose` (v2) sudah terpasang.
> - Domain DNS sudah mengarah ke server.

---

## 1. Persiapan server (install Docker & Docker Compose)

Contoh untuk Ubuntu/Debian:

```bash
# update paket
sudo apt update
sudo apt upgrade -y

# instal paket pendukung
sudo apt install -y ca-certificates curl gnupg lsb-release

# tambahkan GPG key Docker
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg

# tambahkan repo docker
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# verifikasi
sudo docker version
sudo docker compose version
```

Catatan: Untuk distribusi lain, gunakan instruksi resmi Docker.

---

## 2. Clone repository & siapkan branch

```bash
cd /home/youruser
git clone https://github.com/MuhammadRidhoPrasetyo/filament-pos-bengkel.git
cd filament-pos-bengkel
git checkout main
```

---

## 3. Buat dan konfigurasi `.env`

```bash
# jika ada .env.example
cp .env.example .env
nano .env
```

- Pastikan variabel DB disesuaikan:
  - `DB_HOST=db`
  - `DB_PORT=3306`
  - `DB_DATABASE=filament`
  - `DB_USERNAME=filament`
  - `DB_PASSWORD=secret`
- Pastikan `APP_URL=https://your-domain.com` dan `APP_ENV=production` (atau `local` saat testing).

---

## 4. Jalankan stack Docker (build & detached)

```bash
docker compose up --build -d
```

- Perintah ini membangun image `app` dari `Dockerfile` dan menjalankan semua service.
- Cek status container:

```bash
docker compose ps
```

---

## 5. Install Composer dependencies (di container `app`)

```bash
docker compose exec app composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
```

Jika ingin memaksa reinstall vendor (mis. saat pertama kali tanpa memount vendor):

```bash
docker compose exec app composer install
```

---

## 6. Generate key & konfigurasi aplikasi

```bash
docker compose exec app php artisan key:generate --ansi
```

---

## 7. Set file permissions

```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
```

Jika Anda mengalami masalah permission pada host, sesuaikan UID/GID atau jalankan chown dari host.

---

## 8. Run migrations & seed (opsional)

> HATI-HATI: `--force` wajib di lingkungan non-interactive (production)

```bash
docker compose exec app php artisan migrate --force
# jika perlu seed
docker compose exec app php artisan db:seed --force
```

---

## 9. Setup storage symlink

```bash
docker compose exec app php artisan storage:link --force
```

---

## 10. Cache & optimize

```bash
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

---

## 11. Build frontend assets (production)

Jalankan service `node` yang sudah ada di `docker-compose.yml`:

```bash
docker compose run --rm node npm ci
docker compose run --rm node npm run build
```

- Alternatif: jalankan `npm ci && npm run build` di host jika Anda tidak ingin container node.

---

## 12. Queue worker (production)

Direkomendasikan menambahkan layanan `worker` ke `docker-compose.prod.yml`. Jika ingin menjalankan container terpisah sementara:

```bash
docker compose run -d --name filament_worker app php artisan queue:work --sleep=3 --tries=3 --timeout=90
```

Untuk pengelolaan proses yang lebih baik, gunakan `supervisord` atau `systemd` (contoh di bawah).

---

## 13. Scheduler (cron)

Tambahkan baris crontab di host (user yang menjalankan docker):

```bash
crontab -e
# tambahkan:
* * * * * cd /home/youruser/filament-pos-bengkel && /usr/bin/docker compose exec -T app php artisan schedule:run >> /dev/null 2>&1
```

Catatan: Pastikan path ke `docker compose` atau gunakan full path (`/usr/bin/docker compose` atau `/usr/bin/docker-compose`).

---

## 14. Logs & troubleshooting

```bash
# semua logs
docker compose logs -f

# logs specific service
docker compose logs -f app
```

Jika container `app` tidak mau start: periksa `docker compose logs app` dan `docker compose exec app ls -la`.

---

## 15. Redeploy / update code

Langkah umum saat ada update:

```bash
# di dalam repo
git pull origin main
# tarik image (jika ada image remote)
docker compose pull
# rebuild & restart
docker compose up -d --build --remove-orphans

# ulangi command penting:
docker compose exec app composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
docker compose exec app php artisan migrate --force
docker compose exec app php artisan config:cache
```

---

## 16. Backup database (MariaDB)

Contoh: dump ke file di host

```bash
mkdir -p ~/backups
docker exec filament_db sh -c 'exec mysqldump -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' > ~/backups/filament_db_$(date +%F_%H%M).sql
```

- Simpan backup di lokasi aman (off-site atau object storage).

---

## 17. SSL — Let's Encrypt (sederhana via host nginx + certbot)

Saran: atur TLS pada host sebagai reverse-proxy, lalu forward ke container `web` (HTTP). Contoh menggunakan `certbot` pada host (Ubuntu):

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com -m your-email@example.com --agree-tos --non-interactive
```

Alternatif: gunakan stack `nginx-proxy` + `letsencrypt-nginx-proxy-companion` dalam container. Saya bisa bantu konfigurasi jika diinginkan.

---

## 18. Systemd unit (opsional) — Start stack on boot

Contoh `systemd` service untuk menjalankan `docker compose` pada boot. Buat file `/etc/systemd/system/filament-stack.service`:

```ini
[Unit]
Description=Filament POS Docker Compose
Requires=docker.service
After=docker.service

[Service]
Type=oneshot
RemainAfterExit=yes
WorkingDirectory=/home/youruser/filament-pos-bengkel
ExecStart=/usr/bin/docker compose up -d
ExecStop=/usr/bin/docker compose down
TimeoutStartSec=0

[Install]
WantedBy=multi-user.target
```

Enable & start:

```bash
sudo systemctl daemon-reload
sudo systemctl enable filament-stack.service
sudo systemctl start filament-stack.service
```

---

## 19. Rollback (contoh sederhana)

Jika deployment baru rusak, Anda bisa menjalankan:

```bash
# hentikan stack
docker compose down
# restore DB dari backup (lihat point 16)
# jalankan versi sebelumnya (checkout tag/commit)
git checkout <previous-tag-or-commit>
docker compose up -d --build
```

---

## 20. Checklist keamanan & production notes

- Pastikan `.env` tidak di-commit dan hanya disimpan di server.
- Gunakan password DB kuat dan batasi akses jaringan ke DB jika perlu.
- Aktifkan firewall (ufw) hanya membuka port `80`/`443` (atau port internal jika reverse-proxy di host).
- Setup monitoring & automatic backups.

---

## 21. Cara mengunduh file ini

- File ini disimpan di repository sebagai `DEPLOY_COMMANDS.md`.
- Jika repo berada di GitHub publik, Anda dapat download versi raw langsung:

```bash
# contoh (ganti user/repo/path jika perlu)
curl -L -o DEPLOY_COMMANDS.md \
  https://raw.githubusercontent.com/MuhammadRidhoPrasetyo/filament-pos-bengkel/main/DEPLOY_COMMANDS.md
```

---

Jika Anda ingin, saya bisa:
- Menambahkan `docker-compose.prod.yml` khusus produksi dengan service `worker`, healthchecks, dan resource limits.
- Membuat contoh `nginx-proxy` + `letsencrypt` compose stack agar sertifikat otomatis.
- Menyusun script deploy otomatis (`deploy.sh`) yang menjalankan langkah-langkah penting.

Tandai mana yang mau saya lanjutkan.

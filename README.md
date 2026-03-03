# Templating-laravel
...existing code...
# Laravel App

Berikut adalah langkah-langkah untuk membuat project Laravel dari awal dan cara melakukan clone dengan git.

## Membuat Project Laravel dari 0

1. **Pastikan Composer sudah terinstall**
	- Download dan install Composer dari https://getcomposer.org/

2. **Buat Project Laravel Baru**
	```bash
	composer create-project laravel/laravel nama_project
	```

3. **Masuk ke Folder Project**
	```bash
	cd nama_project
	```

4. **Copy file .env**
	```bash
	cp .env.example .env
	```

5. **Generate Application Key**
	```bash
	php artisan key:generate
	```

6. **Atur Konfigurasi Database di file .env**
	- Edit file `.env` dan sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD

7. **Jalankan Migrasi Database**
	```bash
	php artisan migrate
	```

8. **Jalankan Server Lokal**
	```bash
	php artisan serve
	```
	- Akses aplikasi di http://localhost:8000

## Langkah-langkah Git Clone

1. **Clone Repository**
	```bash
	git clone <url-repo-git>
	```

2. **Masuk ke Folder Project**
	```bash
	cd nama_project
	```

3. **Install Dependency**
	```bash
	composer install
	npm install
	```

4. **Copy file .env**
	```bash
	cp .env.example .env
	```

5. **Generate Application Key**
	```bash
	php artisan key:generate
	```

6. **Atur Konfigurasi Database di file .env**
	- Edit file `.env` dan sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD

7. **Jalankan Migrasi Database**
	```bash
	php artisan migrate
	```

8. **Jalankan Server Lokal**
	```bash
	php artisan serve
	```
	- Akses aplikasi di http://localhost:8000

---

Silakan sesuaikan langkah-langkah di atas sesuai kebutuhan project Anda.

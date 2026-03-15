Here is the complete, ready-to-copy `README.md` file. You can paste this directly into your project.

```markdown
# Mini Social Network

A clean, scalable social media platform built with Laravel, React, and Inertia.js. It features a fully containerized local development environment with secure, S3-compatible media storage using MinIO, and a highly optimized, optimistic UI interaction system.

## 🚀 Features

* **Modern SPA Experience:** Powered by React and Inertia.js for lightning-fast, page-reload-free navigation.
* **Rich Media Uploads:** Users can post text, images, and videos securely stored on a local MinIO S3 bucket.
* **Optimistic UI Interactions:** The Like/Unlike system updates the UI instantly before the server even responds, making the app feel incredibly fast.
* **Scalable Backend:** Utilizes Laravel's advanced Eloquent querying (`withCount`, `withExists`) to prevent N+1 performance bottlenecks.
* **Fully Containerized:** Runs entirely inside Docker using Laravel Sail for a consistent development environment across any machine.

## 🛠️ Tech Stack

* **Backend:** Laravel (PHP)
* **Frontend:** React.js, Inertia.js, Tailwind CSS
* **Database:** MySQL
* **File Storage:** MinIO (S3-Compatible Object Storage)
* **DevOps:** Docker, Laravel Sail

## ⚙️ Prerequisites

Before you begin, ensure you have the following installed on your machine:
* [Docker Desktop](https://www.docker.com/products/docker-desktop) (Running)
* [Composer](https://getcomposer.org/)
* [Node.js](https://nodejs.org/) & NPM

## 🚀 Installation & Setup

### 1. Clone the repository
```bash
git clone [https://github.com/mehedi250/mini-social-network.git](https://github.com/mehedi250/mini-social-network.git)
cd mini-social-network

```

### 2. Install PHP Dependencies

```bash
composer install

```

### 3. Configure Environment Variables

Copy the `.env.example` file and configure your S3/MinIO settings:

```bash
cp .env.example .env

```

Ensure the following variables are set exactly like this in your `.env` file for local development:

```env
AWS_ACCESS_KEY_ID=sail
AWS_SECRET_ACCESS_KEY=password
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=local
AWS_ENDPOINT=http://minio:9000
AWS_URL=http://localhost:9000/local
AWS_USE_PATH_STYLE_ENDPOINT=true

```

### 4. Boot up Docker (Laravel Sail)

Start the containers in the background:

```bash
./vendor/bin/sail up -d

```

### 5. Generate Application Key & Run Migrations

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate

```

### 6. Configure the MinIO Storage Bucket

Run the following commands to create your local storage bucket and set it to public so your React frontend can display the images:

```bash
# Authenticate the MinIO client
./vendor/bin/sail exec minio mc alias set myminio http://localhost:9000 sail password

# Create the bucket (if it doesn't exist)
./vendor/bin/sail exec minio mc mb myminio/local || true

# Set the bucket policy to public read
./vendor/bin/sail exec minio mc anonymous set download myminio/local

```

### 7. Compile Frontend Assets

In a separate terminal window, install your NPM packages and start the Vite compiler:

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev

```

## 🌐 Accessing the Application

* **Main Application:** `http://localhost`
* **MinIO Admin Console:** `http://localhost:8900` (User: `sail` | Pass: `password`)

## 👨‍💻 Author

**Md. Mehedi Hasan Shawon**

* Software Engineer | Laravel & Backend Developer

---

```

Would you like to move on to building the Comment models and the frontend comment section next?

```
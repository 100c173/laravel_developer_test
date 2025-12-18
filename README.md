# Laravel Project Installation Guide

This guide will walk you through the steps necessary to set up the project locally and populate the database with sample data.

---

## 🛠 Prerequisites

Before you begin, ensure you have the following installed on your system:
* **PHP** (>= 8.1 recommended)
* **Composer** (Dependency Manager for PHP)
* **MySQL** or any compatible SQL database
* **Node.js & NPM** (For compiling assets)

---

## 🚀 Installation Steps

Follow these steps to get your local environment ready:

### 1. Clone the repository
```bash
git clone <repository-url>
cd <project-folder-name>
```

### 2. Install Dependencies
```bash
composer install
npm install && npm run build
```
### 2. Install Dependencies
```bash
composer install
npm install && npm run build
```
### 3. Environment Configuration
Create your environment file and update your database credentials:
```bash
cp .env.example .env
```
> **Note:** Open the `.env` file and set your `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.

### 4. Generate Application Key
```bash
php artisan key:generate
```
### 5. Run Database Migrations
```bash
php artisan migrate
```
---

## 🗄 Database Seeding

To populate the database with **test users** and **dummy data**, you need to run the seeder command. This will allow you to test the application features with pre-generated content.

Run the following command:
```bash
php artisan db:seed
```
---

## 💻 Running the App

Start the local development server:
```bash
php artisan serve
```
The application will be accessible at: [http://127.0.0.1:8000](http://127.0.0.1:8000)

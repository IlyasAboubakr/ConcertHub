<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

# ConcertHub 🎵

ConcertHub is a comprehensive, full-stack Laravel application designed to streamline event management and digital ticketing. 
Whether you're an organizer planning a massive festival or an attendee looking for the next great concert, ConcertHub provides a secure, seamless, and intuitive experience.

## ✨ Features

### For Attendees
- **Event Discovery:** Browse through upcoming concerts and events effortlessly.
- **Secure Ticketing:** Purchase tickets securely.
- **Automated Digital Tickets:** Instantly receive your purchased tickets via email as attached **PDFs**.
- **User Dashboard:** Track past orders, downloaded tickets, and account settings.

### For Organizers & Administrators
- **Event Management:** Create, update, and manage events, including uploading event banners and setting active dates.
- **Ticket Types:** Manage multiple ticket tiers (e.g., VIP, General Admission, Early Bird) and track stock availability automatically.
- **Role-Based Access Control (RBAC):** Strict security separating **Standard Users**, **Organizers**, **Admins**, and **Super Administrators**.
- **Sales Tracking:** Monitor orders, verify payments, and manage attendees.

### Security
- **Email Verification:** Required email verification for newly registered accounts to prevent spam.
- **Supremely Secure Admin Panel:** Protected administrative routes ensuring that standard admins cannot manage Super Administrators.

## 🛠️ Tech Stack

- **Backend:** Laravel 10 (PHP 8.2)
- **Frontend:** Tailwind CSS, Alpine.js, Blade Templates (Laravel Breeze framework)
- **Database:** MySQL / PostgreSQL
- **Asset Compilation:** Vite
- **PDF Generation:** Barryvdh Laravel DOMPDF
- **Email Delivery:** SMTP (Configured for Gmail/Resend)

## 🚀 Getting Started

### Prerequisites
Make sure you have the following installed on your machine:
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL or PostgreSQL

### Local Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/concerthub.git
   cd concerthub
   ```

2. **Install PHP and Node dependencies:**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Set up your environment variables:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Make sure to configure your `DB_*` databases and `MAIL_*` SMTP credentials in the `.env` file!*

4. **Run Database Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```

5. **Link the storage directory:**
   *(Required for locally uploaded event images)*
   ```bash
   php artisan storage:link
   ```

6. **Serve the application:**
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000` in your browser.

## ☁️ Deployment (Railway)

ConcertHub is optimized for deployment on [Railway.app](https://railway.app/). 
1. Connect this repository to a new Railway project.
2. Add a **MySQL** database plugin to your Railway project.
3. Map your `.env` variables to the Railway Environment Variables settings.
4. Add a **Persistent Volume** to `/app/storage/app/public` to retain uploaded event images across deployments.
5. Once deployed, run `php artisan migrate --force` and `php artisan storage:link` using the Railway Terminal.

## 🛡️ License

The ConcertHub project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

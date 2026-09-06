# 🇲🇦 Morocco Explore

A full-stack web-based tourism platform for discovering tourist destinations across Morocco — built as a personal project covering the complete software development lifecycle (SRS → System Design → Implementation → Testing → Deployment).

## 📖 About

Morocco Explore allows visitors to browse, search and filter 100+ real Moroccan destinations, while registered users can save favorites, rate places, and leave reviews. Administrators manage all content through a dedicated admin panel.

## ✨ Features

**Public (Visitor)**
- Browse and search destinations by name, city or region
- Filter by category
- View detailed destination pages with images, average rating, reviews and an embedded map

**Registered User**
- Register / Login / Logout (Laravel Breeze)
- Save destinations to Favorites
- Rate destinations (1–5 stars)
- Submit reviews (subject to admin approval)

**Administrator**
- Manage Categories (CRUD)
- Manage Destinations (CRUD + multi-image upload)
- Moderate Reviews (approve / hide / delete)
- Manage Users (activate / deactivate / delete)

## 🛠️ Tech Stack

- **Backend:** Laravel 12 (PHP 8.2)
- **Database:** MySQL
- **Frontend:** Blade templates, Tailwind CSS, Alpine.js
- **Auth:** Laravel Breeze
- **Maps:** OpenStreetMap (embedded)
- **Images:** Pexels API (auto-fetched for seeded destinations)

## 📂 Project Documentation

Full requirements analysis and system design documents are available in [`/docs`](./docs):
- Software Requirements Specification (SRS)
- System Analysis & Design (Use Cases, Class Diagram, Sequence/Activity Diagrams, ERD)

## 🚀 Getting Started

```bash
git clone https://github.com/RadiaBouzine/Morocco_Explore.git
cd Morocco_Explore
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
# Configure your database credentials in .env
php artisan migrate
php artisan db:seed --class=MoroccoDataSeeder
php artisan storage:link
php artisan serve
```

## 👤 Author

**Radia Bouzine** — Personal Project


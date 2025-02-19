# EHEE

## Introduction
Ehee is an internal investigative tool developed for the **Anti-Corruption Commission of the Maldives**. It allows investigators and evaluators to efficiently search for vehicles using **owner ID** and retrieve detailed information about vehicle **registries, current and past owners, and associated domains**. This system is built using the **Ministry of Transport API integration package (chumputy/ulhandhu-php)**, enabling seamless access to authoritative transport data.

> **Note:** This project is strictly for internal use and is not intended for public access. This is still in development and new features will be added.

---

## Features
- **Search Vehicles by Owner ID**: Quickly retrieve vehicles registered under a specific owner.
- **View Current & Historical Ownership**: Access records of previous and current owners.
- **Domain & Vehicle Details**: Get registry information, vehicle specifications, and related domains.
- **Efficient API Integration**: Utilizes the `chumputy/ulhandhu-php` package for real-time data fetching.
- **Minimal & User-Friendly Interface**: Built with modern UI frameworks for ease of use.

---

## Installation

### Prerequisites
- PHP 8.2+
- Laravel 11
- Composer
- Node (npm)

### Steps
1. **Clone the Repository**
   ```sh
   git clone https://github.com/ACC-ITU/Ehee.git
   cd ehee
   ```

2. **Install Dependencies**
   ```sh
   composer install
   npm install && npm run dev
   ```

3. **Configure Environment**
   Copy the `.env.example` file to `.env` and update the necessary credentials:
   ```sh
   cp .env.example .env
   ```

   Set the required API credentials for **Ulhandhu Package** in `.env`:
   ```env
    ULHANDHU_BASE_URL=http://10.12.78.26
    ULHANDHU_CLIENT_ID=
    ULHANDHU_CLIENT_SECRET=
    ULHANDHU_SCOPE="domains vehicles view-domain-owner view-domain-vehicle view-domain-vehicles view-people-island view-vehicle-domains view-vehicle-garage view-vehicle-info view-vehicle-island view-vehicle-owners view-vessel view-vessel-engines view-vessel-owners"
    ULHANDHU_TOKEN_ENDPOINT=/oauth/token
   ```


5. **Start the Application**
   ```sh
   php artisan serve
   ```

---


## License
This project is **not open-source** and is the intellectual property of the **Anti-Corruption Commission of Maldives**. Unauthorized distribution is strictly prohibited.

---

For further inquiries, contact the **Development Team** at [your-email@acc.gov.mv].


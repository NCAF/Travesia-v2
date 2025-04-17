# Travesia

Travesia is a travel management platform built with Laravel, designed to connect users and drivers for seamless travel experiences. The platform allows users to explore destinations, book tickets, and chat, while drivers can manage their travel routes and vehicle information.

## Features

- **User Registration & Login**: Secure authentication for users and drivers.
- **Driver Registration**: Special registration flow for drivers, including license upload.
- **Destination Management**: Drivers can add, edit, and delete travel destinations, including details like vehicle type, plate number, number of seats, and price.
- **Search & Filter**: Search for destinations by location name.
- **Booking & Orders**: Users can book tickets for available destinations.
- **Chat**: Communication feature for users and drivers (UI present, implementation may vary).
- **Ticket Management**: Users can view and manage their tickets.
- **Responsive UI**: Optimized for both desktop and mobile devices.

## Tech Stack

- **Backend**: [Laravel 10.x](https://laravel.com/)
- **Frontend**: Blade templates, Bootstrap 5, Vite, Axios
- **Database**: MySQL (default, configurable)
- **Authentication**: Laravel Sanctum
- **Payments**: [Midtrans](https://midtrans.com/) integration
- **Other Libraries**: jQuery, ApexCharts (for data visualization), SimpleBar (custom scrollbars)

## Getting Started

### Prerequisites
- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL or compatible database

### Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd Travesia-v2
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies:**
   ```bash
   npm install
   ```

4. **Copy the environment file and set your variables:**
   ```bash
   cp .env.example .env
   # Edit .env to match your local setup
   ```

5. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

6. **Run migrations:**
   ```bash
   php artisan migrate
   ```

7. **Build frontend assets:**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

8. **Start the development server:**
   ```bash
   php artisan serve
   ```

Visit [http://localhost:8000](http://localhost:8000) to access the application.

## Environment Variables

Key variables in `.env`:
- `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL`
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `MAIL_*` (for email sending)
- `MIDTRANS_*` (for payment integration, if applicable)

See `.env.example` for all available options.

## Usage

- **User Flow:**
  - Register and log in as a user to browse destinations, book tickets, and chat.
- **Driver Flow:**
  - Register as a driver (with license upload), log in, and manage your travel destinations, vehicle info, and orders.

## Testing

- **Feature & Unit Tests:**
  - Run all tests:
    ```bash
    phpunit
    # or
    ./vendor/bin/phpunit
    ```
  - Example Feature Test: `tests/Feature/ExampleTest.php`
  - Example Unit Test: `tests/Unit/ExampleTest.php`

## Frontend Build

- Uses [Vite](https://vitejs.dev/) for fast frontend builds and hot module replacement.
- Main entry points: `resources/css/app.css`, `resources/js/app.js`
- Configured in `vite.config.js`.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Credits

- Laravel
- Bootstrap 5
- Midtrans
- ApexCharts
- SimpleBar
- jQuery

---

Take you around the world with unforgettable experiences — Travesia.
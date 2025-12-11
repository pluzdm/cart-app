# Laravel Cart Application

A cart system built with Laravel.

---

# Features

- Product listing with pagination
- Guest cart stored in the database
- Automatic `guest_cart_token` for all visitors (middleware)
- Cart operations:
  - Add product
  - Update quantity
  - Remove item
- Merging guest cart into authenticated user cart
- Cart total calculated via `$cart->total_price` accessor
- Seeded demo products
- Feature tests for cart logic
- Blade views for quick manual testing

---

# Installation & setup

## 1. Clone the repository
```bash
git clone https://github.com/your-username/cart-app.git
cd cart-app
```
## 2. Install PHP dependencies
```bash
composer install
```
## 3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

Update the following .env variables:
env
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cart_app
DB_USERNAME=root
DB_PASSWORD=secret
```
## 4. Create the database
Using MySQL CLI:

```bash
CREATE DATABASE cart_app;
```
Or using any GUI (TablePlus, phpMyAdmin, DBeaver).

## 5. Run migrations and seeders
```bash
php artisan migrate --seed
```
Demo products will now appear on the homepage.

## 6. Install Node.js dependencies
```bash
npm install
```
## 7. Run Vite dev server
```bash
npm run dev
```
## 8. Start Laravel server
```bash
php artisan serve
```
Your application will be available at:

http://127.0.0.1:8000

# Stability assessment

The application should work reliably for the scope of this assignment because:

- All cart modifications are wrapped in database transactions.
- Controllers remain thin and delegate business rules to services.
- Data access is isolated in repositories, which improves testability and maintainability.
- DTOs ensure strict and predictable data flow into service methods.
- Middleware guarantees every visitor always has a cart identifier via `guest_cart_token`.
- Validation is handled by FormRequests, preventing invalid cart operations.
- Domain logic such as total price calculation is encapsulated inside model accessors.
- Feature tests cover core cart behaviors.

For a demo or educational project, this is a stable foundation.

# What is still missing for production-level stability?

A real production cart requires more safeguards, such as:

- better handling of concurrency when updating quantities
- protection from stock conflicts
- more extensive test coverage (deleted products, abnormal quantities, cart merge edge cases)
- caching frequently accessed data (product lists, product prices)
- define rules for transferring a guest cart to an authenticated user during login
- rate-limiting cart operations to prevent abuse

These improvements were intentionally omitted to keep the solution focused.

---

# Additional feature example

To demonstrate how this architecture can be extended, below is one complete, realistic feature that could be added to the project. 

## Feature: cart reservation system (prevent overselling)

In real e-commerce applications, limited-stock items can be oversold if multiple users add them to the cart at the same time.  
A reservation system temporarily reserves stock for the user so nobody else can take it while they complete checkout.

## How it works

1. When a user adds an item to their cart, the system creates a stock reservation for a limited time (e.g., 15–20 minutes).
2. Reserved stock is subtracted from available inventory.
3. If the user does not complete the checkout before the reservation expires:
    - reservation is removed
    - inventory is released back
    - the cart notifies user that their reservation expired
4. If the user checks out:
    - final stock is deducted
    - reservation entries are deleted

## Database changes

Add a table:

```bash
stock_reservations (
id,
cart_id,
product_id,
quantity,
expires_at,
created_at
)
```

Add a `stock` column to products:
```bash
products.stock (integer)
```

## Repository Layer

Create a `StockReservationRepositoryInterface` with methods:

```php
public function reserve(int $cartId, int $productId, int $quantity)
public function release(int $cartId, int $productId, int $quantity)
public function findActiveReservations(int $cartId)
public function expireReservations()
```

The implementation encapsulates all SQL/Eloquent logic related to reservation and expiration.

## Service layer

A new `StockService` coordinates stock logic:

```php
public function reserveStock(Cart $cart, Product $product, int $quantity)
public function releaseStock(Cart $cart, Product $product, int $quantity)
public function expireOldReservations()
```

CartService calls StockService inside a database transaction to guarantee consistency.

## Scheduled task

`php artisan schedule:run` executes a command every minute:
- finds expired reservations
- releases stock
- updates cart status

## Testing

Important tests include:
- can't reserve more stock than available
- reservation expires properly
- stock is restored after expiration
- checkout finalizes reservations into permanent stock reduction


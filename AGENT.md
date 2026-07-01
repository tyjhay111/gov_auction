# Government Online Auction Platform (Laravel + Blade + Livewire)
## 1. Project Overview

Build a web-based Government Online Auction Platform that allows government agencies to list and auction surplus or confiscated items. Citizens can place bids in real time, and winning bidders complete payments through the system.

The goal is to digitize auction workflows, improve transparency, and provide a simple, reliable bidding system.

This project prioritizes:

- Fast development
- Maintainability
- Free or low-cost deployment
- Laravel ecosystem simplicity

## 2. Tech Stack
**Backend**
- Laravel 12
- PHP 8.4
- Laravel Livewire (core UI reactivity)
- Blade templates
- Laravel Sanctum (authentication)
- Eloquent ORM
- MySQL 8
- Flux UI

**Frontend**
- Blade (server-rendered UI)
- Livewire (dynamic interactions)
- Tailwind
- FLUX UI

**Supporting Tools**
- Composer
- Node.js + Vite (asset bundling)
- Git + GitHub
- Postman (API testing if needed)

## 3. System Architecture

Monolithic Laravel application:

Client (Browser)
→ Blade Views
→ Livewire Components
→ Laravel Controllers / Services
→ Eloquent Models
→ MySQL Database

File Storage:
→ Local storage (dev)
→ Cloud storage optional (future)

## 4. User Roles & Permissions
**1. Administrator**
Full system control:
- Manage users (create, suspend, delete)
- Manage categories
- Approve/reject auctions
- Monitor all bids
- View reports
- Manage system settings
- View all payments

**2. Government Officer (Auction Manager)**
- Create auctions
- Upload images
- Edit auctions before start
- Set starting price, reserve price, duration
- Start / close auctions
- View bidders
- Confirm winning bidder
- View own auction history

**3. Bidder (Citizen)**
- Register / login
- Browse auctions
- Filter and search listings
- View auction details
- Place bids
- Add to watchlist
- Receive outbid updates (basic notifications)
- View won auctions
- Make payments
- Download receipts
- Manage profile

## 5. Core Features
**Authentication**
- Email/password registration
- Email verification
- Password reset
- Role-based access control

**Auction System**
- Create auction listings
- Upload multiple images
- Set start price, reserve price, duration
- Auto-expire auctions
- Display live highest bid

**Bidding System**
- Place bids
- Validate bid > current highest
- Store bid history
- Prevent self-outbidding rules (optional)

**Watchlist**
- Save/unsave auctions
- Track auction status

**Payments**
- Record payment after win
- Generate invoice
- Store payment history

**Admin Dashboard**
- Overview stats (users, auctions, bids)
- Manage all entities
- View reports

## 6. Database Design (MySQL)
**users**
- id
- name
- email
- password
- role (admin | officer | bidder)
- email_verified_at
- timestamps

**auctions**
- id
- title
- description
- starting_price
- reserve_price
- current_price
- start_time
- end_time
- status (draft | active | closed)
- created_by (user_id)
- timestamps

**auction_images**
- id
- auction_id
- image_path

**bids**
- id
- auction_id
- user_id
- amount
- created_at

**categories**
- id
- name

**auction_category (pivot)**
- auction_id
- category_id

**watchlists**
- id
- user_id
- auction_id

**payments**
- id
- user_id
- auction_id
- amount
- status (pending | completed)
- reference
- timestamps

## 7. Application Structure
**Laravel Modules**
- Auth Module
- Auction Module
- Bidding Module
- Admin Module
- Payment Module
- Notification Module

**Livewire Components**
- AuctionList
- AuctionDetails
- BidForm
- AdminDashboard
- OfficerAuctionManager
- WatchlistComponent

**Blade Pages**
- layout/app.blade.php
- home.blade.php
- auctions/index.blade.php
- auctions/show.blade.php
- dashboard/admin.blade.php
- dashboard/officer.blade.php

## 8. Implementation Plan (Phases)
**Phase 1: Foundation**
- Setup Laravel project
- Configure authentication (Sanctum)
- Create database schema
- Setup roles & middleware

**Phase 2: Auctions**
- Auction CRUD (officer role)
- Image upload system
- Auction listing page
- Auction detail page

**Phase 3: Bidding System**
- Bid placement logic
- Real-time highest bid updates (Livewire polling)
- Bid validation rules
- Bid history tracking

**Phase 4: User Features**
- Watchlist system
- User profile pages
- Auction tracking

**Phase 5: Payments**
- Payment recording
- Receipt generation
- Payment history

**Phase 6: Admin Dashboard**
- User management
- Auction moderation
- Reports dashboard

## 9. Security Requirements
- Role-based middleware protection
- CSRF protection (Laravel default)
- Input validation using Form Requests
- File upload validation (images only)
- Prevent invalid bid submission
- Rate-limit bid requests (basic throttling)

## 10. UI/UX Guidelines
- Mobile-first design
- Clean dashboard layout
- Simple auction cards
- Clear bid visibility
- Countdown timers for auctions
- Minimal navigation complexity

Use Tailwind CSS for all styling.

## 11. Deployment Strategy (Free Tier)
Recommended stack:
- Backend: Render or Railway
- Database: Railway MySQL (or equivalent free tier)
- Storage: Local (initially)
- Optional: Cloudinary for images
- Domain: free subdomain or Vercel front proxy (if needed)

Steps:
- Deploy Laravel backend
- Configure environment variables
- Run migrations on production DB
- Link storage properly
- Enable queue worker (optional)

## 12. Performance Considerations
- Use database indexing on bids (auction_id, amount)
- Cache auction listings (optional)
- Optimize image sizes before upload
- Avoid heavy Livewire re-renders

## 13. Future Enhancements
- Real-time WebSocket bidding
- SMS/email notifications
- Auto-bidding system
- Two-factor authentication
- Advanced analytics dashboard
- Mobile app integration
- AI-based auction recommendations

## 14. Acceptance Criteria
The system is complete when:
- Users can register and login by role
- Officers can create auctions
- Bidders can place bids successfully
- Highest bid updates correctly
- Auction closes automatically by time
- Admin can manage all entities
- Payments are recorded and retrievable
- System runs on free-tier deployment without errors

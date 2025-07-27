# Phone Buy & Sell Management System

A lightweight Laravel application powered by Filament Admin to track purchasing, selling and payments of mobile phones.

## Features

- **Customers**  
  - Add/edit customers (individuals or stores)  
  - Store multiple phone contacts  

- **Invoices**  
  - Create **Purchase** and **Sales** invoices  
  - Add multiple phone items per invoice (model, storage, color, IMEI, unit price)  
  - Calculate line‑item totals and invoice grand total  

- **Inventory**  
  - Track each phone as a unique record (`phones` table)  
  - Status: _available_ or _sold_  
  - Link each phone to its purchase and (when sold) its sales invoice  

- **Payments**  
  - Log payments against any invoice  
  - Support cash, card, bank transfer and “other” methods  
  - Show **Paid** vs **Outstanding** amounts  

- **Dashboard & Reporting**  
  - Customer list with counts/sums of buy/sell invoices  
  - Table columns:  
    - Total bought, total sold, net balance  
    - Total amounts (EGP) for buys, sells, outstanding  
  - Quick‑view modals for invoice details  

## Installation

1. Clone this repo  
   ```bash
   git clone git@github.com:ZY1YOGI/Najmat-Al-Thuraya.git
   cd Najmat-Al-Thuraya

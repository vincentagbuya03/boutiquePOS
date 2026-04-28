# Boutique POS ERD

This ERD reflects the current business schema based on the latest migrations.

```mermaid
erDiagram
    CATEGORIES {
        bigint id PK
        string name
        string description
        datetime created_at
        datetime updated_at
    }

    PRODUCTS {
        bigint id PK
        bigint category_id FK
        string name
        string description
        string image_path
        string brand
        string size
        date date_added
        datetime created_at
        datetime updated_at
    }

    INVENTORY {
        bigint id PK
        bigint product_id FK
        int quantity
        int reorder_level
        date last_updated
        datetime created_at
        datetime updated_at
    }

    SUPPLIERS {
        bigint id PK
        string name
        string contact_person
        string phone
        string email
        text address
        datetime created_at
        datetime updated_at
    }

    PRODUCT_BATCHES {
        bigint id PK
        bigint product_id FK
        bigint supplier_id FK
        string batch_number
        int quantity
        decimal cost_price
        decimal selling_price
        date date_received
        datetime created_at
        datetime updated_at
    }

    USERS {
        bigint id PK
        string name
        string email
        string contact_number
        string address
        enum role
        timestamp email_verified_at
        string password
        datetime created_at
        datetime updated_at
    }

    SALES {
        bigint id PK
        bigint user_id FK
        decimal total_amount
        enum discount_type
        decimal discount_amount
        string payment_method
        decimal cash_received
        decimal change_amount
        enum status
        date date_sold
        datetime created_at
        datetime updated_at
    }

    SALE_ITEMS {
        bigint id PK
        bigint sale_id FK
        bigint product_id FK
        int quantity
        decimal unit_price
        decimal total_price
        datetime created_at
        datetime updated_at
    }

    RETURNS_AND_REFUNDS {
        bigint id PK
        bigint sale_id FK
        bigint product_id FK
        int quantity_returned
        enum reason
        text description
        enum status
        enum action
        decimal refund_amount
        date return_date
        date processed_date
        bigint processed_by FK
        datetime created_at
        datetime updated_at
    }

    CATEGORIES ||--o{ PRODUCTS : categorizes
    PRODUCTS ||--o{ INVENTORY : tracked_in
    PRODUCTS ||--o{ PRODUCT_BATCHES : stocked_by_batch
    SUPPLIERS ||--o{ PRODUCT_BATCHES : supplies

    USERS ||--o{ SALES : records
    SALES ||--o{ SALE_ITEMS : contains
    PRODUCTS ||--o{ SALE_ITEMS : sold_as

    SALES ||--o{ RETURNS_AND_REFUNDS : source_sale
    PRODUCTS ||--o{ RETURNS_AND_REFUNDS : returned_product
    USERS ||--o{ RETURNS_AND_REFUNDS : processed_by
```

## Notes

- The online ordering tables (online_orders, order_items, customers) are removed by the restructure migration and are not included in the current-state ERD.
- Laravel framework support tables (sessions, cache, jobs, etc.) are excluded to keep this ERD focused on business entities.

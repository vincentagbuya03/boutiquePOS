# Visual Table of Contents (VTOC)

This page provides a complete visual map of the Boutique POS project documentation and major system pages, using the same top-down structure as your sample.

## Main Project Map

```mermaid
flowchart TB
    A["0.0<br/>BOUTIQUE POS<br/>HOME"]

    B["1.0<br/>QUICK START"]
    C["2.0<br/>SETUP GUIDE"]
    D["3.0<br/>SYSTEM<br/>IMPLEMENTATION"]
    E["4.0<br/>RBAC SYSTEM"]
    F["5.0<br/>USER<br/>MANAGEMENT"]
    G["6.0<br/>BRANCH<br/>SCOPING"]
    H["7.0<br/>BUTTON<br/>VISIBILITY"]
    I["8.0<br/>ERD"]
    J["9.0<br/>TEST<br/>ACCOUNTS"]
    K["10.0<br/>DEPLOYMENT"]

    A --- B
    A --- C
    A --- D
    A --- E
    A --- F
    A --- G
    A --- H
    A --- I
    A --- J
    A --- K

    classDef toc fill:#ffffff,stroke:#7a7a7a,stroke-width:1.2px,color:#111111,font-size:14px;
    class A,B,C,D,E,F,G,H,I,J,K toc;
    linkStyle default stroke:#666666,stroke-width:1.2px;
```

**Boutique POS Home Page**

Figure V.1.0  
VTOC

## Documentation Modules

```mermaid
flowchart TB
    A["3.0<br/>SYSTEM<br/>IMPLEMENTATION"]

    B["3.1<br/>DATABASE<br/>LAYER"]
    C["3.2<br/>MODELS<br/>LAYER"]
    D["3.3<br/>CONTROLLERS<br/>LAYER"]
    E["3.4<br/>ROUTES"]
    F["3.5<br/>BUSINESS<br/>FEATURES"]

    A --- B
    A --- C
    A --- D
    A --- E
    A --- F

    classDef toc fill:#ffffff,stroke:#7a7a7a,stroke-width:1.2px,color:#111111,font-size:14px;
    class A,B,C,D,E,F toc;
    linkStyle default stroke:#666666,stroke-width:1.2px;
```

**System Implementation Page**

Figure V.1.1  
VTOC

## RBAC System Page

```mermaid
flowchart TB
    A["4.0<br/>RBAC<br/>SYSTEM"]

    B["4.1<br/>OWNER"]
    C["4.2<br/>ADMIN"]
    D["4.3<br/>STAFF"]
    E["4.4<br/>CASHIER"]
    F["4.5<br/>PERMISSIONS"]

    A --- B
    A --- C
    A --- D
    A --- E
    A --- F

    classDef toc fill:#ffffff,stroke:#7a7a7a,stroke-width:1.2px,color:#111111,font-size:14px;
    class A,B,C,D,E,F toc;
    linkStyle default stroke:#666666,stroke-width:1.2px;
```

**RBAC System Page**

Figure V.1.2  
VTOC

## User Management Page

```mermaid
flowchart TB
    A["5.0<br/>USER<br/>MANAGEMENT"]

    B["5.1<br/>USER<br/>DOCS"]
    C["5.2<br/>USER<br/>IMPLEMENTATION"]
    D["5.3<br/>USER<br/>SUMMARY"]
    E["5.4<br/>CREATE<br/>USERS"]
    F["5.5<br/>EDIT AND<br/>DELETE USERS"]

    A --- B
    A --- C
    A --- D
    A --- E
    A --- F

    classDef toc fill:#ffffff,stroke:#7a7a7a,stroke-width:1.2px,color:#111111,font-size:14px;
    class A,B,C,D,E,F toc;
    linkStyle default stroke:#666666,stroke-width:1.2px;
```

**User Management Page**

Figure V.1.3  
VTOC

## System Pages Map

```mermaid
flowchart TB
    A["11.0<br/>SYSTEM<br/>PAGES"]

    B["11.1<br/>LOGIN"]
    C["11.2<br/>DASHBOARD"]
    D["11.3<br/>PRODUCTS"]
    E["11.4<br/>CATEGORIES"]
    F["11.5<br/>INVENTORY"]
    G["11.6<br/>SALES"]
    H["11.7<br/>RETURNS"]
    I["11.8<br/>SUPPLIERS"]
    J["11.9<br/>BATCHES"]
    K["11.10<br/>REPORTS"]
    L["11.11<br/>USERS"]

    A --- B
    A --- C
    A --- D
    A --- E
    A --- F
    A --- G
    A --- H
    A --- I
    A --- J
    A --- K
    A --- L

    classDef toc fill:#ffffff,stroke:#7a7a7a,stroke-width:1.2px,color:#111111,font-size:14px;
    class A,B,C,D,E,F,G,H,I,J,K,L toc;
    linkStyle default stroke:#666666,stroke-width:1.2px;
```

**System Pages**

Figure V.1.4  
VTOC

## Sales and Returns Page

```mermaid
flowchart TB
    A["11.6<br/>SALES"]

    B["11.6.1<br/>CREATE<br/>SALE"]
    C["11.6.2<br/>SALES<br/>LIST"]
    D["11.6.3<br/>SALES<br/>REPORT"]
    E["11.7.1<br/>CREATE<br/>RETURN"]
    F["11.7.2<br/>RETURNS<br/>LIST"]
    G["11.7.3<br/>APPROVE OR<br/>REJECT"]
    H["11.7.4<br/>RETURNS<br/>REPORT"]

    A --- B
    A --- C
    A --- D
    A --- E
    A --- F
    A --- G
    A --- H

    classDef toc fill:#ffffff,stroke:#7a7a7a,stroke-width:1.2px,color:#111111,font-size:14px;
    class A,B,C,D,E,F,G,H toc;
    linkStyle default stroke:#666666,stroke-width:1.2px;
```

**Sales and Returns Page**

Figure V.1.5  
VTOC

## Inventory and Product Page

```mermaid
flowchart TB
    A["11.3<br/>PRODUCTS"]

    B["11.3.1<br/>PRODUCT<br/>LIST"]
    C["11.3.2<br/>CREATE<br/>PRODUCT"]
    D["11.3.3<br/>EDIT<br/>PRODUCT"]
    E["11.4.1<br/>CATEGORIES"]
    F["11.5.1<br/>INVENTORY<br/>LIST"]
    G["11.5.2<br/>LOW STOCK"]
    H["11.5.3<br/>UPDATE<br/>STOCK"]
    I["11.8.1<br/>SUPPLIERS"]
    J["11.9.1<br/>PRODUCT<br/>BATCHES"]

    A --- B
    A --- C
    A --- D
    A --- E
    A --- F
    A --- G
    A --- H
    A --- I
    A --- J

    classDef toc fill:#ffffff,stroke:#7a7a7a,stroke-width:1.2px,color:#111111,font-size:14px;
    class A,B,C,D,E,F,G,H,I,J toc;
    linkStyle default stroke:#666666,stroke-width:1.2px;
```

**Inventory and Product Page**

Figure V.1.6  
VTOC

## Section Reference

| Code | Section | File or Route |
| --- | --- | --- |
| 0.0 | Boutique POS Home | `README.md` |
| 1.0 | Quick Start | `QUICK_START.md` |
| 2.0 | Setup Guide | `SETUP_GUIDE.md` |
| 3.0 | System Implementation | `SYSTEM_IMPLEMENTATION.md` |
| 4.0 | RBAC System | `RBAC_DOCUMENTATION.md` |
| 5.0 | User Management | `USER_MANAGEMENT_DOCS.md` |
| 6.0 | Branch Scoping | `BRANCH_SCOPING_IMPLEMENTATION.md`, `BRANCH_FILTER_GUIDE.md` |
| 7.0 | Button Visibility | `BUTTON_VISIBILITY_GUIDE.md` |
| 8.0 | ERD | `ERD.md` |
| 9.0 | Test Accounts | `TEST_ACCOUNTS.md` |
| 10.0 | Deployment | `DEPLOYMENT_CHECKLIST.md` |
| 11.1 | Login | `/login` |
| 11.2 | Dashboard | `/dashboard` |
| 11.3 | Products | `/products` |
| 11.4 | Categories | `/categories` |
| 11.5 | Inventory | `/inventory` |
| 11.6 | Sales | `/sales` |
| 11.7 | Returns | `/returns` |
| 11.8 | Suppliers | `/suppliers` |
| 11.9 | Batches | `/batches` |
| 11.10 | Reports | `/reports` |
| 11.11 | Users | `/users` |

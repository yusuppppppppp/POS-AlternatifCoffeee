@extends('layouts.app')
@section('title', 'Order List')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
        color: #334155;
    }

    .order-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px;
        background: transparent;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    .container.drawer-open .order-container {
        margin-left: 0px !important;
        margin-right: 80px !important;
    }

    .page-header {
        background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
        padding: 40px;
        border-radius: 24px;
        margin-bottom: 32px;
        box-shadow: 
            0 20px 40px rgba(46, 71, 102, 0.15),
            0 0 0 1px rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.6;
    }

    .page-title {
        color: white;
        font-size: 2.25rem;
        font-weight: 700;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
        margin-bottom: 24px;
        position: relative;
        z-index: 1;
    }

    .btn-download-pdf {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
    }

    .btn-download-pdf:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.2) 100%);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        color: white;
        text-decoration: none;
    }

    .btn-download-pdf::before {
        content: '';
        background-image: url('/images/download.png');
        background-size: 25px 25px;
        background-repeat: no-repeat;
        background-position: center;
        width: 25px;
        height: 25px;
        display: inline-block;
    }

    .btn-send-email {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
        margin-left: 12px;
        width: auto;
        height: 3.3rem;
    }

    .btn-send-email:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.2) 100%);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        color: white;
        text-decoration: none;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        padding: 28px 24px;
        border-radius: 18px;
        box-shadow: 
            0 4px 20px rgba(0, 0, 0, 0.06),
            0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(226, 232, 240, 0.4);
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 
            0 8px 30px rgba(0, 0, 0, 0.1),
            0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .stat-card h3 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2E4766;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .stat-card p {
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .order-table {
        background-color: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .table-header {
        background-color: #f8f9fa;
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        display: grid;
        grid-template-columns: 60px 80px 1fr 1fr 1fr 100px 60px;
        gap: 15px;
        align-items: center;
        font-weight: 600;
        color: #495057;
        font-size: 14px;
    }

    .order-row {
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        display: grid;
        grid-template-columns: 60px 80px 1fr 1fr 1fr 100px 60px;
        gap: 15px;
        align-items: center;
        transition: background-color 0.2s ease;
        cursor: pointer;
    }

    .order-row:hover {
        background-color: #f8f9fa;
    }

    .order-row:last-child {
        border-bottom: none;
    }

    .order-number {
        font-weight: 600;
        color: #6c757d;
        font-size: 14px;
        text-align: center;
    }

    .order-id {
        font-weight: 600;
        color: #212529;
        font-size: 16px;
    }

    .customer-name {
        font-weight: 500;
        color: #212529;
        font-size: 14px;
    }

    .order-type {
        font-weight: 500;
        color: #212529;
        font-size: 14px;
        text-transform: capitalize;
    }

    .total-amount {
        font-weight: 600;
        color: #212529;
        font-size: 16px;
    }

    .order-date {
        color: #6c757d;
        font-size: 14px;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        box-shadow: 
            0 2px 8px rgba(37, 99, 235, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .info-icon img {
        filter: brightness(0) saturate(100%) invert(40%) sepia(95%) saturate(2000%) hue-rotate(210deg) brightness(0.95) contrast(1.1);
        transition: all 0.3s ease;
    }

    .info-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .info-icon:hover {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #bfdbfe 0%, #93c5fd 100%);
        box-shadow: 
            0 4px 16px rgba(37, 99, 235, 0.25),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }

    .info-icon:hover img {
        filter: brightness(0) saturate(100%) invert(25%) sepia(95%) saturate(2000%) hue-rotate(210deg) brightness(0.85) contrast(1.2);
    }

    .info-icon:hover::before {
        opacity: 1;
    }

    /* Order details modal/expandable section (optional) */
    .order-details {
        display: none;
        padding: 20px;
        background-color: #f8f9fa;
        border-top: 2px solid #2E4766;
        border-bottom: 1px solid #e9ecef;
    }

    .order-details.show {
        display: block;
    }

    .customer-info {
        margin-bottom: 15px;
        padding: 15px;
        background-color: #fff;
        border-radius: 8px;
        border-left: 4px solid #2E4766;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .customer-info h4 {
        margin-bottom: 10px;
        color: #2E4766;
        font-size: 16px;
        font-weight: 600;
    }

    .customer-info p {
        margin: 5px 0;
        color: #495057;
        font-size: 14px;
    }

    .customer-info p strong {
        color: #2E4766;
        font-weight: 600;
    }

    .order-details h4 {
        margin-bottom: 10px;
        color: #2E4766;
        font-size: 16px;
        font-weight: 600;
    }

    .order-details ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .order-details li {
        padding: 10px 15px;
        margin-bottom: 8px;
        background-color: #fff;
        border-radius: 6px;
        border-left: 3px solid #2E4766;
        color: #495057;
        font-size: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-details li:last-child {
        margin-bottom: 0;
    }

    .item-quantity {
        background: #2E4766;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-right: 8px;
    }

    .item-price {
        color: #2E4766;
        font-weight: 600;
    }

    .no-orders {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
        font-style: italic;
        font-size: 16px;
    }

    .pagination-container {
        margin-top: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .pagination-info {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
    }

    .per-page-selector {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #64748b;
        font-size: 14px;
    }

    .per-page-selector label {
        font-weight: 600;
        color: #495057;
        font-size: 14px;
    }

    .per-page-selector select {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        color: #374151;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .per-page-selector select:hover {
        border-color: #2E4766;
    }

    .per-page-selector select:focus {
        outline: none;
        border-color: #2E4766;
        box-shadow: 0 0 0 3px rgba(46, 71, 102, 0.1);
    }

    .pagination-links {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination-links .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 5px;
    }

    .pagination-links .page-item {
        margin: 0;
    }

    .pagination-links .page-link {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        background: white;
        color: #374151;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .pagination-links .page-link:hover {
        background: #f3f4f6;
        border-color: #2E4766;
        color: #2E4766;
    }

    .pagination-links .page-item.active .page-link {
        background: #2E4766;
        border-color: #2E4766;
        color: white;
    }

    .pagination-links .page-item.disabled .page-link {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }

    /* Search Form Styles */
    .search-container {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 
            0 4px 20px rgba(0, 0, 0, 0.06),
            0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(226, 232, 240, 0.4);
        margin-bottom: 24px;
    }

    .search-form {
        display: flex;
        align-items: center;
        margin-left: -10px;
        gap: 16px;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .search-input-group {
        position: relative;
        flex: 1;
        min-width: 300px;
        display: flex;
        align-items: center;
    }

    .search-input {
        width: 100%;
        padding: 14px 56px 14px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: #374151;
        background: #f8fafc;
        transition: all 0.3s ease;
        outline: none;
    }

    .search-input:focus {
        border-color: #2E4766;
        background: white;
        box-shadow: 0 0 0 3px rgba(46, 71, 102, 0.1);
    }

    .search-input::placeholder {
        color: #9ca3af;
    }

    .search-button {
        position: absolute;
        right: -10px;
        top: 20px;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
        border: none;
        border-radius: 8px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(46, 71, 102, 0.2);
    }

    .search-button:hover {
        background: linear-gradient(135deg, #1e3a5f 0%, #2E4766 100%);
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 4px 12px rgba(46, 71, 102, 0.3);
    }

    .search-button:active {
        transform: translateY(-50%) scale(0.95);
    }

    .search-button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(46, 71, 102, 0.2);
    }

    .clear-search {
        padding: 10px 16px;
        background: #f3f4f6;
        color: #6b7280;
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid #d1d5db;
    }

    .clear-search:hover {
        background: #e5e7eb;
        color: #374151;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 900px) {
        .order-container {
            margin-left: 0 !important;
            padding: 20px;
            margin-top: 0;
        }
        
        .page-header {
            padding: 32px 24px;
            margin-bottom: 24px;
        }
        
        .page-title {
            font-size: 1.75rem;
        }
        
        .stats-cards {
            grid-template-columns: 1fr 1fr;
        }

        .search-container {
            padding: 20px;
        }

        .search-input-group {
            min-width: 250px;
        }
    }

    @media (max-width: 768px) {
        .order-container {
            padding: 16px;
        }
        
        .stats-cards {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .search-container {
            padding: 16px;
        }

        .search-form {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input-group {
            min-width: auto;
        }
        
        .table-header,
        .order-row {
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 10px;
        }

        .order-type,
        .order-date,
        .info-icon {
            grid-column: span 1;
            justify-self: start;
        }
        
        .info-icon {
            justify-self: end;
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 1.5rem;
        }
        
        .page-subtitle {
            font-size: 1rem;
        }
        
        .table-header th,
        .order-row td {
            padding: 12px 8px;
        }
        
        .pagination-container {
            flex-direction: column;
            gap: 15px;
            text-align: center;
            padding: 16px;
        }
        
        .pagination-info,
        .per-page-selector {
            justify-content: center;
        }
        
        .pagination-links .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
    }

    /* Email Modal Styles */
    .email-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(46, 71, 102, 0.8);
        backdrop-filter: blur(12px);
        z-index: 3000;
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .email-modal-content {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        padding: 30px;
        width: 90%;
        max-width: 420px;
        margin: 20vh auto;
        border-radius: 24px;
        box-shadow: 
            0 25px 50px rgba(46, 71, 102, 0.25),
            0 0 0 1px rgba(255, 255, 255, 0.1);
        position: relative;
        animation: slideUp 0.4s ease-out;
        display: flex;
        flex-direction: column;
    }

    .email-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 16px;
    }

    .email-modal-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #2E4766, #4a6b8a, #2E4766);
        border-radius: 1px;
    }

    .email-modal-title {
        color: #2E4766;
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
    }

    .email-close-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: rgba(46, 71, 102, 0.1);
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        color: #2E4766;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .email-close-btn:hover {
        background: rgba(46, 71, 102, 0.2);
        transform: rotate(90deg);
    }

    .email-form-group {
        margin-bottom: 16px;
    }

    .email-form-group label {
        font-weight: 600;
        color: #2E4766;
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
    }

    .email-form-group input,
    .email-form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fff;
        font-family: inherit;
    }

    .email-form-group textarea {
        min-height: 70px;
        resize: vertical;
    }

    .email-form-group input:focus,
    .email-form-group textarea:focus {
        outline: none;
        border-color: #2E4766;
        box-shadow: 
            0 0 0 3px rgba(46, 71, 102, 0.1),
            0 4px 12px rgba(46, 71, 102, 0.1);
        transform: translateY(-1px);
    }

    .email-submit-btn {
        background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 10px;
        width: 100%;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 
            0 4px 15px rgba(46, 71, 102, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
        margin-top: 8px;
    }

    .email-submit-btn:hover {
        background: linear-gradient(135deg, #1e3a5f 0%, #2E4766 100%);
        transform: translateY(-2px);
        box-shadow: 
            0 6px 20px rgba(46, 71, 102, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .email-submit-btn:active {
        transform: translateY(0);
    }

    .email-submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .email-error-message {
        margin-top: 12px;
        padding: 10px;
        background: #fee2e2;
        color: #dc2626;
        border-radius: 8px;
        font-size: 13px;
        display: none;
    }

    .email-success-message {
        margin-top: 12px;
        padding: 10px;
        background: #d1fae5;
        color: #059669;
        border-radius: 8px;
        font-size: 13px;
        display: none;
    }

    /* Notification Styles (similar to login page) */
    .notification {
        position: fixed !important;
        top: -20rem;
        left: 0;
        right: 0;
        z-index: 10000 !important;
        display: flex;
        justify-content: center;
        transition: all 0.5s ease-in-out;
        padding: 15px 0;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    .notification.show {
        top: -7rem;
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .notification-content {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        max-width: 90%;
        width: 100%;
        max-width: 500px;
        z-index: 10001 !important;
        font-weight: 500;
        position: relative;
    }

    .success-notification .notification-content {
        background-color: rgba(255, 255, 255, 0.25);
        color: #059669;
        border-left: 4px solid #059669;
        backdrop-filter: blur(10px);
    }

    .error-notification .notification-content {
        background-color: rgba(255, 255, 255, 0.25);
        color: #B91C1C;
        border-left: 4px solid rgb(161, 33, 33);
        backdrop-filter: blur(10px);
    }

    .notification svg {
        margin-right: 12px;
        flex-shrink: 0;
    }

    .notification span {
        font-size: 14px;
        line-height: 2;
    }
</style>

<div class="order-container">
    <!-- Header -->
    <div class="page-header">
        <h2 class="page-title">Daily Sales Report</h2>
        <p class="page-subtitle">Manage and monitor all sales transactions</p>
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('order-list.download-pdf') }}" class="btn-download-pdf">Download PDF</a>
            <button type="button" onclick="sendEmail()" class="btn-send-email">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22 2L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Send Email
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="stat-card orders">
            <h3>{{ $totalOrdersToday }}</h3>
            <p>Total Orders Today</p>
        </div>
        <div class="stat-card revenue">
            <h3>Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}</h3>
            <p>Total Income Today</p>
        </div>
    </div>

    <!-- Search Form -->
    <div class="search-container">
        <form id="searchForm" class="search-form">
            <div class="search-input-group">
                <input 
                    type="text" 
                    name="search" 
                    id="searchInput"
                    value="{{ $search }}" 
                    placeholder="Search by customer name, order type, ID, total amount, cashier name, or menu name..."
                    class="search-input"
                    autocomplete="off"
                >
                <button type="submit" class="search-button">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            @if(!empty($search))
                <a href="{{ route('order-list') }}" class="clear-search" id="clearSearch">Clear Search</a>
            @endif
        </form>
    </div>

    <div id="orderListContainer">
        <div class="order-table">
            <div class="table-header">
                <div>No.</div>
                <div>Order ID</div>
                <div>Customer</div>
                <div>Order Type</div>
                <div>Time</div>
                <div>Total Amount</div>
                <div>Action</div>
            </div>
            
            @if($orders->count() > 0)
                @foreach($orders as $index => $order)
                    <div class="order-row" data-order-id="{{ $index }}">
                        <div class="order-number">{{ $loop->iteration }}</div>
                        <div class="order-id">{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
                        <div class="customer-name">{{ $order->customer_name }}</div>
                        <div class="order-type">{{ $order->order_type }}</div>
                        <div class="order-date">{{ $order->created_at->format('h:i:s A') }}</div>
                        <div class="total-amount">Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        <div class="info-icon">
                            <img src="{{ asset('images/info.png') }}" alt="Info" style="height:24px;width:24px;">
                        </div>
                    </div>
                    
                    <!-- Order details -->
                    <div class="order-details" id="details-{{ $index }}">
                        <div class="customer-info">
                            <h4>Customer Information</h4>
                            <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Type:</strong> {{ ucfirst($order->order_type) }}</p>
                            <p><strong>Time:</strong> {{ $order->created_at->format('h:i:s A') }}</p>
                            <p><strong>Cashier:</strong> {{ $order->user ? $order->user->name : 'Unknown' }}</p>
                        </div>
                        
                        <h4>Items Ordered:</h4>
                        <ul>
                            @foreach($order->items as $item)
                                <li>
                                    <span>
                                        <span class="item-quantity">{{ $item->quantity }}x</span>
                                        {{ $item->menu_name }}
                                    </span>
                                    <span class="item-price">Rp. {{ number_format($item->price, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @else
                <div class="no-orders">
                    <p>Tidak ada pesanan hari ini.</p>
                </div>
            @endif
        </div>
        
        @if($orders->count() > 0)
            <!-- Show Entries and Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} entries
                </div>
                
                <div class="per-page-selector">
                    <label for="per_page">Show:</label>
                    <select id="per_page" name="per_page">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span>entries</span>
                </div>
            </div>
            
            <div class="pagination-links">
                {{ $orders->appends(['per_page' => $perPage, 'search' => $search])->links() }}
            </div>
        @endif
    </div>

    <script>
    // Pindahkan fungsi ke global scope agar bisa dipanggil dari mana saja
    function toggleOrderDetails(index) {
        const details = document.getElementById('details-' + index);
        if (!details) return;
        
        const isShowing = details.classList.contains('show');
        
        // Sembunyikan semua detail terlebih dahulu
        document.querySelectorAll('.order-details').forEach(detail => {
            detail.classList.remove('show');
        });
        
        // Toggle detail yang diklik
        if (!isShowing) {
            details.classList.add('show');
        }
    }
    
    // Event delegation untuk klik pada order-row
    function attachRowDelegation() {
        const container = document.getElementById('orderListContainer');
        if (!container) return;
        // Pastikan tidak double-binding
        if (container.__rowDelegationAttached) return;
        container.__rowDelegationAttached = true;
        container.addEventListener('click', function(e) {
            const row = e.target.closest('.order-row');
            if (row && container.contains(row)) {
                const id = row.getAttribute('data-order-id');
                toggleOrderDetails(id);
            }
        });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        const orderListContainer = document.getElementById('orderListContainer');
        const clearSearch = document.getElementById('clearSearch');
        let typingTimer;
        const doneTypingInterval = 10;

        // Inisialisasi event delegation agar bekerja di initial load
        attachRowDelegation();

        // Handle form submission
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });

        // Handle clear search
        if (clearSearch) {
            clearSearch.addEventListener('click', function(e) {
                e.preventDefault();
                searchInput.value = '';
                performSearch();
            });
        }

        // Handle input with debounce
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performSearch, doneTypingInterval);
        });

        // Handle per page change
        const perPageSelect = document.querySelector('.per-page-selector select');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                performSearch();
            });
        }

        // Handle pagination clicks
        document.addEventListener('click', function(e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const url = e.target.closest('a').href;
                fetchPage(url);
            }
        });

        function performSearch() {
            const searchValue = searchInput.value.trim();
            const perPage = perPageSelect ? perPageSelect.value : 10;
            const url = new URL('{{ route("order-list") }}');
            
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            }
            
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('ajax', '1');
            
            fetchPage(url);
            
            // Update URL without page reload
            const newUrl = new URL(window.location.href);
            if (searchValue) {
                newUrl.searchParams.set('search', searchValue);
            } else {
                newUrl.searchParams.delete('search');
            }
            newUrl.searchParams.set('per_page', perPage);
            window.history.pushState({}, '', newUrl);
        }

        function fetchPage(url) {
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                }
            })
            .then(response => response.text())
            .then(html => {
                // Create a temporary container to parse the response
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Extract and update the order list container
                const newOrderList = tempDiv.querySelector('#orderListContainer') || tempDiv;
                orderListContainer.innerHTML = newOrderList.innerHTML;
                
                // Delegation cukup dipasang sekali, tidak perlu re-init
                attachRowDelegation();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });

    // Add loading animation for download button
    document.addEventListener('DOMContentLoaded', function() {
        const downloadBtn = document.querySelector('.btn-download-pdf');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function(e) {
                const btn = this;
                const originalText = btn.innerHTML;
                
                btn.innerHTML = '<span style="display:inline-block;width:16px;height:16px;border:2px solid rgba(255,255,255,0.3);border-radius:50%;border-top-color:#fff;animation:spin 0.8s linear infinite;margin-right:8px;"></span>Generating...';
                btn.style.pointerEvents = 'none';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.pointerEvents = 'auto';
                }, 2000);
            });
        }

        // Add CSS animation for loading spinner
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    });

    // Function to show email modal
    function sendEmail() {
        const modal = document.getElementById('emailModal');
        const errorMessage = document.getElementById('emailErrorMessage');
        const successMessage = document.getElementById('emailSuccessMessage');
        
        // Reset form and messages
        document.getElementById('emailForm').reset();
        errorMessage.style.display = 'none';
        successMessage.style.display = 'none';
        
        // Show modal
        modal.style.display = 'block';
    }

    // Function to hide email modal
    function hideEmailModal() {
        const modal = document.getElementById('emailModal');
        modal.style.display = 'none';
    }

    // Function to show notification (similar to login page)
    function showNotification(type, message) {
        // Remove existing notification if any
        const existingNotification = document.getElementById('emailNotification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.id = 'emailNotification';
        notification.className = `notification ${type}-notification`;
        
        const icon = type === 'success' 
            ? '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>'
            : '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
        
        notification.innerHTML = `
            <div class="notification-content">
                ${icon}
                <span>${message}</span>
            </div>
        `;
        
        // Append to body to ensure it's above the modal
        document.body.appendChild(notification);
        
        // Force z-index to ensure it's above modal
        notification.style.zIndex = '10000';
        
        // Show notification after a short delay
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);

        // Auto-hide after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            
            // Remove from DOM after animation completes
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 3000);

        // Allow manual close
        notification.addEventListener('click', function () {
            this.classList.remove('show');
            setTimeout(() => this.remove(), 500);
        });
    }

    // Handle email form submission
    document.addEventListener('DOMContentLoaded', function() {
        const emailForm = document.getElementById('emailForm');
        const emailModal = document.getElementById('emailModal');
        const errorMessage = document.getElementById('emailErrorMessage');
        const successMessage = document.getElementById('emailSuccessMessage');
        const submitBtn = document.getElementById('emailSubmitBtn');
        const btnText = submitBtn ? submitBtn.querySelector('.btn-text') : null;
        
        if (emailForm && submitBtn) {
            emailForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Hide previous messages
                errorMessage.style.display = 'none';
                successMessage.style.display = 'none';
                
                // Disable submit button
                submitBtn.disabled = true;
                if (btnText) btnText.textContent = 'Mengirim...';
                
                // Get email form data
                const emailFormData = new FormData(emailForm);
                
                // Build form data
                const formData = new FormData();
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || emailFormData.get('_token');
                if (csrfToken) {
                    formData.append('_token', csrfToken);
                }
                
                // Add email form fields
                formData.append('email', emailFormData.get('email'));
                formData.append('sender_name', emailFormData.get('sender_name'));
                if (emailFormData.get('description')) {
                    formData.append('description', emailFormData.get('description'));
                }
                
                // Send AJAX request
                fetch('{{ route("order-list.send-email") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        successMessage.textContent = data.message;
                        successMessage.style.display = 'block';
                        
                        // Reset form after 2 seconds and close modal
                        setTimeout(() => {
                            emailForm.reset();
                            hideEmailModal();
                        }, 2000);
                    } else {
                        errorMessage.textContent = data.message || 'Gagal mengirim email';
                        errorMessage.style.display = 'block';
                    }
                })
                .catch(error => {
                    errorMessage.textContent = 'Terjadi kesalahan: ' + error.message;
                    errorMessage.style.display = 'block';
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    if (btnText) btnText.textContent = 'Kirim Email';
                });
            });
        }
        
        // Close modal when clicking outside
        if (emailModal) {
            emailModal.addEventListener('click', function(e) {
                if (e.target === emailModal) {
                    hideEmailModal();
                }
            });
        }
    });
    </script>
</div>

<!-- Email Modal -->
<div id="emailModal" class="email-modal">
    <div class="email-modal-content">
        <div class="email-modal-header">
            <h2 class="email-modal-title">Kirim Daily Sales Report via Email</h2>
            <button class="email-close-btn" onclick="hideEmailModal()">×</button>
        </div>
        <form id="emailForm">
            @csrf
            <div class="email-form-group">
                <label for="email_to">Alamat Email Tujuan *</label>
                <input type="email" name="email" id="email_to" required placeholder="contoh@email.com">
            </div>
            <div class="email-form-group">
                <label for="sender_name">Nama Pengirim *</label>
                <input type="text" name="sender_name" id="sender_name" required placeholder="Masukkan nama pengirim">
            </div>
            <div class="email-form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" placeholder="Masukkan deskripsi (opsional)"></textarea>
            </div>
            <button type="submit" class="email-submit-btn" id="emailSubmitBtn">
                <span class="btn-text">Kirim Email</span>
            </button>
            <div id="emailErrorMessage" class="email-error-message"></div>
            <div id="emailSuccessMessage" class="email-success-message"></div>
        </form>
    </div>
</div>
@endsection
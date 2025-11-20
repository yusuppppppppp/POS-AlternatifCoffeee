@extends('layouts.app')
@section('title', 'Sales Report')

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
        text-align: left;
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
        margin-bottom: 24px;
        position: relative;
        z-index: 1;
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
    
    .no-orders {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
        font-style: italic;
        font-size: 16px;
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

    /* PDF Download Section */
    .pdf-download-section {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 
            0 4px 20px rgba(0, 0, 0, 0.06),
            0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(226, 232, 240, 0.4);
        margin-bottom: 24px;
    }
    
    .pdf-download-section h3 {
        margin-bottom: 20px;
        color: #2E4766;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pdf-download-form {
        display: flex;
        gap: 16px;
        align-items: center;
        flex-wrap: wrap;
    }

    .period-selector {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .period-selector label {
        font-weight: 600;
        color: #495057;
        font-size: 14px;
    }

    .custom-date-range {
        display: none;
        gap: 8px;
        align-items: center;
    }

    .custom-date-range span {
        color: #6c757d;
        font-weight: 500;
    }
    
    
    /* Simplified Order Details Section */
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

    /* Print Receipt Button Styles */
    .print-receipt-section {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 2px solid #e2e8f0;
        text-align: end;
    }

    .print-receipt-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
        color: white;
        border: none;
        border-radius: 7px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(46, 71, 102, 0.3);
    }

    .print-receipt-btn:hover {
        background: linear-gradient(135deg, #1e3a5f 0%, #2E4766 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(46, 71, 102, 0.4);
    }

    .print-receipt-btn:active {
        transform: translateY(0);
    }

    /* Receipt Modal Styles */
    #receiptModal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(46, 71, 102, 0.8);
        backdrop-filter: blur(8px);
        z-index: 3000;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
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

    .modal-container {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 24px;
        margin-top: 70px;
        padding: 40px 32px;
        min-width: 380px;
        max-width: 90vw;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 
            0 25px 50px rgba(46, 71, 102, 0.25),
            0 0 0 1px rgba(255, 255, 255, 0.1);
        position: relative;
        animation: slideUp 0.4s ease-out;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .modal-container::-webkit-scrollbar {
        display: none;
    }

    .modal-header {
        text-align: center;
        margin-bottom: 32px;
        position: relative;
    }

    .modal-header::before {
        content: '';
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #2E4766, #4a6b8a);
        border-radius: 2px;
    }

    .modal-title {
        color: #2E4766;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        margin-bottom: 8px;
    }

    .modal-subtitle {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0;
    }

    #receiptModalContent {
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: inset 0 2px 4px rgba(46, 71, 102, 0.05);
        min-height: 200px;
        position: relative;
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    #receiptModalContent::-webkit-scrollbar {
        display: none;
    }

    #receiptModalContent::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #2E4766, #4a6b8a, #2E4766);
        overflow-y: auto;
    }

    .button-group {
        display: flex;
        gap: 12px;
        flex-direction: column;
    }

    .modal-button {
        padding: 14px 0;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .modal-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .modal-button:hover::before {
        left: 100%;
    }

    .print-button {
        background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
        color: #fff;
        box-shadow: 
            0 4px 15px rgba(46, 71, 102, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .print-button:hover {
        transform: translateY(-2px);
        box-shadow: 
            0 6px 20px rgba(46, 71, 102, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .print-button:active {
        transform: translateY(0);
    }

    .close-button {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: #fff;
        box-shadow: 
            0 4px 15px rgba(100, 116, 139, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .close-button:hover {
        transform: translateY(-2px);
        box-shadow: 
            0 6px 20px rgba(100, 116, 139, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
    }

    .close-button:active {
        transform: translateY(0);
    }

    .close-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 32px;
        height: 32px;
        border: none;
        background: rgba(46, 71, 102, 0.1);
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2E4766;
        font-size: 18px;
        transition: all 0.3s ease;
    }

    .close-icon:hover {
        background: rgba(46, 71, 102, 0.2);
        transform: rotate(90deg);
    }

    .receipt-paper {
        font-family: 'Courier New', monospace;
        width: 270px;
        margin: 0 auto;
        font-size: 10px;
        line-height: 1.2;
        color: #2E4766;
    }

    .receipt-paper h2 {
        font-size: 12px;
        font-weight: bold;
        text-align: center;
        margin: 0 0 5px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .receipt-paper hr {
        border: none;
        border-top: 1px dashed #000;
        margin: 4px 0;
    }

    .receipt-paper .line {
        display: flex;
        justify-content: space-between;
        padding: 1px 0;
        font-size: 10px;
        border-bottom: 1px dotted #000;
    }

    .receipt-paper .line:last-child {
        border-bottom: none;
    }

    .receipt-paper .datetime {
        text-align: center;
        font-size: 9px;
        margin: 4px 0 0 0;
        color: #444;
        font-style: italic;
    }

    #receiptItems {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    #receiptItems li {
        margin: 2px 0;
    }

    #receiptItems .line {
        display: flex;
        justify-content: flex-start;
        gap: 8px;
    }

    #receiptItems .item-info {
        flex: 1;
        text-align: left;
    }

    #receiptItems .item-price {
        text-align: right;
        min-width: 80px;
    }

    .receipt-address {
        text-align: center;
        font-size: 10px;
        margin: 2px 0;
    }

    .receipt-instagram {
        text-align: center;
        font-size: 10px;
        margin: 2px 0 20px 0;
        font-style: italic;
    }

    /* Search Container */
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

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }

    .filter-section h4 {
        color: #495057;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .filter-form {
        display: flex;
        gap: 16px;
        align-items: center;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .filter-form input[type="date"], 
    .filter-form input[type="number"],
    .pdf-download-form select,
    .pdf-download-form input[type="date"] {
        padding: 10px 14px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        font-size: 14px;
        transition: all 0.3s ease;
        min-width: 140px;
        color: #374151;
    }
    
    .filter-form input[type="date"]:focus,
    .filter-form input[type="number"]:focus,
    .pdf-download-form select:focus,
    .pdf-download-form input[type="date"]:focus {
        border-color: #2E4766;
        background: white;
        box-shadow: 0 0 0 3px rgba(46, 71, 102, 0.1);
        outline: none;
    }

    .pdf-download-form input[type="date"] {
        color: #374151;
        min-width: 150px;
    }

    .pdf-download-form input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(30%) sepia(12%) saturate(760%) hue-rotate(166deg) brightness(92%) contrast(89%);
        cursor: pointer;
    }

    .filter-form button, 
    .filter-form a.reset-btn,
    .pdf-download-form button,
    .pdf-download-form a.reset-btn {
        padding: 12px 24px;
        font-weight: 600;
        font-size: 14px;
        border-radius: 8px;
        transition: all 0.3s ease;
        outline: none;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .filter-form button,
    .pdf-download-form button[type="submit"] {
        background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(46, 71, 102, 0.2);
    }

    .filter-form button:hover,
    .pdf-download-form button[type="submit"]:hover {
        background: linear-gradient(135deg, #1e3a5f 0%, #2E4766 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 71, 102, 0.3);
    }

    .pdf-download-form button[type="button"] {
        background: linear-gradient(135deg, #2E4766 0%,rgb(21, 28, 37) 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(71, 96, 127, 0.2);
    }

    .pdf-download-form button[type="button"]:hover {
        background: linear-gradient(135deg, #2E47667 0%,rgb(8, 12, 17) 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(71, 96, 127, 0.2);
    }

    .filter-form a.reset-btn,
    .pdf-download-form a.reset-btn {
        background: #f3f4f6;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .filter-form a.reset-btn:hover,
    .pdf-download-form a.reset-btn:hover {
        background: #e5e7eb;
        color: #374151;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* PDF Download Section */
    .pdf-download-section {
        margin-bottom: 25px;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(46, 71, 102, 0.1);
        border: 1px solid rgba(46, 71, 102, 0.1);
        position: relative;
        z-index: 1;
    }
    
    .pdf-download-section h3 {
        margin-bottom: 16px;
        color: #2E4766;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .pdf-download-section h3::before {
        font-size: 20px;
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

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb {
        background: #2E4766;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #1a2f42;
    }
</style>

<div class="order-container">
    <!-- Enhanced Header -->
    <div class="page-header">
        <h2 class="page-title">Sales Report</h2>
        <p class="page-subtitle">Manage and monitor all sales transactions</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="stat-card orders">
            <h3>{{ $totalOrdersThisMonth ?? 0 }}</h3>
            <p>Total Orders This Month</p>
        </div>
        <div class="stat-card revenue">
            <h3>Rp {{ number_format($totalRevenueThisMonth ?? 0, 0, ',', '.') }}</h3>
            <p>Total Income This Month</p>
        </div>
    </div>

    <!-- Filter & Download Section -->
    <div class="pdf-download-section">
        <h3>Filter & Download PDF</h3>
        <form method="GET" action="{{ route('sales-report') }}" class="pdf-download-form" id="filterForm">
            <div class="period-selector">
                <label for="filter_type">Filter Type:</label>
                <select name="filter_type" id="filter_type">
                    <option value="single" {{ request('filter_type', 'single') == 'single' ? 'selected' : '' }}>Single Date</option>
                    <option value="range" {{ request('filter_type') == 'range' ? 'selected' : '' }}>Date Range</option>
                    <option value="week" {{ request('filter_type') == 'week' ? 'selected' : '' }}>Mingguan</option>
                    <option value="month" {{ request('filter_type') == 'month' ? 'selected' : '' }}>Bulanan</option>
                </select>
            </div>
            
            <div class="single-date-filter" id="singleDateFilter" style="{{ in_array(request('filter_type'), ['range', 'week', 'month']) ? 'display: none;' : 'display: flex;' }} gap: 8px; align-items: center;">
                <input type="date" name="date" value="{{ request('date') }}" placeholder="Tanggal">
            </div>
            
            <div class="custom-date-range" id="dateRangeFilter" style="{{ request('filter_type') == 'range' ? 'display: flex;' : 'display: none;' }}">
                <input type="date" name="start_date" value="{{ request('start_date') }}" placeholder="Tanggal Mulai">
                <span>to</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" placeholder="Tanggal Akhir">
            </div>
            
            @if(!empty($search ?? ''))
                <input type="hidden" name="search" value="{{ $search }}">
            @endif
            
            <button type="submit" name="action" value="filter">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4C3 3.44772 3.44772 3 4 3H20C20.5523 3 21 3.44772 21 4V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V4Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M8 12H16M8 8H16M8 16H12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Apply Filter
            </button>
            
            <button type="button" onclick="downloadPDF()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Download PDF
            </button>
            
            <a href="{{ route('sales-report') }}" class="reset-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Reset
            </a>
        </form>
    </div>
    
    <!-- Search Form -->
    <div class="search-container">
        <form id="salesSearchForm" method="GET" action="{{ route('sales-report') }}" class="search-form">
            <div class="search-input-group">
                <input 
                    type="text" 
                    name="search" 
                    id="salesSearchInput"
                    value="{{ $search ?? '' }}" 
                    placeholder="Search by customer name, order type, ID, total amount, cashier name, or menu name..."
                    class="search-input"
                >
                <button type="submit" class="search-button">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            @if(!empty($search ?? ''))
                <a href="{{ route('sales-report') }}" class="clear-search" id="salesClearSearch">Clear Search</a>
            @endif
        </form>
    </div>

    <div id="salesReportContainer">
    @if(isset($orders) && $orders->count() > 0)
        <div class="order-table">
            <div class="table-header">
                <div>No.</div>
                <div>Order ID</div>
                <div>Customer</div>
                <div>Order Type</div>
                <div>Date</div>
                <div>Total Amount</div>
                <div>Actions</div>
            </div>
            @foreach($orders as $index => $order)
                <div class="order-row" onclick="toggleOrderDetails({{ $index }})">
                    <div class="order-number">{{ $loop->iteration }}</div>
                    <div class="order-id">{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
                    <div class="customer-name">{{ $order->customer_name }}</div>
                    <div class="order-type">{{ $order->order_type }}</div>
                    <div class="order-date">{{ $order->created_at->format('n/j/Y') }}</div>
                    <div class="total-amount">Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                    <div class="info-icon">
                        <img src="{{ asset('images/info.png') }}" alt="Info" style="height:24px;width:24px;">
                    </div>
                </div>
                <!-- Simplified order details -->
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
                    
                    <!-- Print Receipt Button -->
                    <div class="print-receipt-section">
                        <button class="print-receipt-btn" onclick="printReceiptDirectly({{ $order->id }})">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 9V2C6 1.44772 6.44772 1 7 1H17C17.5523 1 18 1.44772 18 2V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6 18H4C3.44772 18 3 17.5523 3 17V11C3 10.4477 3.44772 10 4 10H20C20.5523 10 21 10.4477 21 11V17C21 17.5523 20.5523 18 20 18H18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18 14H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6 14C6 13.4477 6.44772 13 7 13H17C17.5523 13 18 13.4477 18 14V20C18 20.5523 17.5523 21 17 21H7C6.44772 21 6 20.5523 6 20V14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Print Receipt
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
         <!-- Show Entries and Pagination -->
         <div class="pagination-container">
             <div class="pagination-info">
                 Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} entries
             </div>
             
             <div class="per-page-selector">
                 <label for="per_page">Show:</label>
                 <select id="per_page" onchange="changePerPage(this.value)">
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
             {{ $orders->appends([
                 'per_page' => $perPage, 
                 'search' => $search ?? '', 
                 'date' => request('date'),
                 'filter_type' => request('filter_type'),
                 'start_date' => request('start_date'),
                 'end_date' => request('end_date')
             ])->links() }}
         </div>
    @else
        <div class="order-table">
            <p class="no-orders">Tidak ada data penjualan.</p>
        </div>
    @endif
    </div>
</div>


<script>
// Make orders data available to JavaScript
window.ordersData = @json($orders ?? []);

// ==== AJAX Search/Filter/Pagination for Sales Report ====
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('salesReportContainer');
    const searchForm = document.getElementById('salesSearchForm');
    const searchInput = document.getElementById('salesSearchInput');
    const perPageSelect = document.getElementById('per_page');
    const clearSearchLink = document.getElementById('salesClearSearch');
    const filterTypeSelect = document.getElementById('filter_type');
    const singleDateInput = document.getElementById('date');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    let typingTimer;
    const doneTypingInterval = 10;

    function buildSalesUrl() {
        const url = new URL('{{ route("sales-report") }}', window.location.origin);
        const q = (searchInput?.value || '').trim();
        if (q) url.searchParams.set('search', q);
        if (perPageSelect) url.searchParams.set('per_page', perPageSelect.value);
        // preserve current filter selections
        const ft = filterTypeSelect?.value || (new URLSearchParams(window.location.search)).get('filter_type');
        if (ft) url.searchParams.set('filter_type', ft);
        if (ft === 'range') {
            const sd = startDateInput?.value;
            const ed = endDateInput?.value;
            if (sd) url.searchParams.set('start_date', sd);
            if (ed) url.searchParams.set('end_date', ed);
        } else if (ft === 'week' || ft === 'month') {
            // nothing extra
        } else {
            const d = singleDateInput?.value;
            if (d) url.searchParams.set('date', d);
        }
        return url;
    }

    function pushStateFrom(url) {
        const newUrl = new URL(window.location.href);
        newUrl.search = url.search;
        window.history.pushState({}, '', newUrl);
    }

    function tryUpdateOrdersDataFrom(html) {
        try {
            const match = html.match(/window\.ordersData\s*=\s*(\{[\s\S]*?\}|\[[\s\S]*?\]);/);
            if (match && match[1]) {
                const parsed = JSON.parse(match[1]);
                window.ordersData = parsed;
            }
        } catch (e) {
            // ignore
        }
    }

    function fetchSalesPage(url) {
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(res => res.text())
        .then(html => {
            const temp = document.createElement('div');
            temp.innerHTML = html;
            const newContainer = temp.querySelector('#salesReportContainer') || temp;
            if (container) container.innerHTML = newContainer.innerHTML;
            tryUpdateOrdersDataFrom(html);
        })
        .catch(err => console.error('Sales AJAX error:', err));
    }

    function performSalesSearch() {
        const url = buildSalesUrl();
        url.searchParams.delete('page');
        fetchSalesPage(url);
        pushStateFrom(url);
    }

    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSalesSearch();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performSalesSearch, doneTypingInterval);
        });
    }

    if (clearSearchLink) {
        clearSearchLink.addEventListener('click', function(e) {
            e.preventDefault();
            if (searchInput) searchInput.value = '';
            performSalesSearch();
        });
    }

    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            performSalesSearch();
        });
    }

    // Intercept pagination clicks inside the container
    document.addEventListener('click', function(e) {
        const a = e.target.closest('.pagination a');
        if (a && container && container.contains(a)) {
            e.preventDefault();
            const url = new URL(a.href);
            // rebuild current params from inputs
            const base = buildSalesUrl();
            for (const [k, v] of base.searchParams.entries()) {
                if (v) url.searchParams.set(k, v);
            }
            fetchSalesPage(url);
            pushStateFrom(url);
        }
    });

    // Override global changePerPage to use AJAX when available
    window.changePerPage = function(value) {
        if (perPageSelect) perPageSelect.value = value;
        const url = buildSalesUrl();
        url.searchParams.set('per_page', value);
        url.searchParams.delete('page');
        fetchSalesPage(url);
        pushStateFrom(url);
    };
});

// Direct Print Receipt Function
function printReceiptDirectly(orderId) {
    // Find the order data
    const order = window.ordersData.data ? window.ordersData.data.find(o => o.id === orderId) : null;
    
    if (!order) {
        alert('Order data not found');
        return;
    }
    
    // Format date and time
    const orderDate = new Date(order.created_at);
    const formattedTime = orderDate.toLocaleString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
    });
    
    const formattedDate = orderDate.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
    
    // Generate items list
    let itemsHtml = '';
    order.items.forEach(item => {
        itemsHtml += `
            <li>
                <div class="line">
                    <span class="item-info">${item.quantity}x ${item.menu_name}</span>
                    <span class="item-price">Rp. ${parseInt(item.price).toLocaleString()}</span>
                </div>
            </li>
        `;
    });
    
    // Calculate total from items
    const totalAmount = order.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
    
    // Generate receipt content
    let receiptContent = `
        <img src="{{ asset('images/LOGO2.png') }}" alt="Alternatif Coffee Logo" style="width:80px; height:auto; display:block; margin:0 auto 6px;" />
        <h2 style="text-align:center;">ALTERNATIF COFFEE</h2>
        <div class="receipt-address">Jalan P. Galang 7, Kasin, Klojen, Ciptomulyo, Kec. Sukun, Kota Malang, Jawa Timur 65117</div>
        <div class="receipt-instagram">@alternatifngopi</div>
        <hr>
        <ul id="receiptItems">
            ${itemsHtml}
        </ul>
        <hr>
        <div class="line"><span>Total</span><span>Rp. ${totalAmount.toLocaleString()}</span></div>
        <div class="line"><span>Cash</span><span>Rp. ${totalAmount.toLocaleString()}</span></div>
        <div class="line"><span>Balance</span><span>Rp. 0</span></div>
        <hr>
        <div class="line"><span>Customer</span><span>${order.customer_name}</span></div>
        <div class="line"><span>Kasir</span><span>${order.user ? order.user.name : 'Unknown'}</span></div>
        <div class="line"><span>Order</span><span>${order.order_type}</span></div>
    `;
    
    // Add transaction info to receiptItems list
    receiptContent = receiptContent.replace(
        /<\/ul>/,
        '<hr><li><div class="line"><span class="item-info">ID Transaksi</span><span class="item-price">' + String(order.id).padStart(3, '0') + '</span></div></li><li><div class="line"><span class="item-info">Waktu</span><span class="item-price">' + formattedTime + '</span></div></li></ul>'
    );
    
    // Open print window and print directly
    const printWindow = window.open('', '', 'height=700,width=550');
    
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Receipt</title>
            <base href="${window.location.origin}/">
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                .receipt-paper { width: 270px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { text-align: left; padding: 2px; }
                .qty { width: 20%; }
                .price { text-align: right; }
                /* Default line layout for summary rows (Total/Cash/Balance) */
                .line { display: flex; justify-content: space-between; }


                /* Items list: remove bullets/indent and align left */
                #receiptItems { list-style: none; padding: 0; margin: 0; }
                #receiptItems li { margin: 2px 0; }
                #receiptItems .line { justify-content: flex-start; gap: 8px; }
                #receiptItems .item-info { flex: 1; text-align: left; }
                #receiptItems .item-price { text-align: right; min-width: 80px; }
                .receipt-paper img { display: block; margin: 0 auto 6px; max-width: 100%; height: auto; }
                
                /* Header info styling */
                .receipt-paper h2 { margin-bottom: 2px; }
                .receipt-address { text-align: center; font-size: 10px; margin: 2px 0;}
                .receipt-instagram { text-align: center; font-size: 10px; margin: 2px 0 20px 0; font-style: italic; }
                
            </style>
        </head>
        <body>
            <div class="receipt-paper">${receiptContent}</div>
        </body>
        </html>
    `);

    printWindow.document.close();

    const doPrint = () => {
        try { printWindow.focus(); } catch (e) {}
        try { printWindow.print(); } catch (e) {}
        try { printWindow.close(); } catch (e) {}
    };

    const images = () => Array.from(printWindow.document.images || []);
    const imgs = images();
    if (imgs.length === 0) {
        doPrint();
        return;
    }

    let loadedCount = 0;
    const checkAllLoaded = () => {
        loadedCount++;
        if (loadedCount === imgs.length) {
            setTimeout(doPrint, 100);
        }
    };

    imgs.forEach(img => {
        if (img.complete) {
            checkAllLoaded();
        } else {
            img.onload = checkAllLoaded;
            img.onerror = checkAllLoaded;
        }
    });
}
function toggleOrderDetails(index) {
    const details = document.getElementById('details-' + index);
    const isShowing = details.classList.contains('show');
    document.querySelectorAll('.order-details').forEach(detail => {
        detail.classList.remove('show');
    });
    if (!isShowing) {
        details.classList.add('show');
    }
}

// Function to change per page
function changePerPage(value) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('per_page', value);
    currentUrl.searchParams.delete('page'); // Reset to first page when changing entries
    
    // Preserve all filter parameters
    const searchParam = currentUrl.searchParams.get('search');
    if (searchParam) {
        currentUrl.searchParams.set('search', searchParam);
    }
    
    const dateParam = currentUrl.searchParams.get('date');
    if (dateParam) {
        currentUrl.searchParams.set('date', dateParam);
    }
    
    const filterTypeParam = currentUrl.searchParams.get('filter_type');
    if (filterTypeParam) {
        currentUrl.searchParams.set('filter_type', filterTypeParam);
    }
    
    const startDateParam = currentUrl.searchParams.get('start_date');
    if (startDateParam) {
        currentUrl.searchParams.set('start_date', startDateParam);
    }
    
    const endDateParam = currentUrl.searchParams.get('end_date');
    if (endDateParam) {
        currentUrl.searchParams.set('end_date', endDateParam);
    }
    
    window.location.href = currentUrl.toString();
}

// Function to download PDF with current filter values
function downloadPDF() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    
    // Get current URL parameters
    const currentParams = new URLSearchParams(window.location.search);
    
    // Build query string from form data
    const params = new URLSearchParams();
    
    // Add filter_type
    const filterType = formData.get('filter_type') || 'single';
    params.append('filter_type', filterType);
    
    // Add date fields based on filter type
    if (filterType === 'range') {
        const startDate = formData.get('start_date');
        const endDate = formData.get('end_date');
        if (startDate) params.append('start_date', startDate);
        if (endDate) params.append('end_date', endDate);
    } else if (filterType === 'week' || filterType === 'month') {
        // Week and month don't need date parameters
    } else {
        const date = formData.get('date');
        if (date) params.append('date', date);
    }
    
    // Add search from form or current URL
    const search = formData.get('search') || currentParams.get('search');
    if (search) params.append('search', search);
    
    // Redirect to PDF download route with parameters
    window.location.href = '{{ route("sales-report.download-pdf") }}?' + params.toString();
}

// Function to handle filter type selection
document.addEventListener('DOMContentLoaded', function() {
    const filterTypeSelect = document.getElementById('filter_type');
    const singleDateFilter = document.getElementById('singleDateFilter');
    const dateRangeFilter = document.getElementById('dateRangeFilter');
    
    if (filterTypeSelect) {
        filterTypeSelect.addEventListener('change', function() {
            const filterType = this.value;
            if (filterType === 'range') {
                singleDateFilter.style.display = 'none';
                dateRangeFilter.style.display = 'flex';
            } else if (filterType === 'week' || filterType === 'month') {
                singleDateFilter.style.display = 'none';
                dateRangeFilter.style.display = 'none';
            } else {
                singleDateFilter.style.display = 'flex';
                dateRangeFilter.style.display = 'none';
            }
        });
    }

    // Receipt Modal Functions
    function showReceiptModal(orderId) {
        // Find the order data
        const order = window.ordersData.data ? window.ordersData.data.find(o => o.id === orderId) : null;
        
        if (!order) {
            alert('Order data not found');
            return;
        }
        
        // Store current order data for printing
        window.currentOrderData = order;
        
        // Generate receipt content
        generateReceiptContent(order);
        
        // Show modal
        const modal = document.getElementById('receiptModal');
        modal.style.display = 'flex';
    }
    
    function closeReceiptModal() {
        const modal = document.getElementById('receiptModal');
        modal.style.display = 'none';
    }
    
    function generateReceiptContent(order) {
        const receiptContent = document.getElementById('receiptModalContent');
        
        // Format date and time
        const orderDate = new Date(order.created_at);
        const formattedTime = orderDate.toLocaleString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
        
        const formattedDate = orderDate.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
        
        // Generate items list
        let itemsHtml = '';
        order.items.forEach(item => {
            itemsHtml += `
                <li>
                    <div class="line">
                        <span class="item-info">${item.quantity}x ${item.menu_name}</span>
                        <span class="item-price">Rp ${parseInt(item.price).toLocaleString()}</span>
                    </div>
                </li>
            `;
        });
        
        // Calculate total from items
        const totalAmount = order.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
        
        receiptContent.innerHTML = `
            <img src="{{ asset('images/logo.png') }}" alt="Alternatif Coffee Logo" style="width:80px; height:auto; display:block; margin:0 auto 6px;" />
            <h2 style="text-align:center;">ALTERNATIF COFFEE</h2>
            <div class="receipt-address">Jalan P. Galang 7, Kasin, Klojen, Ciptomulyo, Kec. Sukun, Kota Malang, Jawa Timur 65117</div>
            <div class="receipt-instagram">@alternatifngopi</div>
            <hr>
            <ul id="receiptItems">
                ${itemsHtml}
                <hr>
                <li><div class="line"><span class="item-info">ID Transaksi</span><span class="item-price">${String(order.id).padStart(3, '0')}</span></div></li>
                <li><div class="line"><span class="item-info">Waktu</span><span class="item-price">${formattedTime}</span></div></li>
            </ul>
            <hr>
            <div class="line"><span>Total</span><span>Rp ${totalAmount.toLocaleString()}</span></div>
            <div class="line"><span>Customer</span><span>${order.customer_name}</span></div>
            <div class="line"><span>Kasir</span><span>${order.user ? order.user.name : 'Unknown'}</span></div>
            <div class="line"><span>Order</span><span>${order.order_type}</span></div>
            <div class="datetime">${formattedDate} ${formattedTime}</div>
        `;
    }
    
    function printReceiptFromModal() {
        // Get the modal content and replace logo for printing
        let printContents = document.getElementById('receiptModalContent').innerHTML;
        // Replace logo.png with LOGO2.png for printing only
        printContents = printContents.replace(/images\/logo\.png/g, 'images/LOGO2.png');
        
        const printWindow = window.open('', '', 'height=700,width=550');
        
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Receipt</title>
                <base href="${window.location.origin}/">
                <style>
                    body { font-family: Arial, sans-serif; font-size: 12px; }
                    .receipt-paper { width: 270px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { text-align: left; padding: 2px; }
                    .qty { width: 20%; }
                    .price { text-align: right; }
                    .line { display: flex; justify-content: space-between; }
                    #receiptItems { list-style: none; padding: 0; margin: 0; }
                    #receiptItems li { margin: 2px 0; }
                    #receiptItems .line { justify-content: flex-start; gap: 8px; }
                    #receiptItems .item-info { flex: 1; text-align: left; }
                    #receiptItems .item-price { text-align: right; min-width: 80px; }
                    .receipt-paper img { display: block; margin: 0 auto 6px; max-width: 100%; height: auto; }
                    .receipt-paper h2 { margin-bottom: 2px; }
                    .receipt-address { text-align: center; font-size: 10px; margin: 2px 0;}
                    .receipt-instagram { text-align: center; font-size: 10px; margin: 2px 0 20px 0; font-style: italic; }
                    .datetime { text-align: center; font-size: 9px; margin: 4px 0 0 0; color: #444; font-style: italic; }
                    hr { border: none; border-top: 1px dashed #000; margin: 4px 0; }
                </style>
            </head>
            <body>
                <div class="receipt-paper">${printContents}</div>
            </body>
            </html>
        `);

        printWindow.document.close();

        const doPrint = () => {
            try { printWindow.focus(); } catch (e) {}
            try { printWindow.print(); } catch (e) {}
            try { printWindow.close(); } catch (e) {}
        };

        const images = () => Array.from(printWindow.document.images || []);
        const imgs = images();
        if (imgs.length === 0) {
            doPrint();
            return;
        }

        let loadedCount = 0;
        const checkAllLoaded = () => {
            loadedCount++;
            if (loadedCount === imgs.length) {
                setTimeout(doPrint, 100);
            }
        };

        imgs.forEach(img => {
            if (img.complete) {
                checkAllLoaded();
            } else {
                img.onload = checkAllLoaded;
                img.onerror = checkAllLoaded;
            }
        });
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('receiptModal');
        if (event.target === modal) {
            closeReceiptModal();
        }
    });
});
</script>
@endsection

@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background:rgba(135, 178, 248, 0.14);
        min-height: 100vh;
    }

    /* Main Container */
    .container {
        display: flex;
        padding: 20px;
        gap: 10px;
        max-width: 100%;
        margin: 0;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    /* Menu Section */
    .menu-section {
        flex: 1;
        padding-left: 50px;
        margin-top: 60px;
        min-width: 65rem;
        min-height: calc(100vh - 80px);
        transition: all 0.3s ease;
    }

    .container.drawer-open .menu-section {
        margin-left: -8rem;
        min-width: 55rem;
    }

    .container.drawer-open .menu-search-input {
        max-width: 200px;
        transition: max-width 0.3s ease;
    }

    .category-filters {
        display: flex;
        gap: 15px;
        margin-bottom: 0;
        flex-wrap: wrap;
    }

    .menu-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .menu-search { margin-bottom: 0; }
    .menu-search-wrapper { position: relative; z-index: 5; }
    .menu-search-input {
        width: 100%;
        max-width: 420px;
        padding: 12px 64px 12px 44px;
        border: 1px solid #c7d2fe; /* light indigo */
        border-radius: 9999px; /* pill */
        background: #ffffff;
        outline: none;
        color: #334155; /* slate-700 */
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08); /* subtle blue glow */
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
        position: relative;
        z-index: 1;
    }
    .menu-search-input:focus {
        border-color: #2E4766; /* blue-600 */
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
    }
    .menu-search-input::placeholder { color: #94a3b8; } /* slate-400 */
    .menu-search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        opacity: 1;
        pointer-events: none;
        z-index: 20 !important;
        color: #2E4766;
        font-size: 16px;
        line-height: 1;
    }
    .menu-clear-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 28px;
        height: 28px;
        border: none;
        border-radius: 50%;
        background: transparent;
        cursor: pointer;
        display: none;
        color: #7f8c8d;
        font-size: 18px;
        z-index: 21 !important;
    }
    .menu-clear-btn:hover { background: rgba(0,0,0,0.06); }
    .menu-clear-btn.visible { display: inline-flex; align-items: center; justify-content: center; }
    .result-count { font-size: 12px; color: #7f8c8d; margin-top: 6px; }
    

    .category-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 15px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        color: #2c3e50;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .category-btn.active,
    .category-btn:hover {
        background: #2c3e50;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .dropdown-indicator {
        margin-left: 8px;
        font-size: 12px;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        max-width: 100%;
        min-height: 800px !important;
        height: auto;
        transition: grid-template-columns 0.3s ease;
        align-content: start;
        grid-auto-rows: max-content;
        grid-template-rows: repeat(auto-fit, minmax(250px, auto));
    }

    .container.drawer-open .menu-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .menu-item {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .menu-item.inactive {
        background: rgba(200, 200, 200, 0.6);
        opacity: 0.5;
        cursor: not-allowed;
    }

    .menu-item.inactive img {
        filter: grayscale(100%);
    }

    .menu-item.inactive h3,
    .menu-item.inactive .price {
        color: #9ca3af;
    }

    .menu-item.inactive .menu-add-btn {
        background: rgba(156, 163, 175, 0.5);
        cursor: not-allowed;
        pointer-events: none;
    }

    .menu-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .menu-item-header {
        position: relative;
        width: 100%;
    }

    .menu-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .menu-add-btn {
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 50%;
        background: rgba(44, 62, 80, 0.9);
        color: white;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        flex-shrink: 0;
    }

    .menu-add-btn:hover {
        background: rgba(44, 62, 80, 1);
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .menu-item-info {
        padding: 15px;
    }

    .menu-item-info h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 5px;
    }

    .menu-item-info .price {
        color: #7f8c8d;
        font-size: 14px;
        font-weight: 500;
        margin: 0;
    }

    /* Bills Section */
    .bills-section {
        width: 300px;
        border-radius: 10px;
        flex-shrink: 0;
        position: fixed;
        right: 20px;
        top: 90px;
        height: calc(100vh - 110px);
        overflow-y: auto;
        box-shadow: 0 12px 40px 0 rgba(44, 62, 80, 0.25), 0 1.5px 8px 0 rgba(44, 62, 80, 0.10);
        transition: all 0.3s ease;
    }

    .container.drawer-open .bills-section {
        margin-left: 20px;
    }

    .bills-card {
        background: white;
        backdrop-filter: blur(10px);
        border-radius: 10px;
        padding: 25px;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .bills-title {
        font-size: 24px;
        font-weight: 700;
        color: #2E4766;
        margin-bottom: 20px;
    }

    .bill-items-container {
        flex: 1;
        overflow-y: auto;
        margin-bottom: 20px;
        min-height: 300px;
    }

    .bill-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 10px;
        background:rgba(85, 118, 158, 0.23);
    }

    .quantity-controls {
        display: flex;
        flex-direction: column;
        gap: 5px;
        align-items: center;
    }

    .quantity-btn {
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 50%;
        background: #2c3e50;
        color: white;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .quantity-btn:hover {
        background: #34495e;
        transform: scale(1.1);
    }

    .quantity {
        font-weight: 600;
        color: #2c3e50;
        min-width: 20px;
        text-align: center;
    }

    .bill-item img {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        object-fit: cover;
    }

    .bill-item-info {
        flex: 1;
    }

    .bill-item-name {
        font-weight: 600;
        color: #2E4766;
        margin-bottom: 3px;
    }

    .bill-item-price {
        color: #2E4766;
        font-size: 14px;
    }

    .checkout-section {
        border-top: 4px solid #2E4766;
        padding-top: 20px;
        margin-top: auto;
    }

    .total-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 15px;
        background:rgba(46, 71, 102, 0.09);
        border-radius: 10px;
    }

    .total-label {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
    }

    .total-amount {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
    }

    .checkout-btn {
        width: 48%;
        margin: 5px 1%;
        padding: 15px;
        border: none;
        border-radius: 15px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        /* background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); */ 
        background: #2c3e50;
        color: white;
        box-shadow: 0 5px 20px rgba(44, 62, 80, 0.3);
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(44, 62, 80, 0.4);
    }

    .checkout-btn:active {
        transform: translateY(0);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .container {
            flex-direction: column;
        }
        
        .bills-section {
            position: static;
            width: 100%;
            height: auto;
            margin-top: 20px;
        }
        
        .menu-section {
            padding-left: 0 !important;
        }
        
        .menu-grid {
            grid-template-columns: repeat(3, 1fr) !important;
            min-height: 800px !important;
        }
        
        /* Pastikan sidebar tidak mempengaruhi layout di mobile */
        .container.drawer-open {
            margin-left: 0 !important;
        }
    }

    @media (max-width: 768px) {
        .menu-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            min-height: 600px !important;
        }
        
        .category-filters {
            justify-content: center;
        }
        
        .bills-section {
            top: 90px;
            right: 10px;
            width: calc(100% - 20px);
        }
    }

    @media (max-width: 480px) {
        .menu-grid {
            grid-template-columns: 1fr !important;
            min-height: 500px !important;
        }
        
        .container {
            padding: 10px;
        }
        
        .bills-section {
            padding: 20px;
            position: static;
            width: 100%;
            margin-top: 20px;
        }
    }

    /* Scrollbar Styling */
    .bill-items-container::-webkit-scrollbar {
        width: 6px;
    }

    .bill-items-container::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .bill-items-container::-webkit-scrollbar-thumb {
        background: #2c3e50;
        border-radius: 10px;
    }

    .bills-section::-webkit-scrollbar {
        width: 6px;
    }

    .bills-section::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .bills-section::-webkit-scrollbar-thumb {
        background: #2c3e50;
        border-radius: 10px;
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

    .sample-receipt {
        color: #2E4766;
        line-height: 1.6;
    }

    .sample-receipt h3 {
        margin: 0 0 16px 0;
        color: #2E4766;
        font-weight: 700;
        text-align: center;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 12px;
    }

    .sample-receipt .item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        padding: 4px 0;
    }

    .sample-receipt .total {
        border-top: 2px solid #2E4766;
        margin-top: 16px;
        padding-top: 12px;
        font-weight: 700;
        font-size: 1.1rem;
    }

    @media (max-width: 480px) {
        .modal-container {
            margin: 20px;
            padding: 32px 24px;
            min-width: auto;
        }
        
        .button-group {
            flex-direction: column;
        }
    }

    /* Loading animation for demo */
    .loading {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 0.8s ease-in-out infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Existing Payment Modal Styles */
    .payment-modal {
        position: fixed;
        top: 150px; left: 1045px; right: 0; bottom: 0;
        background: rgba(255, 255, 255, 0);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .payment-modal-content {
        background: #fff;
        border-radius: 18px;
        padding: 32px 18px 18px 18px;
        width: 350px;
        max-width: 95vw;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
        align-items: stretch;
        max-height: 87vh;
    }
    .payment-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 10px;
        margin-top: -10px;
    }

    .user-info {
        flex: 1;
    }

    .input-label {
        display: block;
        font-size: 14px;
        font-weight: 770;
        color:rgb(0, 0, 0);
        margin-bottom: 6px;
    }

    .required {
        color: #ef4444;
    }

    .payment-user-name {
        width: 100%;
        padding: 10px 12px;
        font-size: 16px;
        color: #111827;
        background: #f9fafb;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        transition: border-color 0.2s;
    }

    .payment-user-name:focus {
        border-color: #3b82f6;
        background: white;
    }

    .payment-user-name::placeholder {
        color: #9ca3af;
    }
    .payment-summary {
        background: #fff;
        padding: 10px 0 10px 0;
        margin-bottom: 10px;
        max-height: 200px;
        overflow-y: auto;
    }
    #payment-items {
        margin-bottom: 8px;
    }
    .payment-summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2px;
    }
    .payment-subtotal-row, .payment-cash-row, .payment-balance-row {
        display: flex;
        justify-content: space-between;
        margin-top: 4px;
    }
    .payment-cash-input {
        margin: 10px 0 10px 0;
    }
    .payment-cash-input input {
        width: 100%;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 1rem;
        margin-top: 2px;
        background: #f8fafc;
    }
    .payment-type-btns {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }
    .type-btn {
        flex: 1;
        padding: 10px 0;
        border-radius: 8px;
        border: 2px solid #2c3e50;
        background: #fff;
        color: #2c3e50;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }
    .type-btn.active, .type-btn:hover {
        background: #2c3e50;
        color: #fff;
    }
    .payment-keypad {
        margin-top: 8px;
    }
    .keypad-row {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
    }
    .keypad-btn {
        flex: 1;
        padding: 14px 0;
        border-radius: 8px;
        border: 1px solid #2c3e50;
        background: #f8fafc;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .keypad-btn:active {
        background: #2c3e50;
        color: #fff;
    }
    @media (max-width: 480px) {
        .payment-header {
            gap: 12px;
        }
        .payment-user-name {
            width: 100%;
            padding: 10px 12px;
            font-size: 16px;
            color: #111827;
            background: #f9fafb;
        }
    }

    @media (max-width: 400px) {
        .payment-modal-content { width: 98vw; padding: 10px 2vw; }
    }

    /* Payment Modal Close Icon */
    .payment-close-icon {
        position: absolute;
        top: -15px;
        left: 280px;
        width: 32px;
        height: 32px;
        border: none;
        background: rgba(44, 62, 80, 0.1);
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2c3e50;
        font-size: 20px;
        font-weight: bold;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .payment-close-icon:hover {
        background: rgba(44, 62, 80, 0.2);
        color: #1a252f;
    }

    .payment-modal-content {
        position: relative;
    }

    .receipt-paper {
      font-family: 'Courier New', monospace;
      width: 74mm;
      margin: 0 auto;
      font-size: 10px;
      line-height: 1.2;
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

    .receipt-paper .item-list {
      width: 100%;
      border-collapse: collapse;
      font-size: 10px;
    }

    .receipt-paper .item-list th,
    .receipt-paper .item-list td {
      padding: -10px 0;
      vertical-align: top;
    }

    .receipt-paper .item-list th.qty,
    .receipt-paper .item-list td.qty {
      width: 10mm;
      text-align: center;
    }

    .receipt-paper .item-list th.name,
    .receipt-paper .item-list td.name {
      width: 44mm;
      text-align: left;
      word-wrap: break-word;
    }

    .receipt-paper .item-list th.price,
    .receipt-paper .item-list td.price {
      width: 15mm;
      text-align: right;
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

    @media print {
      @page {
        size: 80mm auto;
        margin: 0;
      }
      body {
        margin: 0;
        padding: 0;
        background: #fff !important;
      }
      .receipt-paper {
        width: 74mm !important;
      }
      .item-list {
        page-break-inside: avoid;
      }
    }
</style>



<div class="container" id="container">
        <!-- Menu -->
        <div class="menu-section">
            <div class="menu-header">
                <div class="category-filters" id="categoryFilters">
                    <button class="category-btn active" onclick="filterCategory('All')">All Category</button>
                    <!-- Categories will be loaded dynamically -->
                </div>

                <div class="menu-search">
                    <div class="menu-search-wrapper">
                        <i class="fa-solid fa-magnifying-glass menu-search-icon"></i>
                        <input type="text" id="menuSearchInput" class="menu-search-input" placeholder="Search products..." aria-label="Search products" oninput="onSearchInput(event)" />
                    </div>
                </div>
            </div>

            <div class="menu-grid" id="menuGrid">
                <!-- Menu items will be loaded dynamically -->
            </div>
        </div>

    <!-- Bills -->
    <div class="bills-section">
        <div class="bills-card">
            <h2 class="bills-title">Bills</h2>
            <div class="bill-items-container" id="bill-items"></div>
            <div class="checkout-section">
                <div class="total-section">
                    <div class="total-label">Total</div>
                    <div class="total-amount" id="total-amount">Rp. 0</div>
                </div>
                <button class="checkout-btn" onclick="checkoutAndPrint()">Checkout</button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="payment-modal" style="display:none; margin-top: -80px;">
    <div class="payment-modal-content">
        <button class="payment-close-icon" onclick="closePaymentModal()">×</button>
        <div class="payment-header">
            <div class="user-info">
                <label for="customerNameInput" class="input-label">
                    Nama Customer <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="customerNameInput" 
                    class="payment-user-name" 
                    placeholder="Masukkan nama customer"
                    required 
                />
            </div>
        </div>
        <div class="payment-summary">
            <h3>Payment Summary</h3>
            <div id="payment-items"></div>
            <div class="payment-subtotal-row"><b>Subtotal</b> <span id="payment-subtotal"></span></div>
            <div class="payment-balance-row"><b>Balance</b> <span id="payment-balance"></span></div>
        </div>
        <div class="payment-cash-input">
            <label for="cashInput"><b>Cash</b></label>
            <input id="cashInput" type="text" placeholder="Enter the amount of cash here" />
        </div>
        <div class="payment-type-btns">
            <button id="dineInBtn" class="type-btn active">Dine in</button>
            <button id="takeAwayBtn" class="type-btn">Take Away</button>
        </div>
        <div class="payment-keypad">
            <div class="keypad-row">
                <button class="keypad-btn">1</button>
                <button class="keypad-btn">2</button>
                <button class="keypad-btn">3</button>
            </div>
            <div class="keypad-row">
                <button class="keypad-btn">4</button>
                <button class="keypad-btn">5</button>
                <button class="keypad-btn">6</button>
            </div>
            <div class="keypad-row">
                <button class="keypad-btn">7</button>
                <button class="keypad-btn">8</button>
                <button class="keypad-btn">9</button>
            </div>
            <div class="keypad-row">
            <button class="keypad-btn" onclick="printReceiptAndReset()">Enter</button>
                <button class="keypad-btn">0</button>
                <button class="keypad-btn">⌫</button>
            </div>
        </div>
    </div>
</div>

<div id="receiptContent" class="receipt-paper" style="display: none;">
    <img src="{{ asset('images/logo.png') }}" alt="Alternatif Coffee Logo" style="width:80px; height:auto; display:block; margin:0 auto 6px;" />
    <h2 style="text-align:center;">Alternatif Coffee</h2>
    <hr>
    <ul id="receiptItems"></ul>
    <hr>
    <div class="line"><span>Total</span><span class="payment-total"></span></div>
    <div class="line"><span>Cash</span><span class="payment-cash"></span></div>
    <div class="line"><span>Balance</span><span class="payment-balance"></span></div>
    <hr>
    <div class="line"><span>Customer</span><span id="receiptCustomerValue" class="value"></span></div>
    <div class="line"><span>Kasir</span><span id="receiptCashierValue" class="value"></span></div>
    <div class="line"><span>Order</span><span id="receiptOrderTypeValue" class="value"></span></div>
</div>

<!-- Receipt Modal -->
<div id="receiptModal">
    <div class="modal-container">
        <button class="close-icon" onclick="closeReceiptModal()">×</button>
        
        <div class="modal-header">
            <h3 class="modal-title">Receipt</h3>
            <p class="modal-subtitle">Detail transaksi pembayaran</p>
        </div>
        
        <div id="receiptModalContent" class="receipt-paper"></div>
        
        <div class="button-group">
            <button class="modal-button print-button" onclick="printReceiptFromModal()">
                <span class="loading" style="display:none;"></span>
                Print Receipt
            </button>
            <button class="modal-button close-button" onclick="closeReceiptModal()">
                Tutup
            </button>
        </div>
    </div>
</div>


<style>
    /* Receipt preview styles in modal */
    .receipt-paper { width: 270px; }
    #receiptItems { list-style: none; padding: 0; margin: 0; }
    #receiptItems li { margin: 2px 0; }
    #receiptItems .line { display: flex; justify-content: flex-start; gap: 8px; }
    #receiptItems .item-info { flex: 1; text-align: left; }
    #receiptItems .item-price { text-align: right; min-width: 80px; }
    /* Ensure label/value rows align like totals */
    .receipt-paper .line { display: flex; justify-content: space-between; }
    .receipt-paper .line .value { text-align: right; }
</style>

<script>
window.currentCashierName = @json(Auth::user()->name ?? 'Guest');
    let cart = {};
    let totalAmount = 0;
    let allMenus = [];
    let currentCategory = 'All';
    let currentSearchTerm = '';
    let searchDebounceTimer = null;

    function loadMenuItems() {
        fetch('/api/menus')
            .then(res => res.json())
            .then(data => {
                allMenus = data.map(menu => ({
                    ...menu,
                    image_url: menu.image_path ? `/storage/${menu.image_path}` : 'default.jpg'
                }));
                applyFilters();
            });
    }

    function loadCategories() {
        fetch('/api/categories')
            .then(res => res.json())
            .then(categories => {
                const categoryFilters = document.getElementById('categoryFilters');
                const allCategoryBtn = categoryFilters.querySelector('.category-btn');
                categoryFilters.innerHTML = '';
                categoryFilters.appendChild(allCategoryBtn);
                
                categories.forEach(category => {
                    const btn = document.createElement('button');
                    btn.className = 'category-btn';
                    btn.onclick = () => filterCategory(category.name);
                    btn.textContent = category.name;
                    categoryFilters.appendChild(btn);
                });
            });
    }

    function renderAllMenuItems() {
        const grid = document.getElementById('menuGrid');
        grid.innerHTML = '';
        
        const escapeHtml = (unsafe) => {
            if (unsafe == null) return '';
            return String(unsafe)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };

        allMenus.forEach((menu, index) => {
            const el = document.createElement('div');
            el.className = menu.is_active ? 'menu-item' : 'menu-item inactive';
            el.setAttribute('data-menu-id', menu.id);
            el.setAttribute('data-category', menu.category ? menu.category.name : '');
            el.setAttribute('data-name', menu.name.toLowerCase());
            
            if (menu.is_active) {
                el.onclick = () => addToCart(menu.name, menu.price, menu.image_url);
            } else {
                el.onclick = () => {
                    alert('Menu ini sedang tidak tersedia');
                };
            }
            
            const nameEsc = escapeHtml(menu.name);
            el.innerHTML = `
                <img src="${menu.image_url}" alt="${menu.name}">
                <div class="menu-item-info">
                    <h3>${nameEsc}</h3>
                    <div class="price-row">
                        <p class="price">Rp. ${parseInt(menu.price).toLocaleString()}</p>
                        <button class="menu-add-btn" ${!menu.is_active ? 'disabled' : ''}>+</button>
                    </div>
                </div>
            `;
            grid.appendChild(el);
        });
        menuItemsRendered = true;
    }

    function renderMenuItems(menus, searchTerm = '') {
        const grid = document.getElementById('menuGrid');
        grid.innerHTML = '';
        if (!menus || menus.length === 0) {
            grid.innerHTML = `
                <div class="empty-state" style="grid-column: 1 / -1; text-align:center; color:#7f8c8d;">
                    <div style="font-size:14px;">Tidak ada menu yang cocok.</div>
                </div>
            `;
            return;
        }

        const escapeHtml = (unsafe) => {
            if (unsafe == null) return '';
            return String(unsafe)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };

        const search = (searchTerm || '').toLowerCase();

        menus.forEach(menu => {
            const el = document.createElement('div');
            el.className = menu.is_active ? 'menu-item' : 'menu-item inactive';
            
            if (menu.is_active) {
                el.onclick = () => addToCart(menu.name, menu.price, menu.image_url);
            } else {
                el.onclick = () => {
                    alert('Menu ini sedang tidak tersedia');
                };
            }
            
            const nameEsc = escapeHtml(menu.name);
            let nameHtml = nameEsc;
            el.innerHTML = `
                <img src="${menu.image_url}" alt="${menu.name}">
                <div class="menu-item-info">
                    <h3>${nameHtml}</h3>
                    <div class="price-row">
                        <p class="price">Rp. ${parseInt(menu.price).toLocaleString()}</p>
                        <button class="menu-add-btn" ${!menu.is_active ? 'disabled' : ''}>+</button>
                    </div>
                </div>
            `;
            grid.appendChild(el);
        });
    }

    function filterCategory(category) {
        // Update active button
        document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
        if (window.event && window.event.target) {
            window.event.target.classList.add('active');
        } else {
            const matchBtn = Array.from(document.querySelectorAll('.category-btn')).find(b => b.textContent === category);
            if (matchBtn) matchBtn.classList.add('active');
        }

        currentCategory = category;
        applyFilters();
    }

    function onSearchInput(e) {
        const value = e.target.value || '';
        currentSearchTerm = value;
        toggleClearBtn();
        if (searchDebounceTimer) window.clearTimeout(searchDebounceTimer);
        searchDebounceTimer = window.setTimeout(() => {
            applyFilters();
        }, 200);
    }

    function applyFilters() {
        const search = currentSearchTerm.trim().toLowerCase();
        const filtered = allMenus.filter(menu => {
            const categoryOk = currentCategory === 'All' || (menu.category && menu.category.name === currentCategory);
            const textOk = !search || (menu.name && menu.name.toLowerCase().includes(search));
            return categoryOk && textOk;
        });
        
        // Sort menus: active first, then inactive
        const sorted = filtered.sort((a, b) => {
            if (a.is_active && !b.is_active) return -1;
            if (!a.is_active && b.is_active) return 1;
            return 0;
        });
        
        renderMenuItems(sorted, search);
        const countEl = document.getElementById('menuResultCount');
        if (countEl) {
            const base = `${filtered.length} menu`;
            countEl.textContent = search ? `${base} untuk "${currentSearchTerm}"` : base;
        }
    }

    function clearSearch() {
        const input = document.getElementById('menuSearchInput');
        if (input) {
            input.value = '';
        }
        currentSearchTerm = '';
        toggleClearBtn();
        applyFilters();
    }

    function toggleClearBtn() {
        const btn = document.getElementById('menuSearchClearBtn');
        if (btn) {
            btn.classList.toggle('visible', !!currentSearchTerm);
        }
    }

    function addToCart(name, price, image) {
        if (!cart[name]) {
            cart[name] = { name, price, quantity: 0, image };
        }
        cart[name].quantity++;
        updateBillDisplay();
    }

    function updateBillDisplay() {
        const bill = document.getElementById('bill-items');
        bill.innerHTML = '';
        totalAmount = 0;

        for (const name in cart) {
            const item = cart[name];
            totalAmount += item.price * item.quantity;

            const div = document.createElement('div');
            div.className = 'bill-item';
            div.innerHTML = `
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="updateQuantity('${name}', 1)">+</button>
                    <div class="quantity">${item.quantity}</div>
                    <button class="quantity-btn" onclick="updateQuantity('${name}', -1)">−</button>
                </div>
                <img src="${item.image}" alt="${item.name}">
                <div class="bill-item-info">
                    <div class="bill-item-name">${item.name}</div>
                    <div class="bill-item-price">Rp. ${parseInt(item.price).toLocaleString()}</div>
                </div>
            `;
            bill.appendChild(div);
        }

        document.getElementById('total-amount').textContent = `Rp. ${totalAmount.toLocaleString()}`;
    }

    function updateQuantity(name, change) {
        if (cart[name]) {
            cart[name].quantity += change;
            if (cart[name].quantity <= 0) delete cart[name];
            updateBillDisplay();
        }
    }

    function checkoutAndPrint() {
        if (Object.keys(cart).length === 0) {
            alert("Cart is empty!");
            return;
        }
        // Tampilkan modal pembayaran
        showPaymentModal();
    }

    function showPaymentModal() {
        // Sembunyikan bill section
        document.querySelector('.bills-section').style.display = 'none';
        // Tampilkan modal
        document.getElementById('paymentModal').style.display = 'flex';
        // Render ringkasan pembayaran
        renderPaymentSummary();
        // Reset input cash dan balance
        document.getElementById('cashInput').value = '';
        document.getElementById('payment-balance').textContent = '';
        // Set default tipe dine in
        document.getElementById('dineInBtn').classList.add('active');
        document.getElementById('takeAwayBtn').classList.remove('active');
    }

    function renderPaymentSummary() {
        const itemsDiv = document.getElementById('payment-items');
        itemsDiv.innerHTML = '';
        for (let name in cart) {
            const item = cart[name];
            const row = document.createElement('div');
            row.className = 'payment-summary-row';
            row.innerHTML = `<span>${item.name} ${item.quantity}x</span><span>Rp. ${(item.price * item.quantity).toLocaleString()}</span>`;
            itemsDiv.appendChild(row);
        }
        document.getElementById('payment-subtotal').textContent = 'Rp. ' + totalAmount.toLocaleString();
    }

    // Keypad logic
    let cashValue = '';
    document.querySelectorAll('.keypad-btn').forEach(btn => {
        btn.onclick = function() {
            const val = this.textContent;
            if (val === '⌫') {
                cashValue = cashValue.slice(0, -1);
            } else if (val === 'Enter') {
                // Validasi nama customer
                const customerName = document.getElementById('customerNameInput').value.trim();
                if (!customerName) {
                    alert('Nama Customer wajib diisi!');
                    document.getElementById('customerNameInput').focus();
                    return;
                }
                
                // Validasi panjang nama customer
                if (customerName.length < 2) {
                    alert('Nama Customer minimal 2 karakter!');
                    document.getElementById('customerNameInput').focus();
                    return;
                }
                
                if (customerName.length > 100) {
                    alert('Nama Customer maksimal 100 karakter!');
                    document.getElementById('customerNameInput').focus();
                    return;
                }
                // Hitung balance
                const cash = parseInt(cashValue || '0');
                const balance = cash - totalAmount;
                document.getElementById('payment-balance').textContent = 'Rp. ' + (balance >= 0 ? balance.toLocaleString() : '0');
                            // Jika cash cukup, lakukan print dan reset
            if (cash >= totalAmount) {
                setTimeout(() => {
                    printReceiptAndReset(cash, balance);
                }, 500);
            } else {
                // Tampilkan alert jika cash tidak cukup
                alert('Uang cash tidak cukup! Total: Rp. ' + totalAmount.toLocaleString() + ', Cash: Rp. ' + cash.toLocaleString() + ', Kurang: Rp. ' + (totalAmount - cash).toLocaleString());
                // Reset input cash
                cashValue = '';
                document.getElementById('cashInput').value = '';
                document.getElementById('payment-balance').textContent = '';
                document.getElementById('cashInput').focus();
            }
            } else {
                if (cashValue.length < 9) cashValue += val;
            }
            document.getElementById('cashInput').value = cashValue ? parseInt(cashValue).toLocaleString() : '';
        };
    });

    // Tipe pembayaran
    document.getElementById('dineInBtn').onclick = function() {
        this.classList.add('active');
        document.getElementById('takeAwayBtn').classList.remove('active');
    };
    document.getElementById('takeAwayBtn').onclick = function() {
        this.classList.add('active');
        document.getElementById('dineInBtn').classList.remove('active');
    };

    // Real-time customer name validation
    document.getElementById('customerNameInput').addEventListener('input', function() {
        const customerName = this.value.trim();
        const label = this.parentElement.querySelector('label');
        
        if (customerName.length === 0) {
            this.style.borderColor = '#e2e8f0';
            label.style.color = '#64748b';
        } else if (customerName.length < 2) {
            this.style.borderColor = '#f59e0b';
            label.style.color = '#f59e0b';
        } else if (customerName.length > 100) {
            this.style.borderColor = '#ef4444';
            label.style.color = '#ef4444';
        } else {
            this.style.borderColor = '#10b981';
            label.style.color = '#10b981';
        }
    });

    // Handle keyboard input for cash input field
    document.getElementById('cashInput').addEventListener('input', function() {
        // Remove non-numeric characters except for formatting
        let value = this.value.replace(/[^\d]/g, '');
        
        // Limit to 9 digits
        if (value.length > 9) {
            value = value.substring(0, 9);
        }
        
        // Update cashValue variable
        cashValue = value;
        
        // Format display with commas
        this.value = value ? parseInt(value).toLocaleString() : '';
        
        // Update balance in real-time
        const cash = parseInt(value || '0');
        const balance = cash - totalAmount;
        document.getElementById('payment-balance').textContent = 'Rp. ' + (balance >= 0 ? balance.toLocaleString() : '0');
    });

    // Handle Enter key press on cash input
    document.getElementById('cashInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            
            // Validate customer name
            const customerName = document.getElementById('customerNameInput').value.trim();
            if (!customerName) {
                alert('Nama Customer wajib diisi!');
                document.getElementById('customerNameInput').focus();
                return;
            }
            
            if (customerName.length < 2) {
                alert('Nama Customer minimal 2 karakter!');
                document.getElementById('customerNameInput').focus();
                return;
            }
            
            if (customerName.length > 100) {
                alert('Nama Customer maksimal 100 karakter!');
                document.getElementById('customerNameInput').focus();
                return;
            }
            
            // Get cash value and process payment
            const cash = parseInt(cashValue || '0');
            const balance = cash - totalAmount;
            
            if (cash >= totalAmount) {
                setTimeout(() => {
                    printReceiptAndReset(cash, balance);
                }, 500);
            } else {
                // Tampilkan alert jika cash tidak cukup
                alert('Uang cash tidak cukup! Total: Rp. ' + totalAmount.toLocaleString() + ', Cash: Rp. ' + cash.toLocaleString() + ', Kurang: Rp. ' + (totalAmount - cash).toLocaleString());
                // Reset input cash
                cashValue = '';
                document.getElementById('cashInput').value = '';
                document.getElementById('payment-balance').textContent = '';
                document.getElementById('cashInput').focus();
            }
        }
    });

    // Handle Enter key press on customer name input
    document.getElementById('customerNameInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            // Move focus to cash input when Enter is pressed on customer name
            document.getElementById('cashInput').focus();
        }
    });

    function printReceiptAndReset(cash, balance) {
    // Perbarui isi struk
    const receiptItems = document.getElementById('receiptItems');
    receiptItems.innerHTML = '';

    let total = 0;

    for (const itemKey in cart) {
        const item = cart[itemKey];
        const itemTotal = item.price * item.quantity;
        total += itemTotal;

        const li = document.createElement('li');
        li.innerHTML = `
            <div class="line">
                <span class="item-info">${item.quantity}x ${item.name}</span>
                <span class="item-price">Rp. ${itemTotal.toLocaleString()}</span>
            </div>`;
        receiptItems.appendChild(li);
    }

    total = Math.round(total); // pembulatan aman

    // Tampilkan ke struk
    document.querySelector('.payment-total').textContent = `Rp. ${total.toLocaleString()}`;
    document.querySelector('.payment-cash').textContent = `Rp. ${cash.toLocaleString()}`;
    document.querySelector('.payment-balance').textContent = `Rp. ${balance.toLocaleString()}`;
    // Set nama pembeli dan kasir di struk
    const customerName = document.getElementById('customerNameInput').value || 'Unknown';
    document.getElementById('receiptCustomerValue').textContent = customerName;
    document.getElementById('receiptCashierValue').textContent = (window.currentCashierName || 'Guest');
    // Set order type di struk
    const orderType = document.getElementById('dineInBtn')?.classList.contains('active') ? 'Dine in' : 'Take Away';
    document.getElementById('receiptOrderTypeValue').textContent = orderType;
    // Tampilkan struk di modal, bukan window baru
    const receiptModal = document.getElementById('receiptModal');
    const receiptModalContent = document.getElementById('receiptModalContent');
    receiptModalContent.innerHTML = document.getElementById('receiptContent').innerHTML;
    receiptModal.style.display = 'flex';

    // Simpan ke database via fetch POST
    fetch('/save-order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            customer_name: customerName,
            order_type: document.getElementById('dineInBtn')?.classList.contains('active') ? 'Dine in' : 'Take Away',
            total_amount: total,
            cash_paid: cash,
            balance: balance,
            items: Object.values(cart)
        })
    })
    .then(res => res.json())
    .then(data => {
        // Order berhasil disimpan, tidak perlu refresh langsung
        // Bisa refresh setelah tutup modal jika diinginkan
    })
    .catch(error => {
        console.error('Error saving order:', error);
        alert('Gagal menyimpan order!');
        window.location.reload();
    });

    // Reset cart dan tampilan
    cart = {};
    updateBillDisplay();
    document.querySelector('.payment-total').textContent = '';
    document.querySelector('.payment-cash').textContent = '';
    document.querySelector('.payment-balance').textContent = '';
    receiptItems.innerHTML = '';
}

function printReceiptFromModal() {
    const printContents = document.getElementById('receiptModalContent').innerHTML;
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
    const onDone = () => {
        loadedCount += 1;
        if (loadedCount >= imgs.length) {
            doPrint();
        }
    };
    imgs.forEach(img => {
        if (img.complete) {
            onDone();
        } else {
            img.addEventListener('load', onDone, { once: true });
            img.addEventListener('error', onDone, { once: true });
        }
    });
}


function closeReceiptModal() {
    document.getElementById('receiptModal').style.display = 'none';
    window.location.reload(); // refresh setelah tutup modal
}

function closePaymentModal() {
    // Reset cart
    cart = {};
    totalAmount = 0;
    updateBillDisplay();
    
    // Reset payment modal inputs
    document.getElementById('customerNameInput').value = '';
    document.getElementById('cashInput').value = '';
    cashValue = '';
    document.getElementById('payment-balance').textContent = '';
    
    // Reset payment type to default (Dine in)
    document.getElementById('dineInBtn').classList.add('active');
    document.getElementById('takeAwayBtn').classList.remove('active');
    
    // Hide payment modal
    document.getElementById('paymentModal').style.display = 'none';
    
    // Show bills section again
    document.querySelector('.bills-section').style.display = 'block';
}

window.onload = function() {
    loadMenuItems();
    loadCategories();
};

</script>
@endsection
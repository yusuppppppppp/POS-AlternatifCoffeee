@extends('layouts.app')

@section('title', 'Menu Management')

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

        .menu-section {
            margin-left: 12rem;
            padding: 40px;
            margin-top: -30px;
            max-width: 1100px;
            position: relative;
        }

        .container.drawer-open .menu-section {
            margin-left: 0px !important;
            margin-right: 80px !important;
        }

        .section-header {
            background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
            padding: 32px;
            border-radius: 20px;
            margin-bottom: 32px;
            box-shadow: 
                0 20px 40px rgba(46, 71, 102, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .section-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.6;
        }

        .section-title {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .section-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            position: relative;
            z-index: 1;
        }

        .category-btn {
            background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
            color: #fff;
            border: none;
            padding: 16px 32px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 32px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 
                0 8px 25px rgba(46, 71, 102, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .category-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .category-btn:hover::before {
            left: 100%;
        }

        .category-btn:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 12px 35px rgba(46, 71, 102, 0.35),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        .category-btn:active {
            transform: translateY(-1px);
        }

        .category-btn-icon {
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .table-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.08),
                0 0 0 1px rgba(226, 232, 240, 0.5);
            border: 1px solid rgba(226, 232, 240, 0.3);
        }

        .menu-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .menu-table th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 24px 20px;
            text-align: left;
            font-weight: 700;
            color: #2E4766;
            font-size: 15px;
            border-bottom: 2px solid #e2e8f0;
            position: relative;
        }

        .menu-table th:first-child {
            border-top-left-radius: 20px;
        }

        .menu-table th:last-child {
            border-top-right-radius: 20px;
        }

        .menu-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .menu-table tbody tr:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transform: scale(1.01);
            box-shadow: 0 4px 15px rgba(46, 71, 102, 0.1);
        }

        .menu-table tbody tr:last-child {
            border-bottom: none;
        }

        .menu-table td {
            padding: 20px;
            vertical-align: middle;
            color: #475569;
            font-weight: 500;
        }

        .menu-table .menu-image {
            border-radius: 12px;
            object-fit: cover;
            height: 56px;
            width: 56px;
            box-shadow: 
                0 4px 12px rgba(0,0,0,0.15),
                0 0 0 2px rgba(255, 255, 255, 1);
            border: 2px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .menu-table .menu-image:hover {
            transform: scale(1.1);
            box-shadow: 
                0 8px 25px rgba(0,0,0,0.2),
                0 0 0 3px rgba(46, 71, 102, 0.1);
        }

        .menu-name {
            font-weight: 600;
            color: #2E4766;
            font-size: 16px;
        }

        .menu-price {
            font-weight: 700;
            color: #059669;
            font-size: 16px;
        }

        .menu-category {
            background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(46, 71, 102, 0.2);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .edit-btn, .delete-btn, .active-btn, .inactive-btn {
            border: none;
            padding: 10px;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            position: relative;
            overflow: hidden;
        }

        .edit-btn {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #f59e0b;
            box-shadow: 
                0 4px 12px rgba(245, 158, 11, 0.2),
                0 0 0 1px rgba(245, 158, 11, 0.1);
        }

        .delete-btn {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            box-shadow: 
                0 4px 12px rgba(220, 38, 38, 0.2),
                0 0 0 1px rgba(220, 38, 38, 0.1);
        }

        .active-btn {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
            box-shadow: 
                0 4px 12px rgba(5, 150, 105, 0.2),
                0 0 0 1px rgba(5, 150, 105, 0.1);
        }

        .inactive-btn {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            color: #64748b;
            box-shadow: 
                0 4px 12px rgba(100, 116, 139, 0.2),
                0 0 0 1px rgba(100, 116, 139, 0.1);
        }

        .edit-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }

        .delete-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.3);
        }

        .active-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.3);
        }

        .inactive-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(100, 116, 139, 0.3);
        }

        .modal {
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

        .modal-content {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            padding: 40px;
            width: 90%;
            max-width: 500px;
            margin: 15vh auto;
            border-radius: 24px;
            box-shadow: 
                0 25px 50px rgba(46, 71, 102, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            animation: slideUp 0.4s ease-out;
            max-height: 80vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            transition: margin 0.3s ease;
        }

        .container.drawer-open .modal-content {
            margin-left: auto;
            margin-right: 400px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            position: relative;
            padding-bottom: 20px;
        }

        .modal-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #2E4766, #4a6b8a, #2E4766);
            border-radius: 1px;
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding-right: 8px;
        }

        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .modal-title {
            color: #2E4766;
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
        }

        .close-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: rgba(46, 71, 102, 0.1);
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            color: #2E4766;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(46, 71, 102, 0.2);
            transform: rotate(90deg);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            font-weight: 600;
            color: #2E4766;
            display: block;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #fff;
            font-family: inherit;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #2E4766;
            box-shadow: 
                0 0 0 3px rgba(46, 71, 102, 0.1),
                0 4px 12px rgba(46, 71, 102, 0.1);
            transform: translateY(-1px);
        }

        .file-input-wrapper {
            position: relative;
            margin-top: -50px;
        }

        .file-input {
            opacity: 0;
            position: absolute;
            z-index: -1;
        }

        .file-input-label {
            display: block;
            padding: 14px 16px;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8fafc;
            color: #64748b;
        }

        .file-input-label:hover {
            border-color: #2E4766;
            background: rgba(46, 71, 102, 0.05);
            color: #2E4766;
        }

        .file-input:focus + .file-input-label {
            border-color: #2E4766;
            box-shadow: 0 0 0 3px rgba(46, 71, 102, 0.1);
        }
 
        .file-input:valid + .file-input-label {
            border-color: #059669;
            background: rgba(5, 150, 105, 0.05);
            color: #059669;
        }

        #modalSubmitBtn {
            background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 
                0 4px 15px rgba(46, 71, 102, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        #modalSubmitBtn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        #modalSubmitBtn:hover::before {
            left: 100%;
        }

        #modalSubmitBtn:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 8px 25px rgba(46, 71, 102, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        #modalSubmitBtn:active {
            transform: translateY(0);
        }

        #modalSubmitBtn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

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

        #errorMessage {
            margin-top: 16px;
            padding: 12px 16px;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            font-size: 14px;
            border-radius: 8px;
            border: 1px solid #fca5a5;
            display: none;
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

        @media (max-width: 900px) {
            .menu-section {
                margin-left: 0;
                padding: 20px;
                max-width: 100vw;
                margin-top: 0;
            }
            
            .section-header {
                padding: 24px;
                margin-bottom: 24px;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .menu-section {
                padding: 16px;
            }
            
            .menu-table .menu-image {
                height: 40px;
                width: 40px;
            }
            
            .modal-content {
                width: 95%;
                padding: 24px;
                margin: 2vh auto;
            }
            
            .modal-title {
                font-size: 1.5rem;
            }
            
            .category-btn {
                padding: 12px 24px;
                font-size: 14px;
            }
            
            .menu-table th,
            .menu-table td {
                padding: 12px 8px;
                font-size: 14px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }
            
            .edit-btn, .delete-btn, .active-btn, .inactive-btn {
                width: 32px;
                height: 32px;
            }
        }

        /* Loading state for buttons */
        .btn-loading {
            position: relative;
            color: transparent;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s ease-in-out infinite;
        }

        /* Search Form Styles */
        .search-form {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .search-input-group {
            position: relative;
            min-width: 300px;
            display: flex;
            align-items: center;
        }

        .search-input {
            width: 100%;
            padding: 14px 56px 14px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 10px;
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

        /* Category Filter Styles */
        .category-filters-container {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 
                0 4px 20px rgba(0, 0, 0, 0.06),
                0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(226, 232, 240, 0.4);
            margin-bottom: 24px;
        }

        .menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .category-filters {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            flex: 1;
        }

        .category-filter-btn {
            padding: 12px 24px;
            border: 2px solid #e2e8f0;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            color: #64748b;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .category-filter-btn:hover {
            border-color: #2E4766;
            color: #2E4766;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(46, 71, 102, 0.15);
        }

        .category-filter-btn.active {
            background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
            border-color: #2E4766;
            color: white;
            box-shadow: 
                0 4px 15px rgba(46, 71, 102, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        .category-filter-btn.active:hover {
            background: linear-gradient(135deg, #1e3a5f 0%, #2E4766 100%);
            transform: translateY(-2px);
            box-shadow: 
                0 6px 20px rgba(46, 71, 102, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        /* Responsive Design for Search */
        @media (max-width: 900px) {
            .category-filters-container {
                margin: 0 20px 24px 20px;
            }
            
            .menu-header {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
            }
            
            .search-form {
                justify-content: center;
            }
            
            .search-input-group {
                min-width: auto;
                flex: 1;
            }
            
            .category-filters {
                justify-content: center;
            }
            
            .category-filter-btn {
                padding: 10px 20px;
                font-size: 13px;
            }
        }
</style>

<div class="menu-section">
    <div class="section-header">
        <h1 class="section-title">Menu Management</h1>
        <p class="section-subtitle">Manage your coffee shop menu items</p>
    </div>
    
    <button class="category-btn" onclick="showModal('add')">
        <div class="category-btn-icon">+</div>
        Add New Menu
    </button>
    
    <!-- Menu Header with Category Filters and Search -->
    <div class="category-filters-container">
        <div class="menu-header">
            <div class="category-filters">
                <button class="category-filter-btn {{ !$category || $category === 'All' ? 'active' : '' }}" onclick="filterByCategory('All')">
                    All Category
                </button>
                @foreach($categories as $cat)
                <button class="category-filter-btn {{ $category === $cat->name ? 'active' : '' }}" onclick="filterByCategory('{{ $cat->name }}')">
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>
            
            <form id="menuSearchForm" class="search-form">
                <div class="search-input-group">
                    <input 
                        type="text" 
                        name="search" 
                        id="menuSearchInput"
                        value="{{ $search ?? '' }}" 
                        placeholder="Search by menu name, category, or price..."
                        class="search-input"
                    >
                    <button type="submit" class="search-button">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                @if(!empty($search ?? ''))
                    <a href="{{ route('menu-management') }}{{ $category ? '?category=' . $category : '' }}" class="clear-search" id="menuClearSearch">Clear Search</a>
                @endif
                @if($category && $category !== 'All')
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
            </form>
        </div>
    </div>
    
    <div id="menuListContainer">
    <div class="table-container">
        <table class="menu-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Name</th>
                    <th>Preview</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="menuTableBody">
                @foreach($menus as $index => $menu)
                <tr>
                    <td style="text-align: center; font-weight: 600; color: #2E4766;">{{ ($menus->currentPage() - 1) * $menus->perPage() + $loop->iteration }}</td>
                    <td class="menu-name">{{ $menu->name }}</td>
                    <td><img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" class="menu-image"></td>
                    <td class="menu-price">Rp. {{ number_format($menu->price, 0, ',', '.') }}</td>
                    <td><span class="menu-category">{{ $menu->category ? $menu->category->name : 'No Category' }}</span></td>
                    <td>
                        <span class="menu-category" style="background: {{ $menu->is_active ? 'linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #059669;' : 'linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); color: #6b7280;' }}">
                            {{ $menu->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="edit-btn" onclick="showModal('edit', '{{ $menu->id }}')">
                                <img src="{{ asset('images/edit.png') }}" alt="Edit" style="height:18px;width:18px;">
                            </button>
                            @if($menu->is_active)
                                <button class="active-btn" onclick="toggleMenuStatus('{{ $menu->id }}', false)" title="Set Inactive">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </button>
                            @else
                                <button class="inactive-btn" onclick="toggleMenuStatus('{{ $menu->id }}', true)" title="Set Active">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7 13l3 3 7-7-1.41-1.42L10 13.17l-1.59-1.58L7 13z"/>
                                    </svg>
                                </button>
                            @endif
                            <button class="delete-btn" onclick="deleteMenu('{{ $menu->id }}')">
                                <img src="{{ asset('images/hapus.png') }}" alt="Delete" style="height:18px;width:18px;">
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Show Entries and Pagination -->
    <div class="pagination-container">
        <div class="pagination-info">
            Showing {{ $menus->firstItem() ?? 0 }} to {{ $menus->lastItem() ?? 0 }} of {{ $menus->total() }} entries
        </div>
        
        <div class="per-page-selector">
            <label for="per_page">Show:</label>
            <select id="per_page" onchange="changePerPage(this.value)">
                <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span>entries</span>
        </div>
    </div>
    
    <div class="pagination-links">
        {{ $menus->appends(['per_page' => request('per_page', 10), 'search' => request('search'), 'category' => request('category')])->links() }}
    </div>
    </div>
</div>

<!-- Modal -->
<div id="menuModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="modalTitle"></h2>
            <button class="close-btn" onclick="hideModal()">×</button>
        </div>
        <div class="modal-body">
            <form id="menuForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="menuId">
                <div class="form-group">
                    <label for="name">Menu Name</label>
                    <input type="text" name="name" id="name" required placeholder="Enter menu name">
                </div>
                <div class="form-group">
                    <label for="price">Price (Rp)</label>
                    <input type="number" name="price" id="price" required placeholder="Enter price">
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id" required>
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="image">Menu Image</label>
                    <div class="file-input-wrapper">
                        <input type="file" name="image" id="image" accept="image/*" class="file-input">
                        <label for="image" class="file-input-label">
                            Choose file
                        </label>
                    </div>
                </div>
                <button type="submit" id="modalSubmitBtn">
                    <span class="loading" style="display: none;"></span>
                    <span class="btn-text">Submit</span>
                </button>
                <div id="errorMessage"></div>
            </form>
        </div>
    </div>
</div>

<script>
// ==== AJAX Search/Filter/Pagination for Menu Management ====
document.addEventListener('DOMContentLoaded', function() {
    const listContainer = document.getElementById('menuListContainer');
    const searchForm = document.getElementById('menuSearchForm');
    const searchInput = document.getElementById('menuSearchInput');
    const perPageSelect = document.getElementById('per_page');
    const clearSearchLink = document.getElementById('menuClearSearch');
    let typingTimer;
    const doneTypingInterval = 10;

    function buildUrl() {
        const url = new URL('{{ route("menu-management") }}', window.location.origin);
        const searchVal = (searchInput?.value || '').trim();
        if (searchVal) url.searchParams.set('search', searchVal);
        // category from active button, if any
        const activeCatBtn = document.querySelector('.category-filter-btn.active');
        if (activeCatBtn) {
            const catText = activeCatBtn.textContent.trim();
            if (catText && catText !== 'All Category') {
                url.searchParams.set('category', catText);
            }
        }
        if (perPageSelect) url.searchParams.set('per_page', perPageSelect.value);
        return url;
    }

    function pushStateFrom(url) {
        const newUrl = new URL(window.location.href);
        // Reset and set
        newUrl.search = url.search;
        window.history.pushState({}, '', newUrl);
    }

    function fetchMenuPage(url) {
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
            const newContainer = temp.querySelector('#menuListContainer') || temp;
            listContainer.innerHTML = newContainer.innerHTML;
        })
        .catch(err => console.error('Menu AJAX error:', err));
    }

    function performMenuSearch() {
        const url = buildUrl();
        // ensure first page when changing params
        url.searchParams.delete('page');
        fetchMenuPage(url);
        pushStateFrom(url);
    }

    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performMenuSearch();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performMenuSearch, doneTypingInterval);
        });
    }

    if (clearSearchLink) {
        clearSearchLink.addEventListener('click', function(e) {
            e.preventDefault();
            if (searchInput) searchInput.value = '';
            performMenuSearch();
        });
    }

    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            performMenuSearch();
        });
    }

    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        const a = e.target.closest('.pagination a');
        if (a && listContainer.contains(a)) {
            e.preventDefault();
            const url = new URL(a.href);
            // preserve current search/category/per_page from inputs/active states
            const base = buildUrl();
            url.searchParams.set('search', base.searchParams.get('search') || '');
            if (!base.searchParams.get('search')) url.searchParams.delete('search');
            const cat = base.searchParams.get('category');
            if (cat) url.searchParams.set('category', cat); else url.searchParams.delete('category');
            const pp = base.searchParams.get('per_page');
            if (pp) url.searchParams.set('per_page', pp);
            fetchMenuPage(url);
            pushStateFrom(url);
        }
    });

    // Override global functions to use AJAX
    window.filterByCategory = function(category) {
        // set active button UI
        document.querySelectorAll('.category-filter-btn').forEach(btn => btn.classList.remove('active'));
        // Find button by text
        const buttons = Array.from(document.querySelectorAll('.category-filter-btn'));
        const target = buttons.find(b => b.textContent.trim() === (category === 'All' ? 'All Category' : category));
        if (target) target.classList.add('active');
        // Build and fetch
        const url = buildUrl();
        if (category === 'All') url.searchParams.delete('category');
        else url.searchParams.set('category', category);
        url.searchParams.delete('page');
        fetchMenuPage(url);
        pushStateFrom(url);
    };

    window.changePerPage = function(value) {
        if (perPageSelect) perPageSelect.value = value;
        const url = buildUrl();
        url.searchParams.set('per_page', value);
        url.searchParams.delete('page');
        fetchMenuPage(url);
        pushStateFrom(url);
    };
});
// ==== End AJAX block ====
function showModal(action, id = null) {
    const modal = document.getElementById('menuModal');
    const title = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('modalSubmitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const form = document.getElementById('menuForm');
    const errorMessage = document.getElementById('errorMessage');

    errorMessage.style.display = 'none';
    form.reset();
    modal.style.display = 'block';
    document.getElementById('menuId').value = '';
    document.getElementById('image').required = (action === 'add');

    if (action === 'add') {
        title.textContent = 'Add New Menu';
        btnText.textContent = 'Add Menu';
        form.setAttribute('data-action', '{{ route("menus.store") }}');
        form.setAttribute('data-method', 'POST');
    } else if (action === 'edit' && id) {
        title.textContent = 'Edit Menu';
        btnText.textContent = 'Update Menu';
        form.setAttribute('data-action', '{{ url("menus") }}/' + id);
        form.setAttribute('data-method', 'PUT');

        fetch('/api/menus/' + id)
            .then(res => res.json())
            .then(menu => {
                document.getElementById('menuId').value = menu.id;
                document.getElementById('name').value = menu.name;
                document.getElementById('price').value = menu.price;
                document.getElementById('category_id').value = menu.category_id;
            })
            .catch(() => {
                errorMessage.textContent = 'Failed to load menu';
                errorMessage.style.display = 'block';
            });
    }
}

function hideModal() {
    document.getElementById('menuModal').style.display = 'none';
}

// Function to handle category filtering
function filterByCategory(category) {
    const currentUrl = new URL(window.location);
    if (category === 'All') {
        currentUrl.searchParams.delete('category');
    } else {
        currentUrl.searchParams.set('category', category);
    }
    currentUrl.searchParams.delete('page'); // Reset to first page when changing category
    window.location.href = currentUrl.toString();
}

// Function to handle show entries change
function changePerPage(value) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('per_page', value);
    currentUrl.searchParams.delete('page'); // Reset to first page when changing entries
    // Preserve search parameter if it exists
    const searchParam = currentUrl.searchParams.get('search');
    if (searchParam) {
        currentUrl.searchParams.set('search', searchParam);
    }
    // Preserve category parameter if it exists
    const categoryParam = currentUrl.searchParams.get('category');
    if (categoryParam) {
        currentUrl.searchParams.set('category', categoryParam);
    }
    window.location.href = currentUrl.toString();
}

document.getElementById('menuForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const submitBtn = document.getElementById('modalSubmitBtn');
    const loading = submitBtn.querySelector('.loading');
    const btnText = submitBtn.querySelector('.btn-text');
    const data = new FormData(form);
    const action = form.getAttribute('data-action');
    const method = form.getAttribute('data-method');

    if (method === 'PUT') {
        data.append('_method', 'PUT');
    }

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.classList.add('btn-loading');
    loading.style.display = 'inline-block';
    btnText.style.display = 'none';

    fetch(action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: data
    })
    .then(async res => {
        if (!res.ok) {
            const errorData = await res.json();
            throw new Error(errorData.message || 'Gagal menyimpan data');
        }
        return res.json();
    })
    .then(() => {
        hideModal();
        location.reload();
    })
    .catch(err => {
        const errBox = document.getElementById('errorMessage');
        errBox.textContent = err.message;
        errBox.style.display = 'block';
    })
    .finally(() => {
        // Hide loading state
        submitBtn.disabled = false;
        submitBtn.classList.remove('btn-loading');
        loading.style.display = 'none';
        btnText.style.display = 'inline';
    });
});

function deleteMenu(id) {
    if (!confirm('Yakin hapus menu ini?')) return;

    fetch('/menus/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal hapus');
        location.reload();
    })
    .catch(err => alert(err.message));
}

function toggleMenuStatus(id, isActive) {
    const action = isActive ? 'mengaktifkan' : 'menonaktifkan';
    if (!confirm(`Yakin ${action} menu ini?`)) return;

    fetch('/menus/' + id + '/toggle-status', {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            is_active: isActive
        })
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal mengubah status menu');
        location.reload();
    })
    .catch(err => alert(err.message));
}

window.onclick = function(e) {
    const modal = document.getElementById('menuModal');
    if (e.target == modal) hideModal();
};
</script>

@endsection

@extends('layouts.admin')
<style>
    /* Enhanced Transaction Table Styles */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        font-size: 1em;
        margin-top: 20px;
    }

    .data-table th,
    .data-table td {
        padding: 18px 24px;
        text-align: left;
        border-bottom: 1px solid #f1f5f9;
    }

    .data-table th {
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
        color: #334155;
        font-weight: 700;
        font-size: 1.02em;
        letter-spacing: 0.02em;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tr:hover {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .flagged-row {
        background: linear-gradient(135deg, #fff6f6 0%, #fef2f2 100%) !important;
        border-left: 4px solid #ef4444;
    }

    .flagged-icon {
        color: #ef4444;
        margin-right: 6px;
        font-size: 1.1em;
        vertical-align: middle;
    }

    .flagged-list-item {
        color: #ef4444;
    }

    .flagged-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #b91c1c;
        border-radius: 8px;
        padding: 0.25em 0.8em;
        font-size: 0.9em;
        font-weight: 600;
        margin-left: 8px;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);
    }

    .status-badge {
        display: inline-block;
        padding: 0.4em 1.2em;
        border-radius: 8px;
        font-size: 0.95em;
        font-weight: 600;
        background: #f1f5f9;
        color: #64748b;
        position: relative;
        z-index: 1;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .status-badge.active {
        background: linear-gradient(135deg, #e0f7fa 0%, #b2f5ea 100%);
        color: #059669;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.15);
    }

    .status-badge.inactive {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #b45309;
        box-shadow: 0 2px 8px rgba(180, 83, 9, 0.15);
    }

    .flagged-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #b91c1c;
        border-radius: 8px;
        padding: 0.4em 1em;
        font-size: 0.95em;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);
    }

    .guest-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #eef2ff 0%, #dbeafe 100%);
        color: #6366f1;
        border-radius: 8px;
        padding: 0.4em 1em;
        font-size: 0.95em;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
    }

    .animated-stripes {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(135deg,
                rgba(99, 102, 241, 0.08) 0px,
                rgba(99, 102, 241, 0.08) 8px,
                transparent 8px,
                transparent 16px);
        z-index: 0;
        pointer-events: none;
        animation: stripes-move 1.2s linear infinite;
    }

    @keyframes stripes-move {
        0% {
            background-position: 0 0;
        }

        100% {
            background-position: 32px 0;
        }
    }

    .employee-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .employee-info img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        background: #f1f5f9;
        border: 2px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .employee-info h4 {
        margin: 0 0 4px 0;
        font-size: 1.02em;
        font-weight: 600;
        color: #334155;
    }

    .employee-info span {
        font-size: 0.95em;
        color: #64748b;
    }

    .progress-bar-container {
        width: 100%;
        max-width: 140px;
        margin: 0 auto;
    }

    .progress-bar-outer {
        width: 100%;
        height: 16px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .progress-bar-inner {
        height: 100%;
        border-radius: 10px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .progress-bar-blue {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
    }

    .progress-bar-green {
        background: linear-gradient(90deg, #22c55e 0%, #4ade80 100%);
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
    }

    /* Alphabet Filter Container */
    .alphabet-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .alphabet-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f1f5f9;
    }

    .alphabet-title {
        font-size: 1.4em;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alphabet-title i {
        color: #6366f1;
        font-size: 1.2em;
    }

    .alphabet-description {
        color: #64748b;
        font-size: 0.95em;
        max-width: 600px;
        line-height: 1.5;
    }

    .alphabet-reset-btn {
        background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.8em 1.5em;
        font-weight: 600;
        font-size: 0.95em;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .alphabet-reset-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    .alphabet-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(40px, 1fr));
        gap: 8px;
        max-width: 800px;
    }

    .alphabet-btn {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        color: #64748b;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 8px;
        font-weight: 700;
        font-size: 1.1em;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        min-width: 40px;
        position: relative;
        overflow: hidden;
    }

    .alphabet-btn:hover {
        background: linear-gradient(135deg, #eef2ff 0%, #dbeafe 100%);
        border-color: #6366f1;
        color: #6366f1;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
    }

    .alphabet-btn.active {
        background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%);
        color: white;
        border-color: #6366f1;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }

    .alphabet-btn.active:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
    }

    /* Skeleton Preloader Styles */
    .skeleton-container {
        display: none;
        margin: 20px 0;
    }

    .skeleton-table {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    }

    .skeleton-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .skeleton-header-line {
        height: 20px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 8px;
        margin-bottom: 8px;
    }

    .skeleton-row {
        display: flex;
        align-items: center;
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .skeleton-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        margin-right: 15px;
    }

    .skeleton-content {
        flex: 1;
    }

    .skeleton-line {
        height: 14px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 6px;
        margin-bottom: 6px;
    }

    .skeleton-line.short {
        width: 60%;
    }

    .skeleton-line.medium {
        width: 80%;
    }

    .skeleton-line.long {
        width: 90%;
    }

    .skeleton-badge {
        width: 80px;
        height: 32px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 8px;
        margin-left: 15px;
    }

    .skeleton-progress {
        width: 140px;
        height: 16px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 10px;
        margin: 0 auto;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    /* Enhanced Filter Styles */
    .sleek-filter-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .sleek-filter-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        font-size: 1.3em;
        font-weight: 700;
        color: #1e293b;
    }

    .sleek-filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .sleek-filter-group {
        display: flex;
        flex-direction: column;
    }

    .sleek-filter-label {
        font-size: 0.95em;
        color: #64748b;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .sleek-filter-input {
        padding: 12px 16px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        background: #fff;
        font-size: 1em;
        color: #334155;
        transition: all 0.3s ease;
        outline: none;
    }

    .sleek-filter-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .sleek-filter-btns {
        display: flex;
        gap: 10px;
        align-items: end;
    }

    .filter-btn {
        background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    .reset-btn {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #64748b;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .reset-btn:hover {
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        transform: translateY(-2px);
    }

    /* Custom Select Dropdown Styles */
    .custom-select-input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        background: #fff;
        font-size: 1em;
        color: #334155;
        transition: all 0.3s ease;
        outline: none;
        cursor: pointer;
    }

    .custom-select-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .custom-select-wrapper {
        position: relative;
        width: 100%;
    }

    .custom-options {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        max-height: 250px;
        overflow-y: auto;
        margin-top: 4px;
    }

    .custom-options input[type="text"] {
        width: calc(100% - 20px);
        margin: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 0.9em;
        color: #334155;
        background: #f8fafc;
    }

    .option-item,
    .option-item-member {
        padding: 10px 15px;
        cursor: pointer;
        font-size: 0.95em;
        color: #334155;
        border-radius: 6px;
        margin: 2px 8px;
        transition: all 0.2s ease;
    }

    .option-item:hover,
    .option-item-member:hover {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #6366f1;
        transform: translateX(2px);
    }

    .no-results {
        color: #ef4444;
        font-size: 0.9em;
        padding: 10px 15px;
        text-align: center;
        font-style: italic;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .alphabet-grid {
            grid-template-columns: repeat(auto-fit, minmax(35px, 1fr));
            gap: 6px;
        }

        .alphabet-btn {
            padding: 10px 6px;
            font-size: 1em;
            min-width: 35px;
        }

        .sleek-filter-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .data-table th,
        .data-table td {
            padding: 12px 16px;
        }

        .employee-info img {
            width: 35px;
            height: 35px;
        }
    }

    /* Loading States */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #6366f1;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Action column style */
    .action-btn {
        background: none;
        border: none;
        color: #ef4444;
        cursor: pointer;
        font-size: 1.2em;
        padding: 6px 10px;
        border-radius: 6px;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* Reserve Modal Styles */
    .reserve-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        backdrop-filter: blur(4px);
    }

    .reserve-modal-content {
        background: white;
        border-radius: 20px;
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        animation: modalSlideIn 0.3s ease-out;
        display: flex;
        flex-direction: column;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .reserve-modal-header {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-radius: 20px 20px 0 0;
    }

    .reserve-modal-header h3 {
        margin: 0;
        font-size: 1.4em;
        font-weight: 700;
    }

    .reserve-close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 1.5em;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .reserve-close-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .reserve-step {
        display: none;
        flex: 1;
        overflow: hidden;
    }

    .reserve-step.active {
        display: flex;
        flex-direction: column;
    }

    .reserve-step-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 32px;
        overflow: hidden;
    }

    .reserve-step-content h4 {
        margin: 0 0 16px 0;
        font-size: 1.3em;
        color: #1e293b;
        font-weight: 700;
    }

    .reserve-step-content p {
        color: #64748b;
        margin-bottom: 24px;
        line-height: 1.6;
    }

    .reserve-form-group {
        margin-bottom: 20px;
    }

    .reserve-form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
    }

    .reserve-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 1em;
        transition: all 0.2s;
        background: #f9fafb;
    }

    .reserve-input:focus {
        outline: none;
        border-color: #ef4444;
        background: white;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .reserve-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
        flex-shrink: 0;
    }

    .reserve-scrollable-content {
        flex: 1;
        overflow-y: auto;
        padding-right: 8px;
        margin-right: -8px;
    }

    .reserve-scrollable-content::-webkit-scrollbar {
        width: 6px;
    }

    .reserve-scrollable-content::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .reserve-scrollable-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .reserve-scrollable-content::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .reserve-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95em;
    }

    .reserve-btn-primary {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
    }

    .reserve-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
    }

    .reserve-btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .reserve-btn-secondary:hover {
        background: #e5e7eb;
    }

    .reserve-btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .reserve-btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
    }

    .reserve-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .reserve-summary {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        border: 1px solid #e2e8f0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .reserve-summary-item {
        text-align: center;
    }

    .reserve-summary-label {
        display: block;
        font-size: 0.9em;
        color: #64748b;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .reserve-summary-value {
        display: block;
        font-size: 1.5em;
        font-weight: 700;
        color: #1e293b;
    }

    .reserve-transactions-list {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #f9fafb;
    }

    .reserve-transaction-item {
        display: flex;
        align-items: center;
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
        background: white;
        transition: background 0.2s;
    }

    .reserve-transaction-item:last-child {
        border-bottom: none;
    }

    .reserve-transaction-item:hover {
        background: #f8fafc;
    }

    .reserve-transaction-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 16px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #6366f1;
    }

    .reserve-transaction-info {
        flex: 1;
    }

    .reserve-transaction-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .reserve-transaction-details {
        font-size: 0.9em;
        color: #64748b;
    }

    .reserve-transaction-amount {
        font-weight: 600;
        color: #059669;
        font-size: 1.1em;
    }

    .reserve-progress {
        margin: 32px 0;
    }

    .reserve-progress-bar {
        width: 100%;
        height: 12px;
        background: #f1f5f9;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .reserve-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
        border-radius: 8px;
        transition: width 0.3s ease;
        width: 0%;
    }

    .reserve-progress-text {
        text-align: center;
        font-weight: 600;
        color: #374151;
        font-size: 1.1em;
    }

    .reserve-status {
        text-align: center;
        color: #64748b;
        font-size: 1.1em;
        margin-top: 24px;
    }

    .reserve-completion {
        text-align: center;
        padding: 20px 0;
    }

    .reserve-completion i {
        font-size: 3em;
        color: #22c55e;
        margin-bottom: 16px;
        display: block;
    }

    .reserve-completion-summary {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #bbf7d0;
        margin-top: 20px;
    }

    .reserve-completion-summary h5 {
        margin: 0 0 12px 0;
        color: #166534;
        font-weight: 600;
    }

    .reserve-completion-summary ul {
        margin: 0;
        padding-left: 20px;
        color: #166534;
    }

    .reserve-completion-summary li {
        margin-bottom: 4px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .reserve-modal-content {
            width: 95%;
            margin: 20px;
        }

        .reserve-step-content {
            padding: 20px;
        }

        .reserve-summary {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .reserve-actions {
            flex-direction: column;
        }

        .reserve-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@section('content')
<main class="main-content">
    @include('layouts.header')
    <div class="content">
        <!-- Quick Action Container -->
        <div class="quick-action-container" style="background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%); border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(34,197,94,0.08); border: 1px solid #bbf7d0;">
            <div style="margin: 18px 0 8px 0;">
                <div class="quick-action-header" style="font-weight: 700; color: #22c55e; margin-bottom: 4px; display: flex; align-items: center;">
                    <i class="fa-solid fa-bolt" style="margin-right: 6px; color: #22c55e;"></i>
                    Quick Actions
                </div>
                <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                    Use the quick action buttons below to rapidly add new transactions or reserve (delete) transactions for a specific month and year. This helps you efficiently manage your transaction records.
                </p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('Manually.transactions.create') }}" class="quick-action-btn"
                    style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.9em; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; text-decoration: none;text-align:center!important;">
                    <span style="display: flex; align-items: center; justify-content: center; width: 100%;">
                        <i class="fa-solid fa-plus" style="margin-right: 5px;"></i>
                        Add Transaction
                    </span>
                </a>
                <button onclick="openReserveModal()" class="quick-action-btn"
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.9em; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center;">
                    <i class="fa-solid fa-archive" style="margin-right: 5px;"></i>
                    Reserve Transactions
                </button>
            </div>
        </div>

        <!-- Alphabet Filter Container -->
        <div class="alphabet-container">
            <div class="alphabet-header">
                <div>
                    <div class="alphabet-title">
                        <i class="fa-solid fa-filter"></i>
                        Alphabet Filter
                    </div>
                    <div class="alphabet-description">
                        Click on any letter to filter transactions by member names or emails starting with that letter.
                        This helps you quickly find specific members in large datasets.
                    </div>
                </div>
                <button class="alphabet-reset-btn" onclick="resetAlphabetFilter()">
                    <i class="fa-solid fa-rotate"></i>
                    Reset Filter
                </button>
            </div>
            <div class="alphabet-grid" id="alphabetGrid">
                <button class="alphabet-btn" data-letter="">ALL</button>
                @foreach(range('A', 'Z') as $letter)
                <button class="alphabet-btn" data-letter="{{ $letter }}">{{ $letter }}</button>
                @endforeach
            </div>
        </div>


        <div class="sleek-filter-container">
            <div style="margin: 18px 0 8px 0;">
                <div class="sleek-filter-header">
                    <i class="fa-solid fa-sliders"></i>
                    Advanced Filters
                </div>
                <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                    This filter allows you to quickly find transactions by member name, unique ID, or other criteria,
                    making it easier to manage and review large numbers of records efficiently.
                </p>
            </div>
            <form method="GET" action="{{ route('transactions.list') }}" id="filterForm" autocomplete="off">
                <div class="sleek-filter-grid">
                    <div class="sleek-filter-group" style="min-width: 160px;">
                        <label for="memberInput" class="sleek-filter-label">Member Name</label>
                        <input type="text" id="memberInput" class="custom-select-input" placeholder="Search Member..."
                            autocomplete="off" value="{{ request('member_name') }}">
                        <div class="custom-select-wrapper">
                            <div class="custom-options" id="memberOptions">
                                <input type="text" id="memberSearch" placeholder="Type to search..."
                                    style="width: 95%; margin: 8px auto 6px auto; display: block; border: 1px solid #e2e8f0; border-radius: 4px; padding: 0.4em 0.7em; font-size: 0.97em;">
                                <div class="option-item-member" data-value=""
                                    style="color: #64748b; border-bottom: 1px solid #f1f5f9;">All</div>
                                @php
                                // Get member names from users
                                $memberNames = \App\Models\User::where('type', 'member')->pluck('name')->filter()->unique();

                                // Get names from flagged transactions (where user_id is null but name exists)
                                $flaggedTransactionNames = \App\Models\Transaction::whereNull('user_id')
                                ->whereNotNull('name')
                                ->where('name', '!=', '')
                                ->pluck('name')
                                ->filter()
                                ->unique();

                                // Merge and sort all names
                                $allNames = $memberNames->merge($flaggedTransactionNames)->unique()->sort();
                                @endphp
                                @foreach($allNames as $name)
                                <div class="option-item-member" data-value="{{ $name }}">
                                    {{ $name }}
                                </div>
                                @endforeach
                                <div class="no-results" style="display:none;">No members found.</div>
                            </div>
                            <input type="hidden" name="member_name" id="selectedMember"
                                value="{{ request('member_name') }}">
                        </div>
                    </div>
                    <div class="sleek-filter-group" style="min-width: 140px;">
                        <label for="uniqueInput" class="sleek-filter-label">Unique ID</label>
                        <input type="text" id="uniqueInput" class="custom-select-input"
                            placeholder="Search Unique ID..." readonly value="{{ request('unique_id') }}">
                        <div class="custom-select-wrapper" style="position: relative; width: 100%; z-index: 10;">
                            <div class="custom-options" id="optionsList" style="display: none;">
                                <input type="text" id="searchBox" placeholder="Type to search...">
                                <div class="option-item" data-value="">All</div>
                                @php
                                $uniqueIds = \App\Models\User::where('type', 'member')->pluck('unique_id')->unique();
                                @endphp
                                @foreach($uniqueIds as $uid)
                                <div class="option-item" data-value="{{ $uid }}">{{ $uid }}</div>
                                @endforeach
                                <div class="no-results" style="display:none;">No unique IDs found.</div>
                            </div>
                            <input type="hidden" name="unique_id" id="selectedUniqueId"
                                value="{{ request('unique_id') }}">
                        </div>
                    </div>
                    <div class="sleek-filter-group" style="min-width: 120px;">
                        <label class="sleek-filter-label">Type</label>
                        <select id="type" name="type" class="sleek-filter-input">
                            <option value="">All</option>
                            <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                            <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                            <option value="cash" {{ request('type') == 'cash' ? 'selected' : '' }}>Cash</option>
                        </select>
                    </div>
                    <div class="sleek-filter-group" style="min-width: 120px;">
                        <label class="sleek-filter-label">Start</label>
                        <input type="date" id="start_date" name="start_date" class="sleek-filter-input"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="sleek-filter-group" style="min-width: 120px;">
                        <label class="sleek-filter-label">End</label>
                        <input type="date" id="end_date" name="end_date" class="sleek-filter-input"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="sleek-filter-group" style="min-width: 100px;">
                        <label class="sleek-filter-label">Year</label>
                        <select id="year" name="year" class="sleek-filter-input">
                            <option value="">All</option>
                            @php
                            $currentYear = date('Y');
                            @endphp
                            @for($y = $currentYear; $y >= 2000; $y--)
                            <option value="{{ $y }}" {{ (request('year') == $y) ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="sleek-filter-group" style="min-width: 120px;">
                        <label class="sleek-filter-label">Flagged</label>
                        <select id="flag_status" name="flag_status" class="sleek-filter-input">
                            <option value="" {{ request('flag_status', '') === '' ? 'selected' : '' }}>All</option>
                            <option value="1" {{ request('flag_status') == '1' ? 'selected' : '' }}>Not Flagged</option>
                            <option value="0" {{ request('flag_status') == '0' ? 'selected' : '' }}>Flagged</option>
                        </select>
                    </div>
                    <div class="sleek-filter-group" style="min-width: 120px;">
                        <label class="sleek-filter-label">User Type</label>
                        <select id="is_guest" name="is_guest" class="sleek-filter-input">
                            <option value="" {{ request('is_guest', '') === '' ? 'selected' : '' }}>All Users</option>
                            <option value="0" {{ request('is_guest') == '0' ? 'selected' : '' }}>Members</option>
                            <option value="1" {{ request('is_guest') == '1' ? 'selected' : '' }}>Guest Users</option>
                        </select>
                    </div>
                    <div class="sleek-filter-group" style="flex: 0 0 auto; min-width: 120px;">
                        <div class="sleek-filter-btns">
                            <button type="submit" class="filter-btn">
                                <i class="fa-solid fa-filter"></i>
                                Apply
                            </button>
                            <a href="{{ route('transactions.list') }}" class="reset-btn">
                                <i class="fa-solid fa-rotate"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Skeleton Preloader -->
        <div class="skeleton-container" id="skeletonContainer">
            <div class="skeleton-table">
                <div class="skeleton-header">
                    <div class="skeleton-header-line"></div>
                </div>
                @for($i = 0; $i < 8; $i++)
                    <div class="skeleton-row">
                    <div class="skeleton-avatar"></div>
                    <div class="skeleton-content">
                        <div class="skeleton-line medium"></div>
                        <div class="skeleton-line short"></div>
                    </div>
                    <div class="skeleton-badge"></div>
                    <div class="skeleton-badge"></div>
                    <div class="skeleton-badge"></div>
                    <div class="skeleton-progress"></div>
                    <div class="skeleton-badge"></div>
            </div>
            @endfor
        </div>
    </div>

    <!-- Table Controls Section -->
    <div class="table-controls-container"
        style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0;">
        <div
            style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <div style="margin: 18px 0 8px 0;">

                    <h3 style="margin: 0; color: #1e293b; font-size: 1.1em; font-weight: 600;">
                        <i class="fa-solid fa-table" style="margin-right: 8px; color: #6366f1;"></i>
                        Table Controls
                    </h3>
                    <p style="color: #475569; font-size: 0.85em; margin-bottom: 7px;">
                        Use the table controls below to show or hide columns, making it easy to customize your view and focus on the transaction details that matter most to you.
                    </p>
                </div>

                <!-- Column Visibility Controls -->
                <div class="column-controls" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <span class="badge"
                        style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                        <input type="checkbox" id="showMemberId" checked onchange="toggleColumn('member-id')"
                            style="margin: 0;">
                        Member ID
                    </span>
                    <span class="badge"
                        style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                        <input type="checkbox" id="showName" checked onchange="toggleColumn('name')"
                            style="margin: 0;">
                        Name
                    </span>
                    <span class="badge"
                        style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                        <input type="checkbox" id="showPhone" checked onchange="toggleColumn('phone')"
                            style="margin: 0;">
                        Phone
                    </span>
                    <span class="badge"
                        style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                        <input type="checkbox" id="showAddress" checked onchange="toggleColumn('address')"
                            style="margin: 0;">
                        Address
                    </span>
                    <span class="badge"
                        style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                        <input type="checkbox" id="showAccount" checked onchange="toggleColumn('account')"
                            style="margin: 0;">
                        Account
                    </span>
                    <span class="badge"
                        style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                        <input type="checkbox" id="showStatus" checked onchange="toggleColumn('status')"
                            style="margin: 0;">
                        Status
                    </span>
                    <span class="badge"
                        style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                        <input type="checkbox" id="showDate" checked onchange="toggleColumn('date')"
                            style="margin: 0;">
                        Date
                    </span>
                    <span class="badge"
                        style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                        <input type="checkbox" id="showProgress" checked onchange="toggleColumn('progress')"
                            style="margin: 0;">
                        Progress
                    </span>
                    <span class="badge"
                        style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                        <input type="checkbox" id="showAmount" checked onchange="toggleColumn('amount')"
                            style="margin: 0;">
                        Amount
                    </span>
                    <span class="badge"
                        style="display: flex; align-items: center; gap: 5px; background: #f1f5f9; color: #6366f1; border-radius: 12px; padding: 6px 12px; font-size: 0.9em; font-weight: 500;">
                        <input type="checkbox" id="showAction" checked onchange="toggleColumn('action')"
                            style="margin: 0;">
                        Action
                    </span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div style="display: flex; gap: 10px;">
                <button onclick="showAllColumns()" class="quick-action-btn"
                    style="background: linear-gradient(135deg, #6366f1 0%, #60a5fa 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.9em; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fa-solid fa-eye" style="margin-right: 5px;"></i>
                    Show All
                </button>
                <button onclick="hideAllColumns()" class="quick-action-btn"
                    style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); color: #64748b; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.9em; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fa-solid fa-eye-slash" style="margin-right: 5px;"></i>
                    Hide All
                </button>

            </div>
        </div>
    </div>

    <!-- Enhanced Table Section -->
    <div id="tableSection">
        <div class="table-container">
            <table class="data-table" id="transactionTable">
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Account</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Progress</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="transactionTableBody">
                    @if($transactions->isEmpty())
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 40px; color: #64748b;">
                            <i class="fa-solid fa-inbox"
                                style="font-size: 3em; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                            <div style="font-size: 1.1em; margin-bottom: 8px;">No transactions found</div>
                            <div style="font-size: 0.9em;">Try adjusting your filters or import new transactions
                            </div>
                        </td>
                    </tr>
                    @else
                    @php
                    $selectedYear = request('year');
                    $annualFeeSettings = \App\Models\MembershipFeeSetting::where('member_type', 'annual_fee')->get()->keyBy('year');
                    @endphp
                    @foreach($transactions as $transaction)
                    @php
                    $feeYear = $selectedYear;
                    if (!$feeYear && !empty($transaction->date)) {
                    try {
                    $feeYear = \Carbon\Carbon::parse($transaction->date)->format('Y');
                    } catch (\Exception $e) {
                    $feeYear = null;
                    }
                    }
                    $annualFeeAmount = 80;
                    if ($feeYear && isset($annualFeeSettings[$feeYear])) {
                    $annualFeeAmount = (float) $annualFeeSettings[$feeYear]->amount;
                    } elseif ($annualFeeSettings->count() > 0) {
                    $annualFeeAmount = (float) $annualFeeSettings->sortByDesc('year')->first()->amount;
                    }
                    $amount = (float) $transaction->amount;
                    $progress = min(max($amount, 0), $annualFeeAmount);
                    $progressPercent = $annualFeeAmount > 0 ? ($progress / $annualFeeAmount) * 100 : 0;
                    $isFull = $progress == $annualFeeAmount;
                    @endphp
                    <tr @if(isset($transaction->flag_status) && $transaction->flag_status === 0) class="flagged-row"
                        @endif>
                        <td>
                            @if(isset($transaction->flag_status) && $transaction->flag_status === 0)
                            <i class="fa fa-flag flagged-icon" title="Flagged"></i>
                            @endif

                            @if($transaction->user && $transaction->user->is_guest == 1)
                            <i class="fa fa-user-clock guest-icon" title="Guest User"
                                style="color: #6366f1; margin-right: 6px; font-size: 1.1em; vertical-align: middle;"></i>
                            @endif

                            @if(!$transaction->user || empty($transaction->user->unique_id))
                            <span class="flagged-badge" title="No ID" style="margin-left: 0;">
                                <i class="fa fa-exclamation-circle"></i> No ID
                            </span>
                            @else
                            {{ $transaction->user->unique_id }}
                            @endif
                        </td>
                        <td>
                            @if($transaction->user && !empty($transaction->user->unique_id))
                            <a href="{{ route('member.transactions.detail', ['name' => str_replace(' ', '-', $transaction->user->name ?? ''), 'unique_id' => $transaction->user->unique_id]) }}"
                                style="text-decoration: none; color: inherit;">
                                <div class="employee-info">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($transaction->user->name) }}"
                                        alt="{{ $transaction->user->name }}">
                                    <div>
                                        <h4>{{ $transaction->user->name }}</h4>
                                        <span>{{ $transaction->user->email }}</span>
                                    </div>
                                </div>
                            </a>
                            @else
                            @php
                            // For flagged transactions, use transaction name field if user is null
                            $displayName = $transaction->user->name ?? $transaction->name ?? 'Flagged Transaction';
                            $displayEmail = $transaction->user->email ?? 'No user associated';
                            @endphp
                            <div class="employee-info">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($displayName) }}"
                                    alt="{{ $displayName }}">
                                <div>
                                    <h4>{{ $displayName }}</h4>
                                    <span>{{ $displayEmail }}</span>
                                    @if(!$transaction->user)
                                    <small style="color: #ef4444; font-style: italic;">No user data - Flagged transaction</small>
                                    @elseif(empty($transaction->user->unique_id))
                                    <small style="color: #ef4444; font-style: italic;">No unique ID - Cannot navigate</small>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="employee-info">
                                <div>
                                    <h4>{{ $transaction->user->phone ?? 'No phone' }}</h4>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($transaction->user && ($transaction->user->street || $transaction->user->area || $transaction->user->town || $transaction->user->postal_code))
                            <div style="font-size: 0.9em; color: #666; max-width: 200px;">
                                @if($transaction->user->street)
                                <div style="margin-bottom: 2px;">{{ $transaction->user->street }}</div>
                                @endif
                                @if($transaction->user->area)
                                <div style="margin-bottom: 2px;">{{ $transaction->user->area }}</div>
                                @endif
                                @if($transaction->user->town)
                                <div style="margin-bottom: 2px;">{{ $transaction->user->town }}</div>
                                @endif
                                @if($transaction->user->postal_code)
                                <div style="font-weight: 600; color: #333;">{{ $transaction->user->postal_code }}
                                </div>
                                @endif
                            </div>
                            @else
                            <span style="color: #999; font-style: italic; font-size: 0.9em;">No address</span>
                            @endif
                        </td>
                        <td>{{ $transaction->account ?? '-' }}</td>
                        <td>
                            @if(isset($transaction->flag_status) && $transaction->flag_status === 0)
                            <span class="flagged-status">
                                <i class="fa fa-flag"></i> Flagged
                            </span>
                            @elseif($transaction->user && $transaction->user->is_guest == 1)
                            <span class="guest-status">
                                <i class="fa fa-user-clock"></i> Guest
                            </span>
                            @else
                            <span
                                class="status-badge {{ strtolower($transaction->status) == 'cash' ? 'active' : 'inactive' }}"
                                style="position: relative; overflow: hidden;">
                                {{ strtolower($transaction->status) == 'cash' ? 'cash' : $transaction->status }}
                                <span class="animated-stripes"></span>
                            </span>
                            @endif
                        </td>
                        <td>
                            <div class="activity-info">
                                <span>{{ $transaction->date }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="progress-bar-container">
                                <div class="progress-bar-outer" title="Amount: £{{ $transaction->amount }}">
                                    <div class="progress-bar-inner {{ $isFull ? 'progress-bar-green' : 'progress-bar-blue' }}"
                                        style="width: {{ $progressPercent }}%;">
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span>£{{ $transaction->amount }}/£{{ number_format($annualFeeAmount, 2) }}</span>
                        </td>
                        <td>
                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete">
                                    <span class="btn-content">
                                        <i class="fa-light fa-trash"></i>
                                        <p class="btn-text">Delete</p>
                                    </span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            <div id="pagination-container">
                @include('layouts.custom_pagination', ['paginator' => $transactions])
            </div>
        </div>
    </div>
    </div>
</main>

<!-- Reserve Transactions Modal -->
<div id="reserveModal" class="reserve-modal" style="display: none;">
    <div class="reserve-modal-content">
        <!-- Modal Header -->
        <div class="reserve-modal-header">
            <h3><i class="fa-solid fa-archive" style="margin-right: 8px; color: #ef4444;"></i>Reserve Transactions</h3>
            <button onclick="closeReserveModal()" class="reserve-close-btn">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Step 1: Select Month/Year -->
        <div id="reserveStep1" class="reserve-step active">
            <div class="reserve-step-content">
                <h4>Select Year and Month</h4>
                <p>Choose the year and month for the transactions you want to reserve (delete).</p>

                <div class="reserve-scrollable-content">
                    <div class="reserve-form-group">
                        <label for="reserveYear">Year:</label>
                        <select id="reserveYear" class="reserve-input">
                            <option value="">Select Year</option>
                            @php
                            $currentYear = date('Y');
                            @endphp
                            @for($y = $currentYear; $y >= 2018; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="reserve-form-group">
                        <label for="reserveMonth">Month:</label>
                        <select id="reserveMonth" class="reserve-input">
                            <option value="">Select Month</option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                </div>

                <div class="reserve-actions">
                    <button onclick="closeReserveModal()" class="reserve-btn reserve-btn-secondary">Cancel</button>
                    <button onclick="previewReserveTransactions()" class="reserve-btn reserve-btn-primary" id="previewBtn" disabled>
                        <i class="fa-solid fa-eye"></i> Preview Transactions
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Preview Transactions -->
        <div id="reserveStep2" class="reserve-step">
            <div class="reserve-step-content">
                <h4>Preview Transactions to Reserve</h4>
                <p>Review the transactions that will be reserved (deleted) for <span id="previewPeriod"></span>.</p>

                <div class="reserve-scrollable-content">
                    <div class="reserve-summary">
                        <div class="reserve-summary-item">
                            <span class="reserve-summary-label">Total Transactions:</span>
                            <span class="reserve-summary-value" id="totalTransactions">0</span>
                        </div>
                        <div class="reserve-summary-item">
                            <span class="reserve-summary-label">Total Amount:</span>
                            <span class="reserve-summary-value" id="totalAmount">£0.00</span>
                        </div>
                    </div>

                    <div class="reserve-transactions-list" id="reserveTransactionsList">
                        <!-- Transactions will be loaded here -->
                    </div>
                </div>

                <div class="reserve-actions">
                    <button onclick="showReserveStep(1)" class="reserve-btn reserve-btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </button>
                    <button onclick="confirmReserveTransactions()" class="reserve-btn reserve-btn-danger" id="confirmBtn">
                        <i class="fa-solid fa-trash"></i> Confirm & Reserve
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 3: Processing -->
        <div id="reserveStep3" class="reserve-step">
            <div class="reserve-step-content">
                <h4>Processing Reserve Request</h4>
                <p>Please wait while we process your reserve request...</p>

                <div class="reserve-scrollable-content">
                    <div class="reserve-progress">
                        <div class="reserve-progress-bar">
                            <div class="reserve-progress-fill" id="reserveProgressFill"></div>
                        </div>
                        <div class="reserve-progress-text" id="reserveProgressText">0% Complete</div>
                    </div>

                    <div class="reserve-status" id="reserveStatus">
                        <i class="fa-solid fa-spinner fa-spin"></i> Initializing...
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Completion -->
        <div id="reserveStep4" class="reserve-step">
            <div class="reserve-step-content">
                <h4 id="reserveCompletionTitle">Reserve Complete!</h4>
                <p id="reserveCompletionMessage">Transactions have been successfully reserved.</p>

                <div class="reserve-scrollable-content">
                    <div class="reserve-completion">
                        <i class="fa-solid fa-check-circle" id="reserveCompletionIcon"></i>

                        <div class="reserve-completion-summary" id="reserveCompletionSummary">
                            <!-- Summary will be shown here -->
                        </div>
                    </div>
                </div>

                <div class="reserve-actions">
                    <button onclick="closeReserveModal()" class="reserve-btn reserve-btn-primary">
                        <i class="fa-solid fa-check"></i> Done
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>>


@endsection
@section('script')
<script>
    // Column visibility control functions
    function toggleColumn(columnType) {
        const table = document.getElementById('transactionTable');
        const headerRow = table.querySelector('thead tr');
        const dataRows = table.querySelectorAll('tbody tr');

        let columnIndex = -1;

        // Determine column index based on type
        switch (columnType) {
            case 'member-id':
                columnIndex = 0;
                break;
            case 'name':
                columnIndex = 1;
                break;
            case 'phone':
                columnIndex = 2;
                break;
            case 'address':
                columnIndex = 3;
                break;
            case 'account':
                columnIndex = 4;
                break;
            case 'status':
                columnIndex = 5;
                break;
            case 'date':
                columnIndex = 6;
                break;
            case 'progress':
                columnIndex = 7;
                break;
            case 'amount':
                columnIndex = 8;
                break;
            case 'action':
                columnIndex = 9;
                break;
        }

        if (columnIndex === -1) return;

        // Toggle header
        const headerCell = headerRow.cells[columnIndex];
        headerCell.style.display = headerCell.style.display === 'none' ? '' : 'none';

        // Toggle data cells
        dataRows.forEach(row => {
            const cell = row.cells[columnIndex];
            if (cell) {
                cell.style.display = cell.style.display === 'none' ? '' : 'none';
            }
        });
    }

    function showAllColumns() {
        const checkboxes = document.querySelectorAll('.column-controls input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });

        const table = document.getElementById('transactionTable');
        const allCells = table.querySelectorAll('th, td');
        allCells.forEach(cell => {
            cell.style.display = '';
        });
    }

    function hideAllColumns() {
        const checkboxes = document.querySelectorAll('.column-controls input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        const table = document.getElementById('transactionTable');
        const allCells = table.querySelectorAll('th, td');
        allCells.forEach(cell => {
            cell.style.display = 'none';
        });
    }

    // Initialize column visibility based on checkboxes
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.column-controls input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            const columnType = checkbox.id.replace('show', '').toLowerCase();
            if (!checkbox.checked) {
                toggleColumn(columnType);
            }
        });
    });

    // Confirm delete for transaction
    function confirmDelete(btn) {
        if (confirm('Are you sure you want to delete this transaction?')) {
            btn.closest('form').submit();
        }
    }

    // Reserve Transactions Modal Functions
    let currentReserveData = null;

    function openReserveModal() {
        document.getElementById('reserveModal').style.display = 'flex';
        resetReserveModal();
    }

    function closeReserveModal() {
        document.getElementById('reserveModal').style.display = 'none';
        resetReserveModal();
    }

    function resetReserveModal() {
        document.getElementById('reserveMonth').value = '';
        document.getElementById('reserveYear').value = '';
        document.getElementById('previewBtn').disabled = true;
        showReserveStep(1);

        // Reset form validation
        document.getElementById('reserveMonth').style.borderColor = '#e5e7eb';
        document.getElementById('reserveYear').style.borderColor = '#e5e7eb';
    }

    function showReserveStep(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.reserve-step').forEach(step => {
            step.classList.remove('active');
        });

        // Show selected step
        document.getElementById(`reserveStep${stepNumber}`).classList.add('active');
    }

    // Enable/disable preview button based on form completion
    document.addEventListener('DOMContentLoaded', function() {
        const monthSelect = document.getElementById('reserveMonth');
        const yearSelect = document.getElementById('reserveYear');
        const previewBtn = document.getElementById('previewBtn');

        function checkFormCompletion() {
            const month = monthSelect.value;
            const year = yearSelect.value;
            previewBtn.disabled = !month || !year;
        }

        monthSelect.addEventListener('change', checkFormCompletion);
        yearSelect.addEventListener('change', checkFormCompletion);
    });

    async function previewReserveTransactions() {
        const month = document.getElementById('reserveMonth').value;
        const year = document.getElementById('reserveYear').value;

        if (!month || !year) {
            alert('Please select both month and year');
            return;
        }

        try {
            // Show loading state
            document.getElementById('previewBtn').innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';
            document.getElementById('previewBtn').disabled = true;

            // Fetch transactions for the selected month/year
            const response = await fetch(`/api/transactions/preview-reserve?month=${month}&year=${year}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                currentReserveData = data;
                displayReservePreview(data);
                showReserveStep(2);
            } else {
                alert('Error: ' + (data.message || 'Failed to load transactions'));
            }

        } catch (error) {
            console.error('Error previewing transactions:', error);
            alert('Error loading transactions. Please try again.');
        } finally {
            // Reset button state
            document.getElementById('previewBtn').innerHTML = '<i class="fa-solid fa-eye"></i> Preview Transactions';
            document.getElementById('previewBtn').disabled = false;
        }
    }

    function displayReservePreview(data) {
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        const monthName = monthNames[parseInt(data.month) - 1];
        document.getElementById('previewPeriod').textContent = `${monthName} ${data.year}`;
        document.getElementById('totalTransactions').textContent = data.transactions.length;
        document.getElementById('totalAmount').textContent = `£${parseFloat(data.totalAmount).toFixed(2)}`;

        const transactionsList = document.getElementById('reserveTransactionsList');
        transactionsList.innerHTML = '';

        if (data.transactions.length === 0) {
            transactionsList.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #64748b;">
                        <i class="fa-solid fa-inbox" style="font-size: 2em; margin-bottom: 16px; display: block;"></i>
                        <div>No transactions found for ${monthName} ${data.year}</div>
                    </div>
                `;
            return;
        }

        data.transactions.forEach(transaction => {
            const transactionItem = document.createElement('div');
            transactionItem.className = 'reserve-transaction-item';

            const memberName = transaction.user ? transaction.user.name : transaction.name || 'Unknown Member';
            const memberInitials = memberName.split(' ').map(n => n[0]).join('').toUpperCase();

            transactionItem.innerHTML = `
                    <div class="reserve-transaction-avatar">${memberInitials}</div>
                    <div class="reserve-transaction-info">
                        <div class="reserve-transaction-name">${memberName}</div>
                        <div class="reserve-transaction-details">
                            ${transaction.date} • ${transaction.status} • ${transaction.account || 'No Account'}
                        </div>
                    </div>
                    <div class="reserve-transaction-amount">£${parseFloat(transaction.amount).toFixed(2)}</div>
                `;

            transactionsList.appendChild(transactionItem);
        });
    }

    async function confirmReserveTransactions() {
        if (!currentReserveData) {
            alert('No transaction data available. Please preview transactions first.');
            return;
        }

        const month = document.getElementById('reserveMonth').value;
        const year = document.getElementById('reserveYear').value;
        const totalTransactions = currentReserveData.transactions.length;

        if (totalTransactions === 0) {
            alert('No transactions to reserve for the selected period.');
            return;
        }

        const confirmMessage = `Are you absolutely sure you want to reserve (delete) ${totalTransactions} transactions for ${year} ${getMonthName(month)}?\n\nThis action cannot be undone!`;

        if (!confirm(confirmMessage)) {
            return;
        }

        // Proceed with reservation
        showReserveStep(3);
        await processReserveTransactions(month, year);
    }

    async function processReserveTransactions(month, year) {
        try {
            const progressFill = document.getElementById('reserveProgressFill');
            const progressText = document.getElementById('reserveProgressText');
            const statusElement = document.getElementById('reserveStatus');

            // Update status
            statusElement.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Starting reservation process...';
            progressFill.style.width = '10%';
            progressText.textContent = '10% Complete';

            // Simulate progress for better UX
            await simulateProgress(progressFill, progressText, statusElement);

            // Make actual API call to reserve transactions
            const response = await fetch('/api/transactions/reserve', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    month: month,
                    year: year
                })
            });

            const data = await response.json();

            if (data.success) {
                // Show completion
                progressFill.style.width = '100%';
                progressText.textContent = '100% Complete';
                statusElement.innerHTML = '<i class="fa-solid fa-check"></i> Reservation completed successfully!';

                // Wait a moment then show completion step
                setTimeout(() => {
                    showReserveCompletion(data);
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to reserve transactions');
            }

        } catch (error) {
            console.error('Error processing reserve:', error);
            statusElement.innerHTML = '<i class="fa-solid fa-exclamation-triangle"></i> Error: ' + error.message;

            // Show error in completion step
            setTimeout(() => {
                showReserveError(error.message);
            }, 2000);
        }
    }

    async function simulateProgress(progressFill, progressText, statusElement) {
        const steps = [{
                progress: 25,
                status: 'Validating transaction data...'
            },
            {
                progress: 50,
                status: 'Preparing for deletion...'
            },
            {
                progress: 75,
                status: 'Processing transactions...'
            },
            {
                progress: 90,
                status: 'Finalizing reservation...'
            }
        ];

        for (const step of steps) {
            await new Promise(resolve => setTimeout(resolve, 800));
            progressFill.style.width = step.progress + '%';
            progressText.textContent = step.progress + '% Complete';
            statusElement.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + step.status;
        }
    }

    function showReserveCompletion(data) {
        const monthName = getMonthName(document.getElementById('reserveMonth').value);
        const year = document.getElementById('reserveYear').value;

        document.getElementById('reserveCompletionTitle').textContent = 'Reserve Complete!';
        document.getElementById('reserveCompletionMessage').textContent = `Successfully reserved ${data.reservedCount} transactions for ${monthName} ${year}.`;

        document.getElementById('reserveCompletionSummary').innerHTML = `
                <h5>Reservation Summary</h5>
                <ul>
                    <li><strong>Period:</strong> ${monthName} ${year}</li>
                    <li><strong>Transactions Reserved:</strong> ${data.reservedCount}</li>
                    <li><strong>Total Amount:</strong> £${parseFloat(data.totalAmount).toFixed(2)}</li>
                    <li><strong>Date:</strong> ${new Date().toLocaleDateString()}</li>
                </ul>
            `;

        showReserveStep(4);
    }

    function showReserveError(errorMessage) {
        document.getElementById('reserveCompletionIcon').className = 'fa-solid fa-exclamation-triangle';
        document.getElementById('reserveCompletionIcon').style.color = '#ef4444';
        document.getElementById('reserveCompletionTitle').textContent = 'Reserve Failed';
        document.getElementById('reserveCompletionMessage').textContent = 'An error occurred while reserving transactions.';

        document.getElementById('reserveCompletionSummary').innerHTML = `
                <h5>Error Details</h5>
                <p style="color: #ef4444; margin: 0;">${errorMessage}</p>
            `;

        showReserveStep(4);
    }

    function getMonthName(month) {
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        return monthNames[parseInt(month) - 1];
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('reserveModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeReserveModal();
                }
            });
        }
    });
</script>
@endsection
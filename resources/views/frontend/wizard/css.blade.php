<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .form-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        padding: 2.5rem;
        margin: 2rem 0;
        border: 1px solid rgba(255, 106, 0, 0.1);
    }

    .form-header {
        text-align: center;
        margin-bottom: 2.5rem;
        color: var(--ink);
        padding-bottom: 1.5rem;
        border-bottom: 2px solid rgba(255, 106, 0, 0.2);
    }

    .form-header h2 {
        font-weight: 800;
        margin-bottom: 0.5rem;
        color: var(--ink);
    }

    .form-header p {
        color: var(--muted);
        font-size: 1.05rem;
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2.5rem;
        position: relative;
        counter-reset: step;
    }

    .step-indicator::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        right: 0;
        height: 3px;
        background-color: #e9ecef;
        z-index: 1;
        border-radius: 3px;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .step-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        color: #6c757d;
        font-weight: 700;
        font-size: 1.1rem;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .step.active .step-circle {
        background-color: var(--brand);
        color: white;
        transform: scale(1.05);
    }

    .step.completed .step-circle {
        background-color: #28a745;
        color: white;
    }

    .step.completed .step-circle::after {
        content: '✓';
        font-weight: bold;
    }

    .step-label {
        font-size: 0.9rem;
        text-align: center;
        font-weight: 600;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .step.active .step-label {
        color: var(--brand);
        font-weight: 700;
    }

    .step.completed .step-label {
        color: #28a745;
    }

    .form-step {
        display: none;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-step.active {
        display: block;
    }

    .form-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e9ecef;
    }

    .required::after {
        content: " *";
        color: #dc3545;
    }

    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f1f3f4;
    }

    .section-title {
        color: var(--brand);
        margin-bottom: 1.25rem;
        font-weight: 700;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        font-size: 1.4rem;
    }

    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 0.75rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 0.25rem rgba(255, 106, 0, 0.15);
    }

    .btn-brand {
        background: var(--brand);
        color: white;
        border: none;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-brand:hover {
        background: var(--brand-dark);
        color: rgb(12, 39, 248);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 106, 0, 0.3);
    }

    .btn-outline-brand {
        background: transparent;
        color: var(--brand);
        border: 2px solid var(--brand);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-outline-brand:hover {
        background: var(--brand);
        color: rgb(104, 10, 255);
        transform: translateY(-2px);
    }

    .form-check-input:checked {
        background-color: var(--brand);
        border-color: var(--brand);
    }

    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .upload-area:hover {
        border-color: var(--brand);
        background-color: rgba(255, 106, 0, 0.05);
    }

    .upload-icon {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .upload-area:hover .upload-icon {
        color: var(--brand);
    }

    .review-card {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .review-card-header {
        background: var(--brand);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .review-card-body {
        padding: 1.5rem;
    }

    .review-item {
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f1f3f4;
    }

    .review-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .review-label {
        font-weight: 600;
        color: var(--ink);
    }

    .review-value {
        color: var(--muted);
    }

    .form-icon {
        color: var(--brand);
        font-size: 1.1rem;
    }

    .info-text {
        font-size: 0.9rem;
        color: var(--muted);
        margin-top: 0.5rem;
    }

    .form-alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        background: rgba(255, 106, 0, 0.1);
        border-left: 4px solid var(--brand);
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 1.5rem;
            margin: 1rem 0;
        }

        .step-label {
            font-size: 0.8rem;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .checkbox-grid {
            grid-template-columns: 1fr;
        }

        .form-navigation {
            flex-direction: column;
            gap: 1rem;
        }

        .form-navigation button {
            width: 100%;
        }
    }
</style>

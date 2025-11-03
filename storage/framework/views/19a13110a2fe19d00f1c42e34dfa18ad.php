<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounting System - Revenue & Expense Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 40px;
            text-align: center;
            color: white;
        }

        .logo {
            font-size: 80px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 18px;
            opacity: 0.9;
        }

        .content {
            padding: 50px 40px;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .feature-card {
            background: #f8fafc;
            padding: 30px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .feature-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .feature-desc {
            color: #64748b;
            line-height: 1.6;
        }

        .actions {
            text-align: center;
            padding: 40px 0;
            border-top: 1px solid #e2e8f0;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            margin: 0 10px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            transform: translateY(-2px);
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .stat {
            text-align: center;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
        }

        .footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .header {
                padding: 40px 20px;
            }

            h1 {
                font-size: 32px;
            }

            .content {
                padding: 30px 20px;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .btn {
                display: block;
                margin: 10px 0;
            }

            .stats {
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">💰</div>
            <h1>Accounting System</h1>
            <p class="subtitle">Revenue & Expense Management for IT Company</p>
        </div>

        <div class="content">
            <div class="features">
                <div class="feature-card">
                    <div class="feature-icon">🏦</div>
                    <div class="feature-title">Account Management</div>
                    <div class="feature-desc">
                        Manage multiple financial accounts including bank accounts, cash, and credit cards with real-time balance tracking.
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <div class="feature-title">Transaction Tracking</div>
                    <div class="feature-desc">
                        Record and manage all revenue and expense transactions with automatic account balance updates.
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🏷️</div>
                    <div class="feature-title">Category Management</div>
                    <div class="feature-desc">
                        Organize transactions with custom categories for revenue and expense types to better track your finances.
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📈</div>
                    <div class="feature-title">Financial Reports</div>
                    <div class="feature-desc">
                        Generate detailed reports by day, month, year, or custom date range with profit analysis and category breakdowns.
                    </div>
                </div>
            </div>

            <div class="actions">
                <a href="/api-docs.html" class="btn btn-primary">📖 View API Documentation</a>
                <a href="<?php echo e(route('test-reports.index')); ?>" class="btn btn-secondary">📊 Test Reports</a>
            </div>

            <div class="stats">
                <div class="stat">
                    <div class="stat-value">4</div>
                    <div class="stat-label">API Groups</div>
                </div>
                <div class="stat">
                    <div class="stat-value">20+</div>
                    <div class="stat-label">Endpoints</div>
                </div>
                <div class="stat">
                    <div class="stat-value">REST</div>
                    <div class="stat-label">API Style</div>
                </div>
                <div class="stat">
                    <div class="stat-value">v1.0.0</div>
                    <div class="stat-label">Version</div>
                </div>
                <div class="stat">
                    <div class="stat-value">24</div>
                    <div class="stat-label">Unit Tests</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>
                <strong>Accounting System API</strong> | 
                Built with Laravel Framework | 
                Author: <a href="mailto:gin_vn@haldata.net">Gin</a>
            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\Project\example_unit_test\resources\views/home.blade.php ENDPATH**/ ?>
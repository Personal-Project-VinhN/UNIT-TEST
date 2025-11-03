<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Reports - Accounting System</title>
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
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .report-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        
        .report-card.available {
            border-left: 5px solid #28a745;
        }
        
        .report-card.unavailable {
            border-left: 5px solid #dc3545;
            opacity: 0.8;
        }
        
        .report-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .report-card h2 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .report-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .status.available {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status.unavailable {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: opacity 0.3s ease;
            margin-right: 10px;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .info-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-top: 30px;
        }
        
        .info-box h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .info-box ul {
            list-style: none;
            padding-left: 0;
        }
        
        .info-box li {
            padding: 8px 0;
            color: #666;
            border-bottom: 1px solid #eee;
        }
        
        .info-box li:last-child {
            border-bottom: none;
        }
        
        .info-box code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            color: #e83e8c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Test Reports</h1>
            <p>View test coverage and results for Accounting System</p>
        </div>
        
        <div class="reports-grid">
            <!-- HTML Coverage Report -->
            <div class="report-card <?php echo e($reports['coverage']['available'] ? 'available' : 'unavailable'); ?>">
                <div class="report-icon">📈</div>
                <h2>HTML Coverage Report</h2>
                <span class="status <?php echo e($reports['coverage']['available'] ? 'available' : 'unavailable'); ?>">
                    <?php echo e($reports['coverage']['available'] ? '✅ Available' : '❌ Not Found'); ?>

                </span>
                <p><?php echo e($reports['coverage']['description']); ?></p>
                <?php if($reports['coverage']['available']): ?>
                    <a href="<?php echo e($reports['coverage']['url']); ?>" target="_blank" class="btn">View Report</a>
                <?php else: ?>
                    <span class="btn" style="background: #ccc; cursor: not-allowed;">Generate Report</span>
                <?php endif; ?>
            </div>
            
            <!-- TestDox Report -->
            <div class="report-card <?php echo e($reports['testdox']['available'] ? 'available' : 'unavailable'); ?>">
                <div class="report-icon">📋</div>
                <h2>TestDox Report</h2>
                <span class="status <?php echo e($reports['testdox']['available'] ? 'available' : 'unavailable'); ?>">
                    <?php echo e($reports['testdox']['available'] ? '✅ Available' : '❌ Not Found'); ?>

                </span>
                <p><?php echo e($reports['testdox']['description']); ?></p>
                <?php if($reports['testdox']['available']): ?>
                    <a href="<?php echo e($reports['testdox']['url']); ?>" target="_blank" class="btn">View Report</a>
                <?php else: ?>
                    <span class="btn" style="background: #ccc; cursor: not-allowed;">Generate Report</span>
                <?php endif; ?>
            </div>
            
        </div>
        
        <div class="info-box">
            <h3>ℹ️ How to Generate Reports</h3>
            <ul>
                <li><strong>Windows:</strong> Run <code>.\generate-test-reports.bat</code> in project root</li>
                <li><strong>Linux/Mac:</strong> Run <code>./generate-test-reports.sh</code></li>
                <li><strong>Manual:</strong> <code>vendor/bin/phpunit --coverage-html tests/results/coverage</code></li>
                <li>Reports will be automatically generated in <code>tests/results/</code> folder</li>
                <li>Refresh this page after generating reports to see updates</li>
            </ul>
        </div>
    </div>
</body>
</html>

<?php /**PATH D:\Project\example_unit_test\resources\views/test-reports/index.blade.php ENDPATH**/ ?>
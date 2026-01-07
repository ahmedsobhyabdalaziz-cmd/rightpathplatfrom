<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate - {{ $certificate->certificate_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            box-sizing: border-box;
        }
        .certificate {
            background: white;
            border: 4px solid #10b981;
            border-radius: 16px;
            padding: 60px;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }
        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-text {
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        h1 {
            font-size: 32px;
            color: #1e293b;
            margin: 0 0 10px;
            font-weight: bold;
        }
        .subtitle {
            color: #64748b;
            font-size: 16px;
            margin-bottom: 40px;
        }
        .recipient-name {
            font-size: 42px;
            color: #10b981;
            font-weight: bold;
            margin: 20px 0 40px;
        }
        .course-label {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .course-name {
            font-size: 24px;
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 50px;
        }
        .details {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }
        .detail-item {
            text-align: center;
        }
        .detail-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .detail-value {
            font-size: 14px;
            color: #1e293b;
            font-weight: 600;
            margin-top: 4px;
        }
        .verification {
            margin-top: 40px;
            font-size: 11px;
            color: #94a3b8;
        }
        .verification a {
            color: #10b981;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="logo">
            <span class="logo-text">R</span>
        </div>

        <h1>Certificate of Completion</h1>
        <p class="subtitle">This is to certify that</p>

        <div class="recipient-name">{{ $user->name }}</div>

        <p class="course-label">has successfully completed</p>
        <div class="course-name">{{ $course->title }}</div>

        <div class="details">
            <div class="detail-item">
                <div class="detail-label">Date Issued</div>
                <div class="detail-value">{{ $issued_date }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Certificate ID</div>
                <div class="detail-value">{{ $certificate_number }}</div>
            </div>
        </div>

        <div class="verification">
            Verify this certificate at: {{ $verification_url }}
        </div>
    </div>
</body>
</html>












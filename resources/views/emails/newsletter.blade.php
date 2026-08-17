<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset Styles */
        body, table, td {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Email Container */
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dddddd;
        }

        /* Header */
        .email-header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        /* Subject */
        .email-subject {
            background-color: #f8f9fa;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #dddddd;
        }

        /* Body */
        .email-body {
            padding: 20px;
            font-size: 16px;
            line-height: 1.6;
            color: #333333;
        }

        /* Footer */
        .email-footer {
            background-color: #f4f4f4;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #666666;
        }

        .unsubscribe {
            color: #ff0000;
            text-decoration: none;
            font-weight: bold;
        }

        /* Responsive Styles */
        @media screen and (max-width: 600px) {
            .email-header {
                font-size: 20px;
                padding: 15px;
            }
            .email-subject {
                font-size: 16px;
                padding: 10px;
            }
            .email-body {
                font-size: 14px;
                padding: 15px;
            }
            .email-footer {
                font-size: 12px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

    <table class="email-container" align="center" cellpadding="0" cellspacing="0" width="100%">
        <!-- Email Header -->
        <tr>
            <td class="email-header">
                {{ config('app.name') }} Newsletter
            </td>
        </tr>

        <!-- Subject -->
        <tr>
            <td class="email-subject">
                {{ $subject }}
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td class="email-body">
                <p>Hello,</p>
                <p>{{ $messageContent }}</p>
                <p>We appreciate your subscription to our newsletter!</p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td class="email-footer">
                <p>Thank you for subscribing!</p>
                <p><a href="#" class="unsubscribe">Unsubscribe</a></p>
            </td>
        </tr>
    </table>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $messageData['subject'] ?? 'Notification' }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background-color: #f9fafb;">
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; background-color: #f9fafb;">
        <tr>
            <td style="padding: 20px 0;">
                
                <!-- Main Content Table -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #059669 100%); padding: 40px 32px; text-align: center; border-radius: 16px 16px 0 0;">
                            <h1 style="margin: 0 0 8px 0; color: white; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">{{ $appName }}</h1>
                            <p style="margin: 0; color: rgba(255,255,255,0.9); font-size: 16px; font-weight: 400;">{{ $messageData['subject'] ?? 'Notification' }}</p>
                        </td>
                    </tr>
                    
                    <!-- Greeting -->
                    <tr>
                        <td style="padding: 40px 32px 32px 32px; text-align: center;">
                            <h2 style="margin: 0; font-size: 24px; font-weight: 700; color: #1f2937; letter-spacing: -0.3px;">Hello {{ $messageData['user_name'] ?? 'There' }}!</h2>
                        </td>
                    </tr>
                    
                    <!-- Notification Card -->
                    <tr>
                        <td style="padding: 0 32px 32px 32px;">
                            @php
                                $type = $messageData['type'] ?? 'info';
                                
                                $bgColor = match($type) {
                                    'success' => 'background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%); border-left: 4px solid #059669;',
                                    'error' => 'background: linear-gradient(135deg, #fef2f2 0%, #fef7f7 100%); border-left: 4px solid #dc2626;',
                                    'warning' => 'background: linear-gradient(135deg, #fffbeb 0%, #fefce8 100%); border-left: 4px solid #f59e0b;',
                                    default => 'background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%); border-left: 4px solid #3b82f6;'
                                };
                                
                                $iconBg = match($type) {
                                    'success' => '#059669',
                                    'error' => '#dc2626',
                                    'warning' => '#f59e0b',
                                    default => '#3b82f6'
                                };
                                
                                $iconSymbol = match($type) {
                                    'success' => '✓',
                                    'error' => '✕',
                                    'warning' => '!',
                                    default => 'i'
                                };
                            @endphp
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; {{ $bgColor }} border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                                <tr>
                                    <td style="padding: 32px; position: relative;">
                                        
                                        <!-- Notification Content Table -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%;">
                                            <tr>
                                                <!-- Message Content -->
                                                <td style="width: 80%; vertical-align: top;">
                                                    <div style="font-size: 16px; line-height: 1.7; color: #374151; margin-right: 20px;">
                                                        {!! nl2br(e($messageData['response'])) !!}
                                                    </div>
                                                </td>
                                                
                                                <!-- Icon -->
                                                <td style="width: 20%; vertical-align: top; text-align: right;">
                                                    <div style="width: 40px; height: 40px; border-radius: 12px; background-color: {{ $iconBg }}; color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                                        {{ $iconSymbol }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Action Button (if provided) -->
                    @if(isset($messageData['action_url']))
                    <tr>
                        <td style="padding: 0 32px 40px 32px; text-align: center;">
                            <a href="{{ $messageData['action_url'] }}" style="display: inline-block; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 16px 32px; text-decoration: none; border-radius: 12px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                                {{ $messageData['action_text'] ?? 'View Details' }}
                            </a>
                        </td>
                    </tr>
                    @endif
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 32px;">
                            <div style="height: 1px; background: linear-gradient(90deg, transparent, #e5e7eb, transparent); margin: 24px 0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Timestamp Section -->
                    <tr>
                        <td style="padding: 0 32px 32px 32px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px;">
                                <tr>
                                    <td style="padding: 20px; text-align: center;">
                                        <div style="font-size: 14px; color: #6b7280; font-weight: 500;">
                                            <span style="font-size: 16px; margin-right: 8px;">📅</span>
                                            Sent on {{ now()->format('F j, Y \a\t g:i A') }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: #f9fafb; padding: 32px; text-align: center; border-top: 1px solid #e5e7eb; border-radius: 0 0 16px 16px;">
                            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
                            </p>
                            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                You're receiving this because of your notification preferences.
                            </p>
                            
                            <!-- Divider -->
                            <div style="height: 1px; background: linear-gradient(90deg, transparent, #e5e7eb, transparent); margin: 16px 0;"></div>
                            
                            <p style="margin: 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                <a href="{{ config('app.url') }}/settings" style="color: #3b82f6; text-decoration: none; font-weight: 600;">Update your notification preferences</a>
                            </p>
                            
                            <p style="margin: 16px 0 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Need help? Contact us at <a href="mailto:{{ $settings->support_email ?? 'support@cyberforum.com' }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">{{ $settings->support_email ?? 'support@cyberforum.com' }}</a>
                            </p>
                        </td>
                    </tr>
                    
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>
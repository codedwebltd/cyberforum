<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to {{ $appName }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background-color: #f8fafc; line-height: 1.6;">
    
    @php
    function formatFileSize(int $bytes): string
    {
        if ($bytes == 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = floor(log($bytes, 1024));
        $size = $bytes / pow(1024, $power);
        
        if ($power == 0) {
            return round($size) . ' ' . $units[$power];
        } elseif ($power == 1) {
            $decimals = $size < 10 ? 1 : 0;
        } elseif ($power >= 2) {
            if ($size < 10) {
                $decimals = 2;
            } elseif ($size < 100) {
                $decimals = 1;
            } else {
                $decimals = 0;
            }
        }
        
        return round($size, $decimals) . ' ' . $units[$power];
    }
    
    // Calculate user's total storage usage
    $totalStorageUsed = 0; // You'll need to calculate this from user's files
    $storageLimit = ($user->capped_file_size ?? 1024) * 1024 * 1024; // Convert MB to bytes
    $storagePercentage = $storageLimit > 0 ? round(($totalStorageUsed / $storageLimit) * 100, 1) : 0;
    @endphp

    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); padding: 40px 30px; text-align: center;">
                <h1 style="margin: 0; color: white; font-size: 28px; font-weight: 700;">Welcome to {{ $appName }}</h1>
                <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 16px;">Your journey starts here</p>
            </td>
        </tr>
        
        <!-- User Info Card -->
        <tr>
            <td style="padding: 30px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <tr>
                        <td style="padding: 30px; text-align: center;">
                            <!-- Avatar -->
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: #3b82f6; color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 700; margin: 0 auto 20px;">
                                {{-- {{ substr($user->name, 0, 1) }} --}}
                            </div>
                            
                            <h2 style="margin: 0 0 15px 0; font-size: 24px; font-weight: 700; color: #1f2937;">{{ $user->name }}</h2>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; font-size: 14px; color: #6b7280;">
                                <tr>
                                    <td style="padding: 5px 0; text-align: left; width: 35%; font-weight: 600;">Username:</td>
                                    <td style="padding: 5px 0; text-align: left;">{{ $user->username }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 0; text-align: left; font-weight: 600;">Email:</td>
                                    <td style="padding: 5px 0; text-align: left;">{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 0; text-align: left; font-weight: 600;">Member since:</td>
                                    <td style="padding: 5px 0; text-align: left;">{{ $user->created_at->format('F Y') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 0; text-align: left; font-weight: 600;">Storage:</td>
                                    <td style="padding: 5px 0; text-align: left;">{{ formatFileSize($totalStorageUsed) }} / {{ formatFileSize($storageLimit) }} ({{ $storagePercentage }}%)</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 0; text-align: left; font-weight: 600;">Points:</td>
                                    <td style="padding: 5px 0; text-align: left; color: #059669; font-weight: 700;">{{ $user->points ?? 0 }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <!-- Referral Section -->
        <tr>
            <td style="padding: 0 30px 30px 30px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; background: #eff6ff; border-radius: 12px; border: 1px solid #dbeafe;">
                    <tr>
                        <td style="padding: 25px; text-align: center;">
                            <h3 style="margin: 0 0 15px 0; font-size: 20px; font-weight: 700; color: #1d4ed8;">Invite Friends & Earn Points</h3>
                            <p style="margin: 0 0 20px 0; font-size: 14px; color: #6b7280;">
                                Share your referral code and earn 200 points for each friend who joins!
                            </p>
                            
                            <!-- Referral Code -->
                            <div style="margin: 15px 0;">
                                <div style="font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px; text-transform: uppercase;">Your Referral Code:</div>
                                <div style="background: white; border: 2px solid #3b82f6; border-radius: 8px; padding: 15px; font-family: monospace; font-size: 18px; font-weight: 700; color: #1d4ed8; letter-spacing: 2px;">{{ $user->referral_code }}</div>
                            </div>
                            
                            <!-- Referral Link -->
                            <div style="margin: 15px 0;">
                                <div style="font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px; text-transform: uppercase;">Share This Link:</div>
                                <div style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px; word-break: break-all; font-size: 12px; color: #64748b; font-family: monospace;">{{ url('/register?ref='.$user->referral_code) }}</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <!-- Action Buttons -->
        <tr>
            <td style="padding: 0 30px 30px 30px; text-align: center;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                    <tr>
                        <td style="padding: 0 5px;">
                            <a href="{{ url('/') }}" style="display: inline-block; background: #3b82f6; color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px;">Get Started</a>
                        </td>
                        <td style="padding: 0 5px;">
                            <a href="{{ url('/profile/edit') }}" style="display: inline-block; background: white; color: #3b82f6; border: 2px solid #3b82f6; padding: 12px 26px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px;">Complete Profile</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <!-- Next Steps -->
        <tr>
            <td style="padding: 0 30px 30px 30px;">
                <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 700; color: #1f2937; text-align: center;">What's Next?</h3>
                
                <!-- Step 1 -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; margin-bottom: 15px;">
                    <tr>
                        <td style="width: 40px; vertical-align: top; padding-top: 5px;">
                            {{-- <div style="width: 30px; height: 30px; background: #3b82f6; color: white; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">1</div> --}}
                        </td>
                        <td style="padding-left: 15px;">
                            <div style="font-weight: 700; color: #1f2937; font-size: 16px; margin-bottom: 3px;">Complete your profile</div>
                            <div style="color: #6b7280; font-size: 14px;">Add your photo, bio, and skills</div>
                        </td>
                    </tr>
                </table>
                
                <!-- Step 2 -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; margin-bottom: 15px;">
                    <tr>
                        <td style="width: 40px; vertical-align: top; padding-top: 5px;">
                            {{-- <div style="width: 30px; height: 30px; background: #3b82f6; color: white; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">2</div> --}}
                        </td>
                        <td style="padding-left: 15px;">
                            <div style="font-weight: 700; color: #1f2937; font-size: 16px; margin-bottom: 3px;">Create your first post</div>
                            <div style="color: #6b7280; font-size: 14px;">Share your thoughts with the community</div>
                        </td>
                    </tr>
                </table>
                
                <!-- Step 3 -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; margin-bottom: 15px;">
                    <tr>
                        <td style="width: 40px; vertical-align: top; padding-top: 5px;">
                            {{-- <div style="width: 30px; height: 30px; background: #3b82f6; color: white; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">3</div> --}}
                        </td>
                        <td style="padding-left: 15px;">
                            <div style="font-weight: 700; color: #1f2937; font-size: 16px; margin-bottom: 3px;">Connect with others</div>
                            <div style="color: #6b7280; font-size: 14px;">Follow interesting people and join discussions</div>
                        </td>
                    </tr>
                </table>
                
                <!-- Step 4 -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%;">
                    <tr>
                        <td style="width: 40px; vertical-align: top; padding-top: 5px;">
                            {{-- <div style="width: 30px; height: 30px; background: #3b82f6; color: white; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">4</div> --}}
                        </td>
                        <td style="padding-left: 15px;">
                            <div style="font-weight: 700; color: #1f2937; font-size: 16px; margin-bottom: 3px;">Explore features</div>
                            <div style="color: #6b7280; font-size: 14px;">Check out discussions, marketplace, and events</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <!-- Footer -->
        <tr>
            <td style="background: #f8fafc; padding: 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                <p style="margin: 0 0 10px 0; color: #6b7280; font-size: 14px;">
                    &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
                </p>
                <p style="margin: 0; color: #6b7280; font-size: 13px;">
                    Need help? Contact us at <a href="mailto:support@{{ strtolower($appName) }}.com" style="color: #3b82f6; text-decoration: none;">support@{{ strtolower($appName) }}.com</a>
                </p>
            </td>
        </tr>
        
    </table>
      
</body>
</html>
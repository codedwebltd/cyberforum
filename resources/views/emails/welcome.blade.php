<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to {{ $appName }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background-color: #f9fafb;">
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; background-color: #f9fafb;">
        <tr>
            <td style="padding: 20px 0;">
                
                <!-- Main Content Table -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; max-width: 650px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #059669 100%); padding: 48px 40px; text-align: center; border-radius: 16px 16px 0 0;">
                            <h1 style="margin: 0 0 8px 0; color: white; font-size: 32px; font-weight: 700; letter-spacing: -0.5px;">{{ $appName }}</h1>
                            <p style="margin: 0; color: rgba(255,255,255,0.9); font-size: 18px; font-weight: 400;">Welcome to the Community</p>
                        </td>
                    </tr>
                    
                    <!-- Welcome Section -->
                    <tr>
                        <td style="padding: 48px 40px 24px 40px; text-align: center;">
                            <h2 style="margin: 0 0 16px 0; font-size: 32px; font-weight: 700; color: #1f2937; letter-spacing: -0.5px;">Welcome to your new journey!</h2>
                            <p style="margin: 0; font-size: 18px; color: #6b7280; line-height: 1.7; max-width: 500px; margin: 0 auto;">
                                We're thrilled to have you join our vibrant community of professionals, creators, and innovators. Your adventure starts now!
                            </p>
                        </td>
                    </tr>
                    
                    <!-- User Card -->
                    <tr>
                        <td style="padding: 0 40px 24px 40px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%); border: 1px solid #e5e7eb; border-radius: 16px;">
                                <tr>
                                    <td style="padding: 40px 32px; text-align: center;">
                                        <!-- Avatar -->
                                        <div style="width: 88px; height: 88px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 36px; font-weight: 700; margin: 0 auto 24px; border: 4px solid white; box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <h3 style="margin: 0 0 12px 0; font-size: 28px; font-weight: 700; color: #1f2937; letter-spacing: -0.5px;">{{ $user->name }}</h3>
                                        <div style="color: #6b7280; font-size: 16px; line-height: 1.8;">
                                            <strong>Username:</strong> {{ $user->username }}<br>
                                            <strong>Email:</strong> {{ $user->email }}<br>
                                            <strong>Member since:</strong> {{ $user->created_at->format('F Y') }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Stats Grid -->
                    <tr>
                        <td style="padding: 24px 40px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%;">
                                <tr>
                                    <!-- Stat 1 -->
                                    <td style="width: 33.33%; padding: 0 8px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; background: white; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                            <tr>
                                                <td style="padding: 32px 24px; text-align: center;">
                                                    <div style="width: 56px; height: 56px; border-radius: 16px; background: #eff6ff; color: #3b82f6; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 600; margin: 0 auto 16px;">🎁</div>
                                                    <div style="font-size: 28px; font-weight: 800; color: #1f2937; margin-bottom: 8px; letter-spacing: -0.5px;">{{ $user->points }}</div>
                                                    <div style="font-size: 14px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Welcome Points</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    
                                    <!-- Stat 2 -->
                                    <td style="width: 33.33%; padding: 0 8px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; background: white; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                            <tr>
                                                <td style="padding: 32px 24px; text-align: center;">
                                                    <div style="width: 56px; height: 56px; border-radius: 16px; background: #eff6ff; color: #3b82f6; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 600; margin: 0 auto 16px;">👥</div>
                                                    <div style="font-size: 28px; font-weight: 800; color: #1f2937; margin-bottom: 8px; letter-spacing: -0.5px;">{{ $user->followers_count ?? 0 }}</div>
                                                    <div style="font-size: 14px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Followers</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    
                                    <!-- Stat 3 -->
                                    <td style="width: 33.33%; padding: 0 8px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; background: white; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                            <tr>
                                                <td style="padding: 32px 24px; text-align: center;">
                                                    <div style="width: 56px; height: 56px; border-radius: 16px; background: #eff6ff; color: #3b82f6; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 600; margin: 0 auto 16px;">📝</div>
                                                    <div style="font-size: 28px; font-weight: 800; color: #1f2937; margin-bottom: 8px; letter-spacing: -0.5px;">{{ $user->posts_count ?? 0 }}</div>
                                                    <div style="font-size: 14px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Posts Created</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Referral Section -->
                    <tr>
                        <td style="padding: 24px 40px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%); border: 2px solid #dbeafe; border-radius: 20px;">
                                <tr>
                                    <td style="padding: 40px 32px; text-align: center;">
                                        <h3 style="margin: 0 0 16px 0; font-size: 24px; font-weight: 700; color: #1d4ed8; letter-spacing: -0.3px;">Share & Earn Rewards</h3>
                                        <p style="margin: 0 0 32px 0; font-size: 16px; color: #6b7280;">
                                            Invite friends and earn 200 points for each successful referral!
                                        </p>
                                        
                                        <!-- Referral Code -->
                                        <div style="margin: 24px 0;">
                                            <div style="font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Your Referral Code:</div>
                                            <div style="background: white; border: 2px solid #3b82f6; border-radius: 12px; padding: 20px; font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Courier New', monospace; font-size: 20px; font-weight: 700; color: #1d4ed8; letter-spacing: 3px; margin-bottom: 24px;">{{ $user->referral_code }}</div>
                                        </div>
                                        
                                        <!-- Referral Link -->
                                        <div style="margin: 24px 0;">
                                            <div style="font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Your Referral Link:</div>
                                            <div style="background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; word-break: break-all; font-size: 14px; color: #6b7280; font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Courier New', monospace;">{{ url('/register?ref='.$user->referral_code) }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Action Buttons -->
                    <tr>
                        <td style="padding: 24px 40px; text-align: center;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                                <tr>
                                    <td style="padding: 0 8px;">
                                        <a href="{{ url('/home') }}" style="display: inline-block; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 16px 32px; text-decoration: none; border-radius: 12px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">🚀 Get Started</a>
                                    </td>
                                    <td style="padding: 0 8px;">
                                        <a href="{{ url('/home/onboarding') }}" style="display: inline-block; background: white; color: #3b82f6; border: 2px solid #3b82f6; padding: 14px 30px; text-decoration: none; border-radius: 12px; font-weight: 600; font-size: 16px;">⚙️ Complete Profile</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Next Steps -->
                    <tr>
                        <td style="padding: 24px 40px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; background: #f9fafb; border-radius: 20px;">
                                <tr>
                                    <td style="padding: 40px 32px;">
                                        <h3 style="margin: 0 0 32px 0; font-size: 24px; font-weight: 700; color: #1f2937; text-align: center; letter-spacing: -0.3px;">Here's what you can do next:</h3>
                                        
                                        <!-- Step 1 -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; margin-bottom: 16px;">
                                            <tr>
                                                <td style="width: 60px; vertical-align: top; padding-top: 8px;">
                                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">1</div>
                                                </td>
                                                <td style="background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 24px;">
                                                    <div style="font-weight: 700; color: #1f2937; font-size: 16px; margin-bottom: 4px;">Complete your profile setup</div>
                                                    <div style="color: #6b7280; font-size: 14px; line-height: 1.6;">Add your photo, bio, and skills to help others discover you</div>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Step 2 -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; margin-bottom: 16px;">
                                            <tr>
                                                <td style="width: 60px; vertical-align: top; padding-top: 8px;">
                                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">2</div>
                                                </td>
                                                <td style="background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 24px;">
                                                    <div style="font-weight: 700; color: #1f2937; font-size: 16px; margin-bottom: 4px;">Upload your first post</div>
                                                    <div style="color: #6b7280; font-size: 14px; line-height: 1.6;">Share your thoughts, projects, or questions with the community</div>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Step 3 -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; margin-bottom: 16px;">
                                            <tr>
                                                <td style="width: 60px; vertical-align: top; padding-top: 8px;">
                                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">3</div>
                                                </td>
                                                <td style="background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 24px;">
                                                    <div style="font-weight: 700; color: #1f2937; font-size: 16px; margin-bottom: 4px;">Connect with other members</div>
                                                    <div style="color: #6b7280; font-size: 14px; line-height: 1.6;">Follow interesting people and join conversations</div>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Step 4 -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%; margin-bottom: 16px;">
                                            <tr>
                                                <td style="width: 60px; vertical-align: top; padding-top: 8px;">
                                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">4</div>
                                                </td>
                                                <td style="background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 24px;">
                                                    <div style="font-weight: 700; color: #1f2937; font-size: 16px; margin-bottom: 4px;">Explore the marketplace</div>
                                                    <div style="color: #6b7280; font-size: 14px; line-height: 1.6;">Discover opportunities, services, and collaborations</div>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Step 5 -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="width: 100%;">
                                            <tr>
                                                <td style="width: 60px; vertical-align: top; padding-top: 8px;">
                                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">5</div>
                                                </td>
                                                <td style="background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 24px;">
                                                    <div style="font-weight: 700; color: #1f2937; font-size: 16px; margin-bottom: 4px;">Join discussions</div>
                                                    <div style="color: #6b7280; font-size: 14px; line-height: 1.6;">Participate in forums and groups that match your interests</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: #f9fafb; padding: 40px; text-align: center; border-top: 1px solid #e5e7eb; border-radius: 0 0 16px 16px;">
                            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
                            </p>
                            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                You're receiving this email because you created an account with us.
                            </p>
                            <p style="margin: 16px 0 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Need help? Contact us at <a href="mailto:support@cyberforum.com" style="color: #3b82f6; text-decoration: none; font-weight: 600;">support@cyberforum.com</a>
                            </p>
                        </td>
                    </tr>
                    
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>
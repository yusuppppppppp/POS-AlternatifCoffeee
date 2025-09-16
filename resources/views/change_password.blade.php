@extends('layouts.app')

@section('title', 'Edit Profile')

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

    .profile-section {
        margin-left: 24rem;
        padding: 40px;
        margin-top: -30px;
        max-width: 700px;
        position: relative;
    }

    .container.drawer-open .profile-section {
        margin-left: 10rem !important;
        margin-right: 0px !important;
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

    .form-container {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        margin-top: -20px;
        padding: 25px;
        border-radius: 24px;
        box-shadow: 
            0 25px 50px rgba(46, 71, 102, 0.25),
            0 0 0 1px rgba(255, 255, 255, 0.1);
        position: relative;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        font-weight: 600;
        color: #2E4766;
        display: block;
        margin-bottom: 8px;
        font-size: 15px;
    }

    .form-group input {
        width: 100%;
        padding: 12px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #fff;
        font-family: inherit;
    }

    .form-group input:focus {
        outline: none;
        border-color: #2E4766;
        box-shadow: 
            0 0 0 3px rgba(46, 71, 102, 0.1),
            0 4px 12px rgba(46, 71, 102, 0.1);
        transform: translateY(-1px);
    }

    .submit-btn {
        background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
        color: white;
        border: none;
        padding: 14px;
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
        margin-top: 8px;
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 
            0 8px 25px rgba(46, 71, 102, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    /* Top notification styles */
    .notification-container {
        position: fixed;
        top: 90px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1002;
        width: 90%;
        max-width: 500px;
    }

    .notification {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 10px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        transform: translateY(-100px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .notification.show {
        transform: translateY(0);
        opacity: 1;
    }

    .notification.hide {
        transform: translateY(-100px);
        opacity: 0;
    }

    .notification-success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 100%);
        color: white;
        border-left: 4px solid #059669;
    }

    .notification-error {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.95) 0%, rgba(220, 38, 38, 0.95) 100%);
        color: white;
        border-left: 4px solid #dc2626;
    }

    .notification-icon {
        font-size: 20px;
        flex-shrink: 0;
    }

    .notification-content {
        flex: 1;
    }

    .notification-close {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.2s;
    }

    .notification-close:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    /* Legacy alert styles for fallback */
    .alert {
        display: none;
    }

    @media (max-width: 900px) {
        .profile-section {
            margin-left: 0;
            padding: 20px;
            max-width: 100vw;
        }
    }

    @media (max-width: 768px) {
        .profile-section {
            margin-left: 0px;
            padding: 10px;
        }
        .form-container {
            padding: 15px;
        }
    }
</style>

<div class="profile-section">
    <div class="section-header">
        <h1 class="section-title">Change Password</h1>
        <p class="section-subtitle">Update your account password</p>
    </div>
    
    <!-- Top Notification Container -->
    <div class="notification-container" id="notificationContainer">
        @if(session('success'))
            <div class="notification notification-success" id="successNotification">
                <i class="fas fa-check-circle notification-icon"></i>
                <div class="notification-content">{{ session('success') }}</div>
                <button class="notification-close" onclick="closeNotification('successNotification')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="notification notification-error" id="errorNotification">
                <i class="fas fa-exclamation-triangle notification-icon"></i>
                <div class="notification-content">
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="notification-close" onclick="closeNotification('errorNotification')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>

    <div class="form-container">
        <form action="{{ route('update.profile') }}" method="POST" id="profileForm">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
            </div>

            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" placeholder="Enter current password to change">
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" placeholder="Leave blank to keep current">
            </div>
            
            <div class="form-group">
                <label for="new_password_confirmation">Confirm New Password</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm new password">
            </div>
            
            <button type="submit" class="submit-btn">Update Password</button>
        </form>
    </div>
</div>


<script>
// Form validation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    const currentPassword = document.getElementById('current_password').value;
    
    // If user wants to change password
    if (newPassword || confirmPassword || currentPassword) {
        if (!currentPassword) {
            e.preventDefault();
            alert('Please enter your current password to change your password.');
            document.getElementById('current_password').focus();
            return;
        }
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('New password and confirmation password do not match.');
            document.getElementById('new_password_confirmation').focus();
            return;
        }
        
        if (newPassword.length < 6) {
            e.preventDefault();
            alert('New password must be at least 6 characters long.');
            document.getElementById('new_password').focus();
            return;
        }
    }
});

// Notification system
function showNotification(type, message) {
    const container = document.getElementById('notificationContainer');
    const notificationId = 'notification_' + Date.now();
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.id = notificationId;
    
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    notification.innerHTML = `
        <i class="fas ${icon} notification-icon"></i>
        <div class="notification-content">${message}</div>
        <button class="notification-close" onclick="closeNotification('${notificationId}')">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(notification);
    
    // Trigger animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        closeNotification(notificationId);
    }, 5000);
}

function closeNotification(notificationId) {
    const notification = document.getElementById(notificationId);
    if (notification) {
        notification.classList.add('hide');
        setTimeout(() => {
            notification.remove();
        }, 400);
    }
}

// Initialize notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    const notifications = document.querySelectorAll('.notification');
    notifications.forEach(function(notification, index) {
        setTimeout(() => {
            notification.classList.add('show');
        }, 100 + (index * 100));
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            closeNotification(notification.id);
        }, 5000);
    });
});
</script>
@endsection
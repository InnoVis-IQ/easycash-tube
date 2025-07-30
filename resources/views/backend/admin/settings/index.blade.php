@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
    <div class="nav-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ route('admin.settings') }}">Admin Settings</a></li>
        </ol>
    </div>

    @if (Session::has('success'))
        <div class="alert alert-success" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
            {{ Session::get('success') }}
        </div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
            {{ Session::get('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Site Configuration</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">
                                <i class="fa-solid fa-cog"></i> General Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab">
                                <i class="fa-solid fa-credit-card"></i> Payment Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="notifications-tab" data-toggle="tab" href="#notifications" role="tab">
                                <i class="fa-solid fa-bell"></i> Notifications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="announcements-tab" data-toggle="tab" href="#announcements" role="tab">
                                <i class="fa-solid fa-bullhorn"></i> Announcements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="system-tab" data-toggle="tab" href="#system" role="tab">
                                <i class="fa-solid fa-tools"></i> System
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content mt-4" id="settingsTabsContent">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <form method="POST" action="{{ route('admin.settings.general') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Daily Return Percentage (%)</label>
                                            <input type="number" step="0.1" class="form-control" name="daily_return_percentage" 
                                                   value="{{ $settings['daily_return_percentage'] }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Referral Bonus Percentage (%)</label>
                                            <input type="number" step="0.1" class="form-control" name="referral_bonus_percentage" 
                                                   value="{{ $settings['referral_bonus_percentage'] }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Minimum Withdrawal Limit (₨)</label>
                                            <input type="number" class="form-control" name="min_withdrawal_limit" 
                                                   value="{{ $settings['min_withdrawal_limit'] }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Maximum Withdrawal Limit (₨)</label>
                                            <input type="number" class="form-control" name="max_withdrawal_limit" 
                                                   value="{{ $settings['max_withdrawal_limit'] }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>New User Bonus (₨)</label>
                                            <input type="number" class="form-control" name="new_user_bonus" 
                                                   value="{{ $settings['new_user_bonus'] }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch mt-4">
                                                <input type="checkbox" class="custom-control-input" id="maintenance_mode" 
                                                       name="site_maintenance_mode" {{ $settings['site_maintenance_mode'] ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="maintenance_mode">Maintenance Mode</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" style="background-color: #28a745; border-color: #28a745;">Update General Settings</button>
                            </form>
                        </div>

                        <!-- Payment Settings -->
                        <div class="tab-pane fade" id="payment" role="tabpanel">
                            <form method="POST" action="{{ route('admin.settings.payment') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Payment Processing Fee (%)</label>
                                            <input type="number" step="0.1" class="form-control" name="payment_processing_fee" 
                                                   value="{{ $settings['payment_processing_fee'] }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="easypaisa_enabled" 
                                                   name="easypaisa_enabled" {{ $settings['easypaisa_enabled'] ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="easypaisa_enabled">Easypaisa</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="jazzcash_enabled" 
                                                   name="jazzcash_enabled" {{ $settings['jazzcash_enabled'] ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="jazzcash_enabled">JazzCash</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="nayapay_enabled" 
                                                   name="nayapay_enabled" {{ $settings['nayapay_enabled'] ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="nayapay_enabled">NayaPay</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="bank_transfer_enabled" 
                                                   name="bank_transfer_enabled" {{ $settings['bank_transfer_enabled'] ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="bank_transfer_enabled">Bank Transfer</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">Update Payment Settings</button>
                            </form>
                        </div>

                        <!-- Notification Settings -->
                        <div class="tab-pane fade" id="notifications" role="tabpanel">
                            <form method="POST" action="{{ route('admin.settings.notifications') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Withdrawal Notification Threshold (₨)</label>
                                            <input type="number" class="form-control" name="withdrawal_notification_threshold" 
                                                   value="{{ $settings['withdrawal_notification_threshold'] }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="email_notifications_enabled" 
                                                   name="email_notifications_enabled" {{ $settings['email_notifications_enabled'] ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="email_notifications_enabled">Email Notifications</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="sms_notifications_enabled" 
                                                   name="sms_notifications_enabled" {{ $settings['sms_notifications_enabled'] ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="sms_notifications_enabled">SMS Notifications</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="push_notifications_enabled" 
                                                   name="push_notifications_enabled" {{ $settings['push_notifications_enabled'] ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="push_notifications_enabled">Push Notifications</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" style="background-color: #28a745; border-color: #28a745;">Update Notification Settings</button>
                            </form>
                        </div>

                        <!-- Announcements -->
                        <div class="tab-pane fade" id="announcements" role="tabpanel">
                            <form method="POST" action="{{ route('admin.settings.announcement') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Announcement Title</label>
                                            <input type="text" class="form-control" name="announcement_title" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Announcement Type</label>
                                            <select class="form-control" name="announcement_type" required>
                                                <option value="info">Info</option>
                                                <option value="warning">Warning</option>
                                                <option value="success">Success</option>
                                                <option value="danger">Danger</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Target Users</label>
                                            <select class="form-control" name="target_users" id="target_users" required>
                                                <option value="all">All Users</option>
                                                <option value="active">Active Users Only</option>
                                                <option value="inactive">Inactive Users Only</option>
                                                <option value="specific">Specific Users</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="specific_users_div" style="display: none;">
                                            <label>Select Specific Users</label>
                                            <select class="form-control" name="user_ids[]" multiple>
                                                @foreach(\App\Models\User::all() as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Announcement Message</label>
                                    <textarea class="form-control" name="announcement_message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">Send Announcement</button>
                            </form>
                        </div>

                        <!-- System -->
                        <div class="tab-pane fade" id="system" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Cache Management</h5>
                                            <p class="card-text">Clear all cached data to refresh the system.</p>
                                            <form method="POST" action="{{ route('admin.settings.clear-cache') }}">
                                                @csrf
                                                <button type="submit" class="btn btn-warning" style="background-color: #ffc107; border-color: #ffc107; color: #212529;">Clear Cache</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">System Information</h5>
                                            <p class="card-text">
                                                <strong>Laravel Version:</strong> {{ app()->version() }}<br>
                                                <strong>PHP Version:</strong> {{ phpversion() }}<br>
                                                <strong>Server:</strong> {{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
$(document).ready(function() {
    $('#target_users').change(function() {
        if ($(this).val() === 'specific') {
            $('#specific_users_div').show();
        } else {
            $('#specific_users_div').hide();
        }
    });
});
</script>
@endpush 
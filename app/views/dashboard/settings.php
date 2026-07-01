<?php $title = 'Settings - Hana-Eunhaeng'; ?>

<div class="container-lg">
    <div class="row mb-4">
        <div class="col-lg-12">
            <h1 class="fw-bold mb-2">Settings</h1>
            <p class="text-muted">Manage your account preferences and notifications.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="list-group">
                <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                    <i class="fas fa-cog me-2"></i>General
                </a>
                <a href="#notifications" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-bell me-2"></i>Notifications
                </a>
                <a href="#privacy" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-eye-slash me-2"></i>Privacy
                </a>
                <a href="#security" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-lock me-2"></i>Security
                </a>
                <a href="#preferences" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-sliders-h me-2"></i>Preferences
                </a>
            </div>
        </div>

        <div class="col-lg-9">
            <!-- General Settings -->
            <div class="card border-0 shadow-sm mb-4" id="general">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold">General Settings</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/dashboard/updateSettings">
                        <input type="hidden" name="csrf_token" value="<?php echo \App\Helpers\SecurityHelper::generateCsrfToken(); ?>">
                        <input type="hidden" name="section" value="general">

                        <div class="mb-3">
                            <label for="language" class="form-label">Language</label>
                            <select class="form-select" id="language" name="language">
                                <option value="en">English</option>
                                <option value="es">Español</option>
                                <option value="fr">Français</option>
                                <option value="de">Deutsch</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="timezone" class="form-label">Timezone</label>
                            <select class="form-select" id="timezone" name="timezone">
                                <option value="UTC">UTC</option>
                                <option value="America/New_York">Eastern Time</option>
                                <option value="America/Chicago">Central Time</option>
                                <option value="America/Denver">Mountain Time</option>
                                <option value="America/Los_Angeles">Pacific Time</option>
                            </select>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter" checked>
                            <label class="form-check-label" for="newsletter">
                                Subscribe to newsletter and updates
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="card border-0 shadow-sm mb-4" id="notifications" style="display: none;">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold">Notification Preferences</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/dashboard/updateSettings">
                        <input type="hidden" name="csrf_token" value="<?php echo \App\Helpers\SecurityHelper::generateCsrfToken(); ?>">
                        <input type="hidden" name="section" value="notifications">

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="trans_notify" name="transaction_notifications" checked>
                            <label class="form-check-label" for="trans_notify">
                                Transaction notifications
                            </label>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="security_notify" name="security_notifications" checked>
                            <label class="form-check-label" for="security_notify">
                                Security alerts
                            </label>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="promo_notify" name="promotional_notifications">
                            <label class="form-check-label" for="promo_notify">
                                Promotional messages
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Preferences
                        </button>
                    </form>
                </div>
            </div>

            <!-- Privacy Settings -->
            <div class="card border-0 shadow-sm" id="privacy" style="display: none;">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold">Privacy Settings</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/dashboard/updateSettings">
                        <input type="hidden" name="csrf_token" value="<?php echo \App\Helpers\SecurityHelper::generateCsrfToken(); ?>">
                        <input type="hidden" name="section" value="privacy">

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="profile_visible" name="profile_visible">
                            <label class="form-check-label" for="profile_visible">
                                Make profile visible to other users
                            </label>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="data_analytics" name="data_analytics" checked>
                            <label class="form-check-label" for="data_analytics">
                                Allow data analytics and personalization
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Privacy Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $title = 'My Profile - Hana-Eunhaeng'; ?>

<div class="container-lg">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2">My Profile</h1>
            <p class="text-muted">Manage your personal information and preferences.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user me-2 text-primary"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/dashboard/updateProfile">
                        <input type="hidden" name="csrf_token" value="<?php echo \App\Helpers\SecurityHelper::generateCsrfToken(); ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo $user['date_of_birth'] ?? ''; ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Address Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Address</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/dashboard/updateAddress">
                        <input type="hidden" name="csrf_token" value="<?php echo \App\Helpers\SecurityHelper::generateCsrfToken(); ?>">

                        <div class="mb-3">
                            <label for="street" class="form-label">Street Address</label>
                            <input type="text" class="form-control" id="street" name="street" value="<?php echo htmlspecialchars($user['street'] ?? ''); ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state" value="<?php echo htmlspecialchars($user['state'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="zip_code" class="form-label">Zip Code</label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo htmlspecialchars($user['zip_code'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country" value="<?php echo htmlspecialchars($user['country'] ?? ''); ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Address
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Account Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-shield-alt me-2 text-success"></i>Account Status</h6>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Verification Status</p>
                        <p class="fw-bold"><span class="badge bg-success">Verified</span></p>
                    </div>
                    <div class="mb-0">
                        <p class="text-muted small mb-1">Member Since</p>
                        <p class="fw-bold"><?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                    </div>
                </div>
            </div>

            <!-- Security Options -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-lock me-2 text-primary"></i>Security</h6>
                    <a href="/dashboard/changePassword" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-key me-2"></i>Change Password
                    </a>
                    <a href="/dashboard/twoFactor" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-shield-alt me-2"></i>Two-Factor Auth
                    </a>
                    <a href="/dashboard/sessions" class="btn btn-outline-primary w-100">
                        <i class="fas fa-laptop me-2"></i>Active Sessions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

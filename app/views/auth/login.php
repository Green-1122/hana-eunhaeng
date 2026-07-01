<?php $title = 'Login - Hana-Eunhaeng'; ?>

<div class="container-sm mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-building text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-3 fw-bold">Hana-Eunhaeng</h2>
                        <p class="text-muted">Welcome Back</p>
                    </div>

                    <form method="POST" action="/auth/login">
                        <input type="hidden" name="csrf_token" value="<?php echo \App\Helpers\SecurityHelper::generateCsrfToken(); ?>">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <small class="text-muted">We'll never share your email with anyone else.</small>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">Don't have an account? <a href="/auth/register" class="fw-bold text-decoration-none">Sign up here</a></p>
                    </div>

                    <div class="text-center mt-3">
                        <a href="/auth/forgot-password" class="text-muted text-decoration-none small">Forgot password?</a>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center text-muted small">
                <p><i class="fas fa-lock me-2"></i>Your account is secured with bank-level encryption.</p>
            </div>
        </div>
    </div>
</div>

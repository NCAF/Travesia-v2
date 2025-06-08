/**
 * CSRF Token & Session Management
 * Menangani auto-refresh CSRF token dan session keep-alive
 */

// Global variables
let csrfRefreshInterval;
let sessionKeepAliveInterval;
let isRefreshingToken = false;

// Configuration
const CSRF_CONFIG = {
    refreshInterval: 10 * 60 * 1000, // 10 menit
    keepAliveInterval: 5 * 60 * 1000, // 5 menit
    maxRetries: 3,
    retryDelay: 2000
};

/**
 * Initialize CSRF and Session Management
 */
function initCSRFManager() {
    // Setup CSRF token in AJAX headers
    setupAjaxCSRF();

    // Start auto-refresh CSRF token
    startCSRFRefresh();

    // Start session keep-alive
    startSessionKeepAlive();

    // Handle form submissions
    handleFormSubmissions();

    // Handle AJAX errors
    handleAjaxErrors();

    console.log('CSRF Manager initialized');
}

/**
 * Setup CSRF token for all AJAX requests
 */
function setupAjaxCSRF() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function (xhr, settings) {
            // Update token sebelum request
            const currentToken = $('meta[name="csrf-token"]').attr('content');
            if (currentToken) {
                xhr.setRequestHeader('X-CSRF-TOKEN', currentToken);
            }
        }
    });
}

/**
 * Refresh CSRF Token
 */
async function refreshCSRFToken(retryCount = 0) {
    if (isRefreshingToken) return;

    isRefreshingToken = true;

    try {
        const response = await fetch('/api/csrf-token', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            const data = await response.json();
            updateCSRFToken(data.csrf_token);
            console.log('CSRF token refreshed successfully');
        } else if (response.status === 419 && retryCount < CSRF_CONFIG.maxRetries) {
            // Token expired, retry after delay
            setTimeout(() => {
                refreshCSRFToken(retryCount + 1);
            }, CSRF_CONFIG.retryDelay);
        } else {
            console.warn('Failed to refresh CSRF token:', response.status);
            handleCSRFError();
        }
    } catch (error) {
        console.error('Error refreshing CSRF token:', error);
        if (retryCount < CSRF_CONFIG.maxRetries) {
            setTimeout(() => {
                refreshCSRFToken(retryCount + 1);
            }, CSRF_CONFIG.retryDelay);
        } else {
            handleCSRFError();
        }
    } finally {
        isRefreshingToken = false;
    }
}

/**
 * Update CSRF token in DOM
 */
function updateCSRFToken(newToken) {
    // Update meta tag
    $('meta[name="csrf-token"]').attr('content', newToken);

    // Update all forms
    $('input[name="_token"]').val(newToken);

    // Update AJAX setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': newToken
        }
    });
}

/**
 * Session Keep-alive
 */
async function sessionKeepAlive() {
    try {
        await fetch('/api/keep-alive', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        console.log('Session keep-alive ping sent');
    } catch (error) {
        console.warn('Session keep-alive failed:', error);
    }
}

/**
 * Start auto-refresh CSRF token
 */
function startCSRFRefresh() {
    if (csrfRefreshInterval) {
        clearInterval(csrfRefreshInterval);
    }

    csrfRefreshInterval = setInterval(() => {
        refreshCSRFToken();
    }, CSRF_CONFIG.refreshInterval);
}

/**
 * Start session keep-alive
 */
function startSessionKeepAlive() {
    if (sessionKeepAliveInterval) {
        clearInterval(sessionKeepAliveInterval);
    }

    sessionKeepAliveInterval = setInterval(() => {
        sessionKeepAlive();
    }, CSRF_CONFIG.keepAliveInterval);
}

/**
 * Handle form submissions with CSRF protection
 */
function handleFormSubmissions() {
    $(document).on('submit', 'form', function (e) {
        const form = $(this);
        const tokenInput = form.find('input[name="_token"]');

        // Pastikan form memiliki CSRF token
        if (tokenInput.length === 0) {
            const currentToken = $('meta[name="csrf-token"]').attr('content');
            if (currentToken) {
                form.append(`<input type="hidden" name="_token" value="${currentToken}">`);
            }
        } else {
            // Update token yang ada
            const currentToken = $('meta[name="csrf-token"]').attr('content');
            if (currentToken) {
                tokenInput.val(currentToken);
            }
        }
    });
}

/**
 * Handle AJAX errors
 */
function handleAjaxErrors() {
    $(document).ajaxError(function (event, xhr, settings) {
        if (xhr.status === 419) { // CSRF token mismatch
            console.warn('CSRF token mismatch detected');

            // Coba refresh token dan retry request
            refreshCSRFToken().then(() => {
                // Show user-friendly message
                showCSRFErrorMessage();
            });
        }
    });
}

/**
 * Handle CSRF errors
 */
function handleCSRFError() {
    // Stop intervals
    if (csrfRefreshInterval) clearInterval(csrfRefreshInterval);
    if (sessionKeepAliveInterval) clearInterval(sessionKeepAliveInterval);

    // Show error message
    showCSRFErrorMessage(true);
}

/**
 * Show CSRF error message to user
 */
function showCSRFErrorMessage(critical = false) {
    const message = critical
        ? 'Sesi Anda telah berakhir. Halaman akan dimuat ulang untuk melanjutkan.'
        : 'Sesi Anda akan segera berakhir. Silakan simpan pekerjaan Anda.';

    // Remove existing alerts
    $('.csrf-alert').remove();

    // Create alert
    const alertClass = critical ? 'alert-danger' : 'alert-warning';
    const alert = $(`
        <div class="alert ${alertClass} alert-dismissible fade show csrf-alert" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
            <strong>${critical ? 'Sesi Berakhir!' : 'Peringatan Sesi!'}</strong><br>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    $('body').append(alert);

    // Auto dismiss after 5 seconds if not critical
    if (!critical) {
        setTimeout(() => {
            alert.alert('close');
        }, 5000);
    } else {
        // Reload page after 3 seconds if critical
        setTimeout(() => {
            location.reload();
        }, 3000);
    }
}

/**
 * Stop all intervals (useful for cleanup)
 */
function stopCSRFManager() {
    if (csrfRefreshInterval) {
        clearInterval(csrfRefreshInterval);
        csrfRefreshInterval = null;
    }

    if (sessionKeepAliveInterval) {
        clearInterval(sessionKeepAliveInterval);
        sessionKeepAliveInterval = null;
    }

    console.log('CSRF Manager stopped');
}

/**
 * Manual token refresh (can be called from UI)
 */
function manualRefreshToken() {
    return refreshCSRFToken();
}

// Initialize when DOM is ready
$(document).ready(function () {
    // Only initialize if we have CSRF token
    if ($('meta[name="csrf-token"]').length > 0) {
        initCSRFManager();
    }
});

// Cleanup on page unload
$(window).on('beforeunload', function () {
    stopCSRFManager();
});

// Export functions for global access
window.CSRFManager = {
    init: initCSRFManager,
    stop: stopCSRFManager,
    refreshToken: manualRefreshToken,
    keepAlive: sessionKeepAlive
}; 